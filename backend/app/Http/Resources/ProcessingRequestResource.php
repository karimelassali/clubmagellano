<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProcessingRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'project_id' => $this->project_id,
            'reference' => $this->reference,
            'status' => $this->status,
            'payload_json' => $this->payload_json,
            'result_json' => $this->result_json,
            'error_message' => $this->error_message,
            'processed_at' => optional($this->processed_at)?->toISOString(),
            'created_at' => optional($this->created_at)?->toISOString(),
            'project' => $this->whenLoaded('project'),
            'creator' => $this->whenLoaded('creator'),
        ];
    }
}
