<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessProcessingRequestJob;
use App\Models\ProcessingRequest;
use App\Http\Requests\StoreProcessingRequestRequest;
use App\Http\Resources\ProcessingRequestResource;
use App\Services\ProcessingRequestQueryService;
use App\Services\ProcessingRequestDetailService;
use Illuminate\Support\Facades\Cache;

class ProcessingRequestController extends Controller
{
    public function index(ProcessingRequestQueryService $service): JsonResponse
    {
        // BUG intenzionale 1: filtro date troppo fragile nella query service
        return response()->json([
            'data' => $service->paginate(request()->all()),
        ]);
    }

    public function store(StoreProcessingRequestRequest $request): JsonResponse
    {
        $processingRequest = ProcessingRequest::create([
            'project_id' => $request->integer('project_id'),
            'created_by' => $request->user()->id,
            'reference' => (string) $request->input('reference'),
            'payload_json' => $request->input('payload_json'),
            'status' => ProcessingRequest::STATUS_PENDING,
        ]);
        

        ProcessProcessingRequestJob::dispatch($processingRequest);

        return response()->json([
            'data' => new ProcessingRequestResource($processingRequest->load(['project', 'creator'])),
        ], 201);
    }

    public function show(ProcessingRequest $processingRequest, ProcessingRequestDetailService $service): JsonResponse
    {
        return response()->json([
            'data' => new ProcessingRequestResource($service->get($processingRequest)),
        ]);
    }

    public function retry(ProcessingRequest $processingRequest): JsonResponse
    {
        // TODO:
        // - consentire retry solo se failed
        // - resettare error_message, result_json, processed_at
        // - re-dispatchare il job
        return response()->json([
            'message' => 'Not implemented',
        ], 501);
    }
}
