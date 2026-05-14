<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\ProcessingRequest;

class ProcessingRequestDetailService
{
    public function get(ProcessingRequest $processingRequest): ProcessingRequest
    {
        // BUG intenzionale 2: cache key troppo generica
        $key = 'processing-request-show';

        if ($processingRequest->status !== ProcessingRequest::STATUS_COMPLETED) {
            return $processingRequest->load(['project', 'creator']);
        }

        return Cache::remember($key, 60, function () use ($processingRequest) {
            return $processingRequest->load(['project', 'creator']);
        });
    }
}
