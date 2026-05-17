<?php

namespace App\Services;

use App\Models\ProcessingRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardStatsService
{
    public function get(): array
    {
        $page = request()->integer('page', 1);
        $cacheKey = 'dashboard_stats_page_' . $page;

        // I did wrap the ENTIRE logic in the cache to avoid any DB calls during a cache hit.
        return Cache::remember($cacheKey, 120, function () {
            $statusCounts = ProcessingRequest::query()
                ->select('status', DB::raw('COUNT(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status')
                ->all();

            $avgSeconds = ProcessingRequest::query()
                ->where('status', ProcessingRequest::STATUS_COMPLETED)
                ->whereNotNull('processed_at')
                ->selectRaw("AVG(strftime('%s', processed_at) - strftime('%s', created_at)) as avg_seconds")
                ->value('avg_seconds');

            return [
                'total' => ProcessingRequest::count(),
                'requests' => ProcessingRequest::with(['project', 'creator'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(15)
                    ->through(function ($request) {
                        return [
                            'id' => $request->id,
                            'project_id' => $request->project_id,
                            'reference' => $request->reference,
                            'status' => $request->status,
                            'created_at' => $request->created_at,
                            'processed_at' => $request->processed_at,
                            'payload' => $request->payload,
                            'result' => $request->result,
                            'created_by' => $request->creator?->name ?? ('User #' . $request->created_by),
                            'project' => $request->project ? [
                                'id' => $request->project->id,
                                'name' => $request->project->name,
                            ] : null,
                        ];
                    }),
                'by_status' => [
                    'pending' => (int) ($statusCounts['pending'] ?? 0),
                    'processing' => (int) ($statusCounts['processing'] ?? 0),
                    'completed' => (int) ($statusCounts['completed'] ?? 0),
                    'failed' => (int) ($statusCounts['failed'] ?? 0),
                ],
                'created_today' => ProcessingRequest::query()
                    ->whereDate('created_at', today())
                    ->count(),
                'avg_processing_seconds' => $avgSeconds !== null ? round((float) $avgSeconds, 2) : null,
            ];
        });
    }
}
