<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'status' => 'sometimes|nullable|string|in:pending,in_progress,completed,cancelled',
            'priority' => 'sometimes|nullable|string|in:low,medium,high',
            'due_date' => 'sometimes|nullable|date',
            'user_id' => 'sometimes|nullable|exists:users,id',
        ];
    }
}
