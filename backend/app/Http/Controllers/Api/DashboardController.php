<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Services\DashboardStatsService;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function stats(DashboardStatsService $service): JsonResponse
    {
        // TODO: usare cache applicativa
        return response()->json([
            'data' => $service->get(),
        ]);
    }
}
