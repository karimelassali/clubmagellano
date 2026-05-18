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
        // - consentire retry solo se failed-Done
        // - resettare error_message, result_json, processed_at-Done
        // - re-dispatchare il job-Done


        //Checking if the request is failed.
        if($processingRequest->status == ProcessingRequest::STATUS_FAILED){
            //Resetting the request to pending.
            $processingRequest->update([
                'status' => ProcessingRequest::STATUS_PENDING,
                'error_message' => null,
                'result_json' => null,
                'processed_at' => null,
            ]);
            
            //Invalidate caches
            Cache::forget('processing-request-show:' . $processingRequest->id);
            Cache::put('dashboard_stats_version', time());
            
            //Re-dispatching the job.
            ProcessProcessingRequestJob::dispatch($processingRequest);
            
            //Returning the updated request.
            return response()->json([
                'data' => new ProcessingRequestResource($processingRequest->load(['project', 'creator'])),
            ]);
        }
        

        //We make sure that only the failed requests can be retried. So if the request is not failed, we return an error.501 means not implemented.
        return response()->json([
            'message' => 'Only Failed requests can be retried.',
        ], 422);
    }
}
