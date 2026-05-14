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
        // Fixed By using Carbon to precisely set the time to startOfDay() and endOfDay() to include all records within the selected dates.
        return response()->json([
            'data' => $service->paginate(request()->all()),
        ]);
    }

    public function store(StoreProcessingRequestRequest $request): JsonResponse
    {
        //Implementig validation for the request.
        $request->validate([
            'reference' => 'required|unique:processing_requests,reference',
            'project_id' => 'required|exists:projects,id',
            'payload_json' => 'required|array',
            'payload_json.customer' => 'required|string',
            'payload_json.items' => 'required|array|min:1',
            'payload_json.items.*.sku' => 'required|string',
            'payload_json.items.*.qty' => 'required|integer|min:1',
            'payload_json.items.*.price' => 'required|min:0',
        ]);

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
        //Implementing pagination.
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
