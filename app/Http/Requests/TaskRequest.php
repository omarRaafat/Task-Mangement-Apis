<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:pending,in-progress,completed',
            'due_date' => 'required|date|after:today',
            'assigned_to' => 'sometimes|exists:users,id',
        ];
    
        // For update, make fields optional
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['title'] = 'sometimes|string|max:255';
            $rules['description'] = 'sometimes|string';
            $rules['due_date'] = 'sometimes|date|after:today';
            $rules['assigned_to'] = 'sometimes|exists:users,id';
        }
    
        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'description.required' => 'The description field is required.',
            'description.string' => 'The description must be a string.',
            'status.required' => 'The status field is required.',
            'status.in' => 'The status must be one of: pending, in-progress, completed.',
            'due_date.required' => 'The due date field is required.',
            'due_date.date' => 'The due date must be a valid date.',
            'due_date.after' => 'The due date must be a date after today.',
            'assigned_to.required' => 'The assigned to field is required.',
            'assigned_to.exists' => 'The selected user does not exist.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'assigned_to' => 'assigned user',
        ];
    }
}