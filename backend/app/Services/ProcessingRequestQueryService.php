<?php

namespace App\Services;

use App\Models\ProcessingRequest;

class ProcessingRequestQueryService
{
    public function paginate(array $filters)
    {
        $query = ProcessingRequest::query()
            ->with(['project', 'creator'])
            ->latest();

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['project_id'])) {
            $query->where('project_id', $filters['project_id']);
        }

        if (! empty($filters['reference'])) {
            $query->where('reference', 'like', '%' . $filters['reference'] . '%');
        }

        // BUG intenzionale 1: filtro range fragile / incompleto
        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->paginate(15);
    }
}
