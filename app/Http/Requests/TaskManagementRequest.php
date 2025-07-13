<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class TaskManagementRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'visa_form_id' => 'nullable|integer|min:1',
            'task_status' => 'nullable|in:open,closed',
            'deadline_start' => 'nullable|date_format:Y-m-d',
            'deadline_end' => 'nullable|date_format:Y-m-d|after_or_equal:deadline_start',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'visa_form_id.integer' => 'The visa_form_id must be a valid integer.',
            'task_status.in' => 'The task_status field must be either "open" or "closed".',
            'deadline_start.date_format' => 'The deadline_start must be in YYYY-MM-DD format.',
            'deadline_end.date_format' => 'The deadline_end must be in YYYY-MM-DD format.',
            'deadline_end.after_or_equal' => 'The deadline_end must be on or after deadline_start.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }

    /**
     * Prepare data for validation
     */
    protected function prepareForValidation()
    {
        // Set default values for user-controllable fields only
        $this->merge([
            'task_status' => $this->input('task_status', 'open'),
        ]);
    }
}
