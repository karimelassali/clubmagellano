<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Cache;

class ProjectQueryService
{
    public function getActiveProjects()
    {
        $projects = Cache::remember('active_projects', 300, function () {
            return Project::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get();
        });
        return $projects;
    }
}
