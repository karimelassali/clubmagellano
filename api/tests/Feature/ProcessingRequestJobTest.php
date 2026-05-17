<?php

use App\Jobs\ProcessProcessingRequestJob;
use App\Models\ProcessingRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

// ─── Shared setup ─────────────────────────────────────────────────────────────
beforeEach(function () {
    $this->user = User::create([
        'name'     => 'Test User',
        'email'    => 'test@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->project = Project::create([
        'name'      => 'Test Project',
        'slug'      => 'test-project',
        'is_active' => true,
    ]);

    // A valid payload the ProcessingEngine will accept
    $this->validPayload = [
        'customer' => 'ACME',
        'items' => [
            ['sku' => 'A1', 'qty' => 2, 'price' => 10.00],
            ['sku' => 'B2', 'qty' => 1, 'price' => 30.00],
        ],
    ];
});

// ─── Helper: create a ProcessingRequest row ───────────────────────────────────
function makeJobRequest(string $status, ?array $payload = null): ProcessingRequest
{
    return ProcessingRequest::create([
        'project_id'   => test()->project->id,
        'created_by'   => test()->user->id,
        'reference'    => 'JOB-TEST-' . uniqid(),
        'payload_json' => $payload ?? test()->validPayload,
        'status'       => $status,
    ]);
}

// ─── 1. Happy path ────────────────────────────────────────────────────────────
test('job processes a pending request and marks it completed', function () {
    $req = makeJobRequest(ProcessingRequest::STATUS_PENDING);

    ProcessProcessingRequestJob::dispatchSync($req);

    $req->refresh();

    expect($req->status)->toBe(ProcessingRequest::STATUS_COMPLETED)
        ->and($req->result_json)->toBeArray()
        ->and($req->result_json)->toHaveKey('grand_total')
        ->and($req->processed_at)->not->toBeNull()
        ->and($req->error_message)->toBeNull();
});

// ─── 2. Concurrency guard: skip if already processing ────────────────────────
test('job skips a request that is already being processed by another worker', function () {
    // Simulate a request another worker already picked up (status = processing)
    $req = makeJobRequest(ProcessingRequest::STATUS_PROCESSING);

    ProcessProcessingRequestJob::dispatchSync($req);

    $req->refresh();

    // Nothing must have changed — the DB lock check bailed out
    expect($req->status)->toBe(ProcessingRequest::STATUS_PROCESSING)
        ->and($req->result_json)->toBeNull()
        ->and($req->processed_at)->toBeNull();
});

// ─── 3. Concurrency guard: skip if already completed ─────────────────────────
test('job skips a request that is already completed', function () {
    $req = makeJobRequest(ProcessingRequest::STATUS_COMPLETED);
    $req->update([
        'result_json'  => ['grand_total' => 99.99],
        'processed_at' => now()->subMinutes(5),
    ]);

    ProcessProcessingRequestJob::dispatchSync($req);

    $req->refresh();

    // Original result must be preserved
    expect($req->status)->toBe(ProcessingRequest::STATUS_COMPLETED)
        ->and($req->result_json['grand_total'])->toBe(99.99);
});

// ─── 4. Engine failure: marks request as failed with error message ────────────
test('job marks request as failed when the engine throws an exception', function () {
    // Empty items array causes ProcessingEngine to throw RuntimeException
    $req = makeJobRequest(ProcessingRequest::STATUS_PENDING, [
        'customer' => 'ACME',
        'items'    => [],
    ]);

    expect(fn () => ProcessProcessingRequestJob::dispatchSync($req))
        ->toThrow(RuntimeException::class, 'Items array is required.');

    $req->refresh();

    expect($req->status)->toBe(ProcessingRequest::STATUS_FAILED)
        ->and($req->error_message)->toBe('Items array is required.')
        ->and($req->result_json)->toBeNull();
});

// ─── 5. Cache invalidated on success ─────────────────────────────────────────
test('job clears the dashboard_stats cache after successful processing', function () {
    Cache::put('dashboard_stats', ['total' => 999], 120);

    $req = makeJobRequest(ProcessingRequest::STATUS_PENDING);

    ProcessProcessingRequestJob::dispatchSync($req);

    expect(Cache::has('dashboard_stats'))->toBeFalse();
});

// ─── 6. Cache invalidated even on failure (finally block) ────────────────────
test('job clears the dashboard_stats cache even when the engine throws', function () {
    Cache::put('dashboard_stats', ['total' => 999], 120);

    $req = makeJobRequest(ProcessingRequest::STATUS_PENDING, [
        'customer' => 'ACME',
        'items'    => [],
    ]);

    try {
        ProcessProcessingRequestJob::dispatchSync($req);
    } catch (RuntimeException) {
        // Expected — we only care about the cache side-effect below
    }

    expect(Cache::has('dashboard_stats'))->toBeFalse();
});

// ─── 7. Result shape is mathematically correct ────────────────────────────────
test('completed request stores the correct calculated result from the engine', function () {
    $req = makeJobRequest(ProcessingRequest::STATUS_PENDING, [
        'customer' => 'ACME',
        'items' => [
            ['sku' => 'A1', 'qty' => 2, 'price' => 10.00],  // 20
            ['sku' => 'B2', 'qty' => 1, 'price' => 30.00],  // 30 → total 50
        ],
    ]);

    ProcessProcessingRequestJob::dispatchSync($req);

    $req->refresh();

    expect($req->result_json['customer'])->toBe('ACME')
        ->and($req->result_json['items_count'])->toBe(2)
        ->and((float) $req->result_json['total_amount'])->toEqual(50.0)
        ->and((float) $req->result_json['vat'])->toEqual(11.0)         // 50 * 0.22
        ->and((float) $req->result_json['grand_total'])->toEqual(61.0); // 50 + 11
});
