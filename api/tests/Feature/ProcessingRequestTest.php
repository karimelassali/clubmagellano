<?php

use App\Models\User;
use App\Models\Project;
use App\Models\ProcessingRequest;
use App\Jobs\ProcessProcessingRequestJob;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    $this->user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->activeProject = Project::create([
        'name' => 'Active Project',
        'slug' => 'active-project',
        'is_active' => true,
    ]);

    $this->inactiveProject = Project::create([
        'name' => 'Inactive Project',
        'slug' => 'inactive-project',
        'is_active' => false,
    ]);
});

test('it returns only active projects', function () {
    $response = $this->actingAs($this->user)
        ->getJson('/api/projects');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $this->activeProject->id);
});

test('it caches active projects for 5 minutes', function () {
    Cache::shouldReceive('remember')
        ->once()
        ->with('active_projects', 300, Closure::class)
        ->andReturn(collect([$this->activeProject]));

    $this->actingAs($this->user)->getJson('/api/projects');
});

test('it lists processing requests with pagination', function () {
    ProcessingRequest::create([
        'project_id' => $this->activeProject->id,
        'created_by' => $this->user->id,
        'reference' => 'REF-001',
        'payload_json' => ['customer' => 'Test Customer', 'items' => [['qty' => 1, 'price' => 10]]],
    ]);

    $response = $this->actingAs($this->user)
        ->getJson('/api/processing-requests');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'current_page',
                'data',
                'first_page_url',
                'last_page',
                'total',
            ],
        ])
        ->assertJsonCount(1, 'data.data');
});

test('it validates request creation and dispatches processing job', function () {
    Queue::fake();

    $payload = [
        'project_id' => $this->activeProject->id,
        'reference' => 'REF-UNIQUE',
        'payload_json' => [
            'customer' => 'Acme Inc',
            'items' => [
                ['qty' => 5, 'price' => 12.5],
            ],
        ],
    ];

    $response = $this->actingAs($this->user)
        ->postJson('/api/processing-requests', $payload);

    $response->assertStatus(201)
        ->assertJsonPath('data.reference', 'REF-UNIQUE')
        ->assertJsonPath('data.status', 'pending');

    $this->assertDatabaseHas('processing_requests', [
        'reference' => 'REF-UNIQUE',
        'status' => 'pending',
    ]);

    Queue::assertPushed(ProcessProcessingRequestJob::class);
});

test('it rejects request creation with inactive projects', function () {
    $payload = [
        'project_id' => $this->inactiveProject->id,
        'reference' => 'REF-001',
        'payload_json' => [
            'customer' => 'Acme Inc',
            'items' => [
                ['qty' => 5, 'price' => 12.5],
            ],
        ],
    ];

    $response = $this->actingAs($this->user)
        ->postJson('/api/processing-requests', $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['project_id']);
});

test('it rejects request creation with invalid nested values', function () {
    // 1. Invalid qty (<= 0)
    $payload = [
        'project_id' => $this->activeProject->id,
        'reference' => 'REF-QTY',
        'payload_json' => [
            'customer' => 'Acme Inc',
            'items' => [
                ['qty' => 0, 'price' => 12.5],
            ],
        ],
    ];

    $response = $this->actingAs($this->user)
        ->postJson('/api/processing-requests', $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['payload_json.items.0.qty']);

    // 2. Invalid price (< 0)
    $payload = [
        'project_id' => $this->activeProject->id,
        'reference' => 'REF-PRICE',
        'payload_json' => [
            'customer' => 'Acme Inc',
            'items' => [
                ['qty' => 5, 'price' => -1],
            ],
        ],
    ];

    $response = $this->actingAs($this->user)
        ->postJson('/api/processing-requests', $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['payload_json.items.0.price']);
});

test('it rejects request creation if reference is duplicate for same project', function () {
    ProcessingRequest::create([
        'project_id' => $this->activeProject->id,
        'created_by' => $this->user->id,
        'reference' => 'REF-DUP',
        'payload_json' => ['customer' => 'Cust', 'items' => [['qty' => 1, 'price' => 1]]],
    ]);

    $payload = [
        'project_id' => $this->activeProject->id,
        'reference' => 'REF-DUP',
        'payload_json' => [
            'customer' => 'Acme Inc',
            'items' => [
                ['qty' => 5, 'price' => 12.5],
            ],
        ],
    ];

    $response = $this->actingAs($this->user)
        ->postJson('/api/processing-requests', $payload);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['reference']);
});

test('it only caches request detail when status is completed', function () {
    $request = ProcessingRequest::create([
        'project_id' => $this->activeProject->id,
        'created_by' => $this->user->id,
        'reference' => 'REF-SHOW',
        'payload_json' => ['customer' => 'Cust', 'items' => [['qty' => 1, 'price' => 1]]],
        'status' => 'pending',
    ]);

    // 1. Pending requests are NOT cached
    Cache::shouldReceive('remember')->never();
    $this->actingAs($this->user)->getJson("/api/processing-requests/{$request->id}");

    // 2. Completed requests ARE cached
    $request->update(['status' => 'completed']);

    Cache::shouldReceive('remember')
        ->once()
        ->with('processing-request-show:' . $request->id, 60, Closure::class)
        ->andReturn($request);

    $this->actingAs($this->user)->getJson("/api/processing-requests/{$request->id}");
});

test('it retries failed requests and puts them back to pending', function () {
    Queue::fake();

    $request = ProcessingRequest::create([
        'project_id' => $this->activeProject->id,
        'created_by' => $this->user->id,
        'reference' => 'REF-RETRY',
        'payload_json' => ['customer' => 'Cust', 'items' => [['qty' => 1, 'price' => 1]]],
        'status' => 'failed',
        'error_message' => 'Something went wrong',
        'result_json' => ['error' => true],
        'processed_at' => now(),
    ]);

    $response = $this->actingAs($this->user)
        ->postJson("/api/processing-requests/{$request->id}/retry");

    $response->assertStatus(200)
        ->assertJsonPath('data.status', 'pending')
        ->assertJsonPath('data.error_message', null);

    $this->assertDatabaseHas('processing_requests', [
        'id' => $request->id,
        'status' => 'pending',
        'error_message' => null,
        'result_json' => null,
        'processed_at' => null,
    ]);

    Queue::assertPushed(ProcessProcessingRequestJob::class);
});

test('it does not allow retrying completed requests', function () {
    $request = ProcessingRequest::create([
        'project_id' => $this->activeProject->id,
        'created_by' => $this->user->id,
        'reference' => 'REF-RETRY-FAIL',
        'payload_json' => ['customer' => 'Cust', 'items' => [['qty' => 1, 'price' => 1]]],
        'status' => 'completed',
    ]);

    $response = $this->actingAs($this->user)
        ->postJson("/api/processing-requests/{$request->id}/retry");

    $response->assertStatus(422)
        ->assertJsonPath('message', 'Only Failed requests can be retried.');
});
