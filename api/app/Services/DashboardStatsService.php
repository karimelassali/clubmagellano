<?php

namespace App\Services;

use App\Models\ProcessingRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardStatsService
{
    public function get(): array
    {
        // I did wrap the ENTIRE logic in the cache to avoid any DB calls during a cache hit.
        return Cache::remember('dashboard_stats', 120, function () {
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
