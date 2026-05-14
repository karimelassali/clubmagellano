<?php

namespace App\Services;

use App\Models\Project;

class ProjectQueryService
{
    public function getActiveProjects()
    {
        return Project::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}
