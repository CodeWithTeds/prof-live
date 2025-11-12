<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class FilterTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'sometimes|nullable|string|in:pending,in_progress,completed,cancelled',
            'priority' => 'sometimes|nullable|string|in:low,medium,high',
            'user_id' => 'sometimes|nullable|exists:users,id',
            'project_id' => 'sometimes|integer|exists:project,id',
            'due_from' => 'sometimes|date',
            'due_to' => 'sometimes|date|after_or_equal|:due_from',
            'q' => 'sometimes|string|max:255',
            'sort' => 'sometimes|string|in:created_at,due_date,priority,status,title',
            'order' => 'sometimes|string|in:asc,desc',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
        ];
    }
}
