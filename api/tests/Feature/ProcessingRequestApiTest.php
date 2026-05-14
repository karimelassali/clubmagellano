<?php

use App\Models\User;
use App\Models\Project;
use App\Models\ProcessingRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\ProcessProcessingRequestJob;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->project = Project::create([
        'name' => 'Test Project',
        'slug' => 'test-project',
        'is_active' => true,
    ]);
});

test('it requires authentication', function () {
    $this->getJson('/api/processing-requests')
        ->assertStatus(401);
});

test('it returns paginated processing requests', function () {
    // Create 20 requests
    for ($i = 0; $i < 20; $i++) {
        ProcessingRequest::create([
            'project_id' => $this->project->id,
            'created_by' => $this->user->id,
            'reference' => 'REF-' . $i,
            'payload_json' => ['test' => true],
            'status' => 'pending',
        ]);
    }

    $this->actingAs($this->user)
        ->getJson('/api/processing-requests')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'current_page',
                'data',
                'total'
            ]
        ]);
});

test('it can retry a failed request', function () {
    Queue::fake();

    $request = ProcessingRequest::create([
        'project_id' => $this->project->id,
        'created_by' => $this->user->id,
        'reference' => 'RETRY-TEST',
        'payload_json' => ['test' => true],
        'status' => ProcessingRequest::STATUS_FAILED,
        'error_message' => 'Something went wrong',
    ]);

    $this->actingAs($this->user)
        ->postJson("/api/processing-requests/{$request->id}/retry")
        ->assertStatus(200)
        ->assertJsonPath('data.status', 'pending');

    $request->refresh();
    expect($request->status)->toBe('pending')
        ->and($request->error_message)->toBeNull();
    
    Queue::assertPushed(ProcessProcessingRequestJob::class);
});

test('it cannot retry a non-failed request', function () {
    $request = ProcessingRequest::create([
        'project_id' => $this->project->id,
        'created_by' => $this->user->id,
        'reference' => 'COMPLETED-RETRY',
        'payload_json' => ['test' => true],
        'status' => ProcessingRequest::STATUS_COMPLETED,
    ]);

    $this->actingAs($this->user)
        ->postJson("/api/processing-requests/{$request->id}/retry")
        ->assertStatus(422)
        ->assertJsonPath('message', 'Only Failed requests can be retried.');
});
