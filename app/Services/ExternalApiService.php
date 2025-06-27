<?php

namespace App\Services;

use App\Models\Lead;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalApiService
{
    /**
     * Send lead data to external enquiry API
     */
    public function sendEnquiryLead(Lead $lead): bool
    {
        // Check if external API is enabled
        if (!config('services.external_api.enabled')) {
            Log::info('External API is disabled, skipping lead sync', ['lead_id' => $lead->id]);
            return true;
        }

        $apiUrl = config('services.external_api.enquiry_lead_url');
        
        if (!$apiUrl) {
            Log::error('External API URL not configured');
            return false;
        }

        $maxRetries = 3;
        $retryDelay = 1; // seconds

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                // Prepare lead data for external API
                $leadData = $this->prepareLeadData($lead);
                
                Log::info('Sending lead to external API', [
                    'lead_id' => $lead->id,
                    'lead_ref_no' => $lead->ref_no,
                    'lead_name' => $lead->name,
                    'created_by' => $lead->creator->name ?? 'Unknown',
                    'api_url' => $apiUrl,
                    'attempt' => $attempt,
                    'data_keys' => array_keys($leadData)
                ]);

                // Send POST request to external API
                $response = Http::timeout(30)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ])
                    ->post($apiUrl, $leadData);

                if ($response->successful()) {
                    Log::info('Lead successfully sent to external API', [
                        'lead_id' => $lead->id,
                        'lead_ref_no' => $lead->ref_no,
                        'created_by' => $lead->creator->name ?? 'Unknown',
                        'attempt' => $attempt,
                        'response_status' => $response->status(),
                        'response_body' => $response->body()
                    ]);
                    return true;
                } else {
                    Log::warning('Failed to send lead to external API', [
                        'lead_id' => $lead->id,
                        'attempt' => $attempt,
                        'response_status' => $response->status(),
                        'response_body' => $response->body()
                    ]);
                    
                    // If this is the last attempt, log as error
                    if ($attempt === $maxRetries) {
                        Log::error('All attempts failed to send lead to external API', [
                            'lead_id' => $lead->id,
                            'total_attempts' => $maxRetries
                        ]);
                        return false;
                    }
                }

            } catch (\Exception $e) {
                Log::warning('Exception occurred while sending lead to external API', [
                    'lead_id' => $lead->id,
                    'attempt' => $attempt,
                    'error' => $e->getMessage()
                ]);
                
                // If this is the last attempt, log as error
                if ($attempt === $maxRetries) {
                    Log::error('All attempts failed due to exceptions', [
                        'lead_id' => $lead->id,
                        'total_attempts' => $maxRetries,
                        'last_error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return false;
                }
            }

            // Wait before retry (except on last attempt)
            if ($attempt < $maxRetries) {
                sleep($retryDelay);
                $retryDelay *= 2; // Exponential backoff
            }
        }

        return false;
    }

    /**
     * Send application data to external enquiry API
     */
    public function sendApplication($application): bool
    {
        // Check if external API is enabled
        if (!config('services.external_api.enabled')) {
            Log::info('External API is disabled, skipping application sync', ['application_id' => $application->id]);
            return true;
        }

        $apiUrl = config('services.external_api.enquiry_lead_url');
        
        if (!$apiUrl) {
            Log::error('External API URL not configured');
            return false;
        }

        $maxRetries = 3;
        $retryDelay = 1; // seconds

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                // Prepare application data for external API
                $applicationData = $this->prepareApplicationData($application);
                
                Log::info('Sending application to external API', [
                    'application_id' => $application->id,
                    'application_number' => $application->application_number,
                    'applicant_name' => $application->name,
                    'created_by' => $application->creator->name ?? 'Unknown',
                    'documents_count' => $application->documents->count(),
                    'mandatory_documents' => $application->documents->where('is_mandatory', true)->count(),
                    'api_url' => $apiUrl,
                    'attempt' => $attempt,
                    'data_keys' => array_keys($applicationData)
                ]);

                // Send POST request to external API
                $response = Http::timeout(30)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ])
                    ->post($apiUrl, $applicationData);

                if ($response->successful()) {
                    Log::info('Application successfully sent to external API', [
                        'application_id' => $application->id,
                        'application_number' => $application->application_number,
                        'created_by' => $application->creator->name ?? 'Unknown',
                        'documents_sent' => $application->documents->count(),
                        'attempt' => $attempt,
                        'response_status' => $response->status(),
                        'response_body' => $response->body()
                    ]);
                    return true;
                } else {
                    Log::warning('Failed to send application to external API', [
                        'application_id' => $application->id,
                        'attempt' => $attempt,
                        'response_status' => $response->status(),
                        'response_body' => $response->body()
                    ]);
                    
                    // If this is the last attempt, log as error
                    if ($attempt === $maxRetries) {
                        Log::error('All attempts failed to send application to external API', [
                            'application_id' => $application->id,
                            'total_attempts' => $maxRetries
                        ]);
                        return false;
                    }
                }

            } catch (\Exception $e) {
                Log::warning('Exception occurred while sending application to external API', [
                    'application_id' => $application->id,
                    'attempt' => $attempt,
                    'error' => $e->getMessage()
                ]);
                
                // If this is the last attempt, log as error
                if ($attempt === $maxRetries) {
                    Log::error('All attempts failed due to exceptions', [
                        'application_id' => $application->id,
                        'total_attempts' => $maxRetries,
                        'last_error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    return false;
                }
            }

            // Wait before retry (except on last attempt)
            if ($attempt < $maxRetries) {
                sleep($retryDelay);
                $retryDelay *= 2; // Exponential backoff
            }
        }

        return false;
    }

    /**
     * Prepare lead data for external API
     */
    private function prepareLeadData(Lead $lead): array
    {
        // Load relationships
        $lead->load(['employmentHistory', 'creator']);
        
        return [
            'type' => 'enquiry',
            'ref_no' => $lead->ref_no,
            'name' => $lead->name,
            'dob' => $lead->dob ? $lead->dob->format('Y-m-d') : null,
            'father_name' => $lead->father_name,
            'phone' => $lead->phone,
            'alt_phone' => $lead->alt_phone,
            'email' => $lead->email,
            'city' => $lead->city,
            'address' => $lead->address,
            'preferred_country' => $lead->preferred_country,
            'preferred_city' => $lead->preferred_city,
            'preferred_college' => $lead->preferred_college,
            'preferred_course' => $lead->preferred_course,
            'travel_history' => $lead->travel_history,
            'any_refusal' => $lead->any_refusal,
            'spouse_name' => $lead->spouse_name,
            'any_gap' => $lead->any_gap,
            'score_type' => $lead->score_type,
            
            // English proficiency scores
            'ielts_listening' => $lead->ielts_listening,
            'ielts_reading' => $lead->ielts_reading,
            'ielts_writing' => $lead->ielts_writing,
            'ielts_speaking' => $lead->ielts_speaking,
            'ielts_overall' => $lead->ielts_overall,
            
            'pte_listening' => $lead->pte_listening,
            'pte_reading' => $lead->pte_reading,
            'pte_writing' => $lead->pte_writing,
            'pte_speaking' => $lead->pte_speaking,
            'pte_overall' => $lead->pte_overall,
            
            'duolingo_listening' => $lead->duolingo_listening,
            'duolingo_reading' => $lead->duolingo_reading,
            'duolingo_writing' => $lead->duolingo_writing,
            'duolingo_speaking' => $lead->duolingo_speaking,
            'duolingo_overall' => $lead->duolingo_overall,
            
            // Academic qualifications
            'last_qualification' => $lead->last_qualification,
            'twelfth_year' => $lead->twelfth_year,
            'twelfth_percentage' => $lead->twelfth_percentage,
            'twelfth_major' => $lead->twelfth_major,
            'tenth_year' => $lead->tenth_year,
            'tenth_percentage' => $lead->tenth_percentage,
            'tenth_major' => $lead->tenth_major,
            'diploma_year' => $lead->diploma_year,
            'diploma_percentage' => $lead->diploma_percentage,
            'diploma_major' => $lead->diploma_major,
            'graduation_year' => $lead->graduation_year,
            'graduation_percentage' => $lead->graduation_percentage,
            'graduation_major' => $lead->graduation_major,
            'post_graduation_year' => $lead->post_graduation_year,
            'post_graduation_percentage' => $lead->post_graduation_percentage,
            'post_graduation_major' => $lead->post_graduation_major,
            
            'previous_visa_application' => $lead->previous_visa_application,
            'source' => $lead->source,
            'reference_name' => $lead->reference_name,
            'remarks' => $lead->remarks,
            'status' => $lead->status,
            
            // Employment history
            'employment_history' => $lead->employmentHistory->map(function ($employment) {
                return [
                    'join_date' => $employment->join_date ? $employment->join_date->format('Y-m-d') : null,
                    'left_date' => $employment->left_date ? $employment->left_date->format('Y-m-d') : null,
                    'company_name' => $employment->company_name,
                    'job_position' => $employment->job_position,
                    'job_city' => $employment->job_city,
                ];
            })->toArray(),
            
            // User information (who created the lead)
            'created_by_user' => [
                'id' => $lead->creator->id,
                'name' => $lead->creator->name,
                'email' => $lead->creator->email,
                'company' => $lead->creator->company ?? null,
                'role' => $lead->creator->role ?? null,
                'department' => $lead->creator->department ?? null,
            ],
            
            // Metadata
            'created_at' => $lead->created_at->toISOString(),
            'updated_at' => $lead->updated_at->toISOString(),
        ];
    }

    /**
     * Prepare application data for external API
     */
    private function prepareApplicationData($application): array
    {
        // Load relationships including documents
        $application->load(['employmentHistory', 'creator', 'documents']);
        
        return [
            'type' => 'application',
            'application_number' => $application->application_number,
            'lead_ref_no' => $application->lead_ref_no,
            'name' => $application->name,
            'email' => $application->email,
            'phone' => $application->phone,
            'date_of_birth' => $application->date_of_birth ? $application->date_of_birth->format('Y-m-d') : null,
            'gender' => $application->gender,
            'nationality' => $application->nationality,
            'passport_number' => $application->passport_number,
            'passport_expiry' => $application->passport_expiry ? $application->passport_expiry->format('Y-m-d') : null,
            'marital_status' => $application->marital_status,
            'address' => $application->address,
            'city' => $application->city,
            'state' => $application->state,
            'postal_code' => $application->postal_code,
            'country' => $application->country,
            'emergency_contact_name' => $application->emergency_contact_name,
            'emergency_contact_phone' => $application->emergency_contact_phone,
            'emergency_contact_relationship' => $application->emergency_contact_relationship,
            
            // Study preferences
            'preferred_country' => $application->preferred_country,
            'preferred_city' => $application->preferred_city,
            'preferred_college' => $application->preferred_college,
            'course_level' => $application->course_level,
            'field_of_study' => $application->field_of_study,
            'intake_year' => $application->intake_year,
            'intake_month' => $application->intake_month,
            
            // English proficiency
            'english_proficiency' => $application->english_proficiency,
            'ielts_score' => $application->ielts_score,
            'toefl_score' => $application->toefl_score,
            'pte_score' => $application->pte_score,
            'other_english_test' => $application->other_english_test,
            
            // Educational qualifications
            'bachelor_degree' => $application->bachelor_degree,
            'bachelor_university' => $application->bachelor_university,
            'bachelor_percentage' => $application->bachelor_percentage,
            'bachelor_year' => $application->bachelor_year,
            'master_degree' => $application->master_degree,
            'master_university' => $application->master_university,
            'master_percentage' => $application->master_percentage,
            'master_year' => $application->master_year,
            'twelfth_board' => $application->twelfth_board,
            'twelfth_school' => $application->twelfth_school,
            'twelfth_percentage' => $application->twelfth_percentage,
            'twelfth_year' => $application->twelfth_year,
            'tenth_board' => $application->tenth_board,
            'tenth_school' => $application->tenth_school,
            'tenth_percentage' => $application->tenth_percentage,
            'tenth_year' => $application->tenth_year,
            
            // Background information
            'visa_refusal_history' => $application->visa_refusal_history,
            'refusal_details' => $application->refusal_details,
            'travel_history' => $application->travel_history,
            'financial_support' => $application->financial_support,
            'sponsor_name' => $application->sponsor_name,
            'sponsor_relationship' => $application->sponsor_relationship,
            'estimated_budget' => $application->estimated_budget,
            'remarks' => $application->remarks,
            'status' => $application->status,
            'visa_counselor' => $application->visa_counselor,
            
            // Employment history
            'employment_history' => $application->employmentHistory->map(function ($employment) {
                return [
                    'start_date' => $employment->start_date ? $employment->start_date->format('Y-m-d') : null,
                    'end_date' => $employment->end_date ? $employment->end_date->format('Y-m-d') : null,
                    'company_name' => $employment->company_name,
                    'position' => $employment->position,
                    'location' => $employment->location,
                    'description' => $employment->description,
                ];
            })->toArray(),
            
            // Documents information
            'documents' => $application->documents->map(function ($document) {
                return [
                    'document_name' => $document->document_name,
                    'document_type' => $document->document_type,
                    'original_filename' => $document->original_filename,
                    'file_size' => $document->file_size,
                    'mime_type' => $document->mime_type,
                    'is_mandatory' => $document->is_mandatory,
                    'status' => $document->status,
                    'file_url' => $document->file_url, // Full URL to access the document
                    'uploaded_at' => $document->created_at->toISOString(),
                ];
            })->toArray(),
            
            // Document summary for quick reference
            'documents_summary' => [
                'total_count' => $application->documents->count(),
                'mandatory_count' => $application->documents->where('is_mandatory', true)->count(),
                'optional_count' => $application->documents->where('is_mandatory', false)->count(),
                'document_types' => $application->documents->pluck('document_type')->unique()->values()->toArray(),
                'document_names' => $application->documents->pluck('document_name')->toArray(),
            ],
            
            // User information (who created the application)
            'created_by_user' => [
                'id' => $application->creator->id,
                'name' => $application->creator->name,
                'email' => $application->creator->email,
                'company' => $application->creator->company ?? null,
                'role' => $application->creator->role ?? null,
                'department' => $application->creator->department ?? null,
            ],
            
            // Metadata
            'created_at' => $application->created_at->toISOString(),
            'updated_at' => $application->updated_at->toISOString(),
        ];
    }

    /**
     * Test external API connection
     */
    public function testConnection(): array
    {
        // Check if external API is enabled
        if (!config('services.external_api.enabled')) {
            return [
                'success' => false,
                'message' => 'External API is disabled in configuration'
            ];
        }

        $apiUrl = config('services.external_api.enquiry_lead_url');
        
        if (!$apiUrl) {
            return [
                'success' => false,
                'message' => 'External API URL not configured'
            ];
        }

        try {
            // Send a simple test request
            $testData = [
                'type' => 'test',
                'timestamp' => now()->toISOString(),
                'message' => 'Connection test from B2B system'
            ];

            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($apiUrl, $testData);

            return [
                'success' => $response->successful(),
                'status_code' => $response->status(),
                'response_body' => $response->body(),
                'api_url' => $apiUrl
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection failed: ' . $e->getMessage(),
                'api_url' => $apiUrl
            ];
        }
    }

    /**
     * Get document checklist from external API
     */
    public function getDocumentChecklist(string $country, string $qualification = null): array
    {
        // Check if external API is enabled
        if (!config('services.external_api.enabled')) {
            Log::info('External API is disabled, returning empty checklist');
            return ['success' => false, 'message' => 'External API is disabled'];
        }

        $apiUrl = config('services.external_api.checklist_url');
        
        if (!$apiUrl) {
            Log::error('External API checklist URL not configured');
            return ['success' => false, 'message' => 'External API checklist URL not configured'];
        }
        
        try {
            $requestData = [
                'country' => $country
            ];
            
            if ($qualification) {
                $requestData['qualification'] = $qualification;
            }

            Log::info('Requesting document checklist from external API', [
                'api_url' => $apiUrl,
                'country' => $country,
                'qualification' => $qualification
            ]);

            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($apiUrl, $requestData);

            if ($response->successful()) {
                $responseData = $response->json();
                
                Log::info('Document checklist received from external API', [
                    'country' => $country,
                    'qualification' => $qualification,
                    'response_status' => $response->status(),
                    'documents_count' => is_array($responseData) ? count($responseData) : 0
                ]);
                
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            } else {
                Log::warning('Failed to get document checklist from external API', [
                    'country' => $country,
                    'qualification' => $qualification,
                    'response_status' => $response->status(),
                    'response_body' => $response->body()
                ]);
                
                return [
                    'success' => false,
                    'message' => 'Failed to fetch document checklist',
                    'status_code' => $response->status()
                ];
            }

        } catch (\Exception $e) {
            Log::error('Exception occurred while fetching document checklist', [
                'country' => $country,
                'qualification' => $qualification,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'message' => 'Error fetching document checklist: ' . $e->getMessage()
            ];
        }
    }
}
