<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Project;

class StoreProcessingRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => [
                'required',
                'integer',
                Rule::exists(Project::class, 'id')->where('is_active', true),
            ],
            'reference' => [
                'required',
                'string',
                'max:100',
                // TODO: rendere univoco per progetto
            ],
            'payload_json' => ['required', 'array'],
            'payload_json.customer' => ['required', 'string', 'max:255'],
            'payload_json.items' => ['required', 'array', 'min:1'],
        ];
    }
}
