<?php

namespace App\Jobs;

use Throwable;
use App\Models\ProcessingRequest;
use Illuminate\Bus\Queueable;
use App\Services\ProcessingEngine;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ProcessProcessingRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public ProcessingRequest $processingRequest) {}

    public function handle(ProcessingEngine $engine): void
    {
        $request = $this->processingRequest->fresh();

        // Only process if it's still pending!
        if (! $request || $request->status !== ProcessingRequest::STATUS_PENDING) {
            return;
        }

        // TODO:
        // - proteggere da doppia elaborazione concorrente
        // - impostare processing
        // - invocare engine
        // - salvare result_json
        // - impostare completed e processed_at
        // - invalidare cache correlate

        $request->update([
            'status' => ProcessingRequest::STATUS_PROCESSING,
        ]);

        try {
            $result = $engine->process($request->payload_json ?? []);

            $request->update([
                'status' => ProcessingRequest::STATUS_COMPLETED,
                'result_json' => $result,
                'processed_at' => now(),
                'error_message' => null,
            ]);
        } catch (Throwable $e) {
            $request->update([
                'status' => ProcessingRequest::STATUS_FAILED,
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        } finally {
            // CACHE INVALIDATION: Ensure the dashboard shows fresh numbers!
            Cache::forget('dashboard_stats');
        }
    }
}
