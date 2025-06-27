<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'dob' => 'nullable|date|before:today',
            'father' => 'nullable|string|max:255',
            'rphone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            
            // Country and college preferences
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'college' => 'nullable|string|max:255',
            'course' => 'nullable|string|max:255',
            
            // Background information
            'travel_history' => 'nullable|string|max:500',
            'any_refusal' => 'nullable|string|max:500',
            'spouse_name' => 'nullable|string|max:255',
            'any_gap' => 'nullable|string|max:500',
            
            // English proficiency
            'score_type' => 'nullable|in:ielts,pte,duolingo',
            
            // IELTS scores
            'ielts_listening' => 'nullable|numeric|between:0,9',
            'ielts_reading' => 'nullable|numeric|between:0,9',
            'ielts_writing' => 'nullable|numeric|between:0,9',
            'ielts_speaking' => 'nullable|numeric|between:0,9',
            'ielts_overall' => 'nullable|numeric|between:0,9',
            
            // PTE scores
            'pte_listening' => 'nullable|integer|between:0,90',
            'pte_reading' => 'nullable|integer|between:0,90',
            'pte_writing' => 'nullable|integer|between:0,90',
            'pte_speaking' => 'nullable|integer|between:0,90',
            'pte_overall' => 'nullable|integer|between:0,90',
            
            // Duolingo scores
            'duolingo_listening' => 'nullable|integer|between:0,160',
            'duolingo_reading' => 'nullable|integer|between:0,160',
            'duolingo_writing' => 'nullable|integer|between:0,160',
            'duolingo_speaking' => 'nullable|integer|between:0,160',
            'duolingo_overall' => 'nullable|integer|between:0,160',
            
            // Qualifications
            'last_qual' => 'nullable|in:12th,Diploma,Graduation,Post Graduation',
            
            // Employment history
            'employementhistory' => 'nullable|array',
            'employementhistory.*.join_date' => 'required_with:employementhistory|date',
            'employementhistory.*.left_date' => 'required_with:employementhistory|date|after_or_equal:employementhistory.*.join_date',
            'employementhistory.*.company_name' => 'required_with:employementhistory|string|max:255',
            'employementhistory.*.job_position' => 'required_with:employementhistory|string|max:255',
            'employementhistory.*.job_city' => 'required_with:employementhistory|string|max:255',
            
            // Source and reference
            'source' => 'nullable|string|max:255',
            'r_name' => 'nullable|string|max:255',
            'remarks' => 'nullable|string|max:1000',
            'status' => 'nullable|in:new,contacted,qualified,converted,rejected',
            'assigned_to' => 'nullable|exists:users,id',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Lead name is required',
            'phone.required' => 'Phone number is required',
            'email.email' => 'Please provide a valid email address',
            'dob.before' => 'Date of birth must be before today',
            'score_type.in' => 'Score type must be IELTS, PTE, or Duolingo',
            'ielts_*.between' => 'IELTS scores must be between 0 and 9',
            'pte_*.between' => 'PTE scores must be between 0 and 90',
            'duolingo_*.between' => 'Duolingo scores must be between 0 and 160',
            'employementhistory.*.left_date.after_or_equal' => 'End date must be after or equal to start date',
            'assigned_to.exists' => 'Assigned user does not exist',
        ];
    }

    /**
     * Get custom attribute names for error messages.
     */
    public function attributes(): array
    {
        return [
            'rphone' => 'alternate phone',
            'father' => 'father name',
            'last_qual' => 'last qualification',
            'r_name' => 'reference name',
            'employementhistory.*.join_date' => 'employment start date',
            'employementhistory.*.left_date' => 'employment end date',
            'employementhistory.*.company_name' => 'company name',
            'employementhistory.*.job_position' => 'job position',
            'employementhistory.*.job_city' => 'job city',
        ];
    }
}
