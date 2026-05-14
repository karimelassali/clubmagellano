<?php

namespace App\Services;

use App\Models\ProcessingRequest;
use Illuminate\Support\Carbon;

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
        // Fixed By using Carbon to precisely set the time to startOfDay() and endOfDay() to include all records within the selected dates.
        if (! empty($filters['date_from'])) {
            //Using carbon.
            $date = Carbon::parse($filters['date_from'])->startOfDay();
            $query->where('created_at', '>=', $date);
        }

        if (! empty($filters['date_to'])) {
            //Using carbon.
            $date = Carbon::parse($filters['date_to'])->endOfDay();
            $query->where('created_at', '<=', $date);
        }

        return $query->paginate(15);
    }
}
