<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\ProjectQueryService;

class ProjectController extends Controller
{
    public function index(ProjectQueryService $service): JsonResponse
    {
        // TODO: usare cache applicativa e restituire solo progetti attivi
        return response()->json([
            'data' => $service->getActiveProjects(),
        ]);
    }
}
