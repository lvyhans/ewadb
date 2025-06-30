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
        // Load relationships including documents, course options, and admin details
        $application->load(['employmentHistory', 'creator.roles', 'creator.admin.roles', 'documents', 'courseOptions']);
        
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
            
            // Course options from course finder
            'course_options' => $application->courseOptions->map(function ($courseOption) {
                return [
                    'country' => $courseOption->country,
                    'city' => $courseOption->city,
                    'college' => $courseOption->college,
                    'course' => $courseOption->course,
                    'course_type' => $courseOption->course_type,
                    'fees' => $courseOption->fees,
                    'duration' => $courseOption->duration,
                    'college_detail_id' => $courseOption->college_detail_id,
                    'is_primary' => $courseOption->is_primary,
                    'priority_order' => $courseOption->priority_order,
                ];
            })->toArray(),
            
            // Course options summary
            'course_options_summary' => [
                'total_count' => $application->courseOptions->count(),
                'primary_course' => $application->courseOptions->where('is_primary', true)->first() ? [
                    'course' => $application->courseOptions->where('is_primary', true)->first()->course,
                    'college' => $application->courseOptions->where('is_primary', true)->first()->college,
                    'country' => $application->courseOptions->where('is_primary', true)->first()->country,
                ] : null,
                'countries' => $application->courseOptions->pluck('country')->unique()->values()->toArray(),
                'colleges' => $application->courseOptions->pluck('college')->unique()->values()->toArray(),
            ],
            
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
                'phone' => $application->creator->phone ?? null,
                'city' => $application->creator->city ?? null,
                'state' => $application->creator->state ?? null,
                'zip' => $application->creator->zip ?? null,
                'company_name' => $application->creator->company_name ?? null,
                'company_registration_number' => $application->creator->company_registration_number ?? null,
                'gstin' => $application->creator->gstin ?? null,
                'pan_number' => $application->creator->pan_number ?? null,
                'company_address' => $application->creator->company_address ?? null,
                'company_city' => $application->creator->company_city ?? null,
                'company_state' => $application->creator->company_state ?? null,
                'company_pincode' => $application->creator->company_pincode ?? null,
                'company_phone' => $application->creator->company_phone ?? null,
                'company_email' => $application->creator->company_email ?? null,
                'approval_status' => $application->creator->approval_status ?? null,
                'approved_at' => $application->creator->approved_at ? $application->creator->approved_at->toISOString() : null,
                'admin_group_name' => $application->creator->admin_group_name ?? null,
                'admin_id' => $application->creator->admin_id ?? null, // Admin ID if user is a member
                'roles' => $application->creator->role_names ?? [],
                'primary_role' => $application->creator->role ?? 'member',
                'is_super_admin' => $application->creator->isSuperAdmin(),
                'is_regular_admin' => $application->creator->isRegularAdmin(),
                'user_type' => $application->creator->hasRole('admin') ? 'admin' : 'member',
            ],
            
            // Admin details (if user is a member under an admin)
            'admin_details' => $application->creator->admin ? [
                'id' => $application->creator->admin->id,
                'name' => $application->creator->admin->name,
                'email' => $application->creator->admin->email,
                'phone' => $application->creator->admin->phone ?? null,
                'company_name' => $application->creator->admin->company_name ?? null,
                'company_registration_number' => $application->creator->admin->company_registration_number ?? null,
                'gstin' => $application->creator->admin->gstin ?? null,
                'pan_number' => $application->creator->admin->pan_number ?? null,
                'company_address' => $application->creator->admin->company_address ?? null,
                'company_city' => $application->creator->admin->company_city ?? null,
                'company_state' => $application->creator->admin->company_state ?? null,
                'company_pincode' => $application->creator->admin->company_pincode ?? null,
                'company_phone' => $application->creator->admin->company_phone ?? null,
                'company_email' => $application->creator->admin->company_email ?? null,
                'approval_status' => $application->creator->admin->approval_status ?? null,
                'approved_at' => $application->creator->admin->approved_at ? $application->creator->admin->approved_at->toISOString() : null,
                'admin_group_name' => $application->creator->admin->admin_group_name ?? null,
                'is_super_admin' => $application->creator->admin->isSuperAdmin(),
                'total_members' => $application->creator->admin->members()->count(),
                'active_members' => $application->creator->admin->members()->where('approval_status', 'approved')->count(),
            ] : null,
            
            // Organization/Company hierarchy info
            'organization_info' => [
                'company_name' => $application->creator->admin ? 
                    $application->creator->admin->company_name : 
                    $application->creator->company_name,
                'company_registration_number' => $application->creator->admin ? 
                    $application->creator->admin->company_registration_number : 
                    $application->creator->company_registration_number,
                'gstin' => $application->creator->admin ? 
                    $application->creator->admin->gstin : 
                    $application->creator->gstin,
                'admin_group_name' => $application->creator->admin_group_name ?? 
                    ($application->creator->admin ? $application->creator->admin->admin_group_name : null),
                'hierarchy_level' => $application->creator->hasRole('admin') ? 'admin' : 'member',
                'is_independent_admin' => $application->creator->hasRole('admin') && !$application->creator->admin,
                'under_admin' => $application->creator->admin ? true : false,
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

    /**
     * Get countries from external API
     */
    public function getCountries(): array
    {
        try {
            $apiUrl = config('services.external_api.countries_url');
            
            if (!$apiUrl) {
                Log::warning('External API countries URL not configured, returning test data');
                return $this->getTestCountries();
            }

            Log::info('Fetching countries from external API', ['url' => $apiUrl]);

            $response = Http::timeout(30)->get($apiUrl);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Countries fetched successfully', ['count' => count($data)]);
                return $data;
            } else {
                Log::warning('Failed to fetch countries from external API, returning test data', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return $this->getTestCountries();
            }
        } catch (\Exception $e) {
            Log::warning('Error fetching countries from external API, returning test data', [
                'error' => $e->getMessage()
            ]);
            return $this->getTestCountries();
        }
    }

    /**
     * Get test countries data
     */
    private function getTestCountries(): array
    {
        return [
            ['country_name' => 'Canada'],
            ['country_name' => 'Australia'],
            ['country_name' => 'United Kingdom'],
            ['country_name' => 'United States'],
            ['country_name' => 'Germany'],
            ['country_name' => 'France'],
            ['country_name' => 'New Zealand'],
        ];
    }

    /**
     * Get cities by country from external API
     */
    public function getCitiesByCountry(string $country): array
    {
        try {
            $apiUrl = config('services.external_api.cities_url');
            
            if (!$apiUrl) {
                Log::warning('External API cities URL not configured, returning test data');
                return $this->getTestCities($country);
            }

            Log::info('Fetching cities from external API', ['url' => $apiUrl, 'country' => $country]);

            $response = Http::timeout(30)->get($apiUrl, ['country' => $country]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Cities fetched successfully', ['count' => count($data), 'country' => $country]);
                return $data;
            } else {
                Log::warning('Failed to fetch cities from external API, returning test data', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'country' => $country
                ]);
                return $this->getTestCities($country);
            }
        } catch (\Exception $e) {
            Log::warning('Error fetching cities from external API, returning test data', [
                'error' => $e->getMessage(),
                'country' => $country
            ]);
            return $this->getTestCities($country);
        }
    }

    /**
     * Get test cities data
     */
    private function getTestCities(string $country): array
    {
        $testCities = [
            'Canada' => [
                ['city_name' => 'Toronto'],
                ['city_name' => 'Vancouver'],
                ['city_name' => 'Montreal'],
                ['city_name' => 'Calgary'],
                ['city_name' => 'Ottawa'],
            ],
            'Australia' => [
                ['city_name' => 'Sydney'],
                ['city_name' => 'Melbourne'],
                ['city_name' => 'Brisbane'],
                ['city_name' => 'Perth'],
                ['city_name' => 'Adelaide'],
            ],
            'United Kingdom' => [
                ['city_name' => 'London'],
                ['city_name' => 'Manchester'],
                ['city_name' => 'Birmingham'],
                ['city_name' => 'Edinburgh'],
                ['city_name' => 'Glasgow'],
            ]
        ];

        return $testCities[$country] ?? [['city_name' => 'Default City']];
    }

    /**
     * Get colleges by country and city from external API
     */
    public function getCollegesByCountryAndCity(string $country, string $city): array
    {
        try {
            $apiUrl = config('services.external_api.colleges_url');
            
            if (!$apiUrl) {
                Log::warning('External API colleges URL not configured, returning test data');
                return $this->getTestColleges($country, $city);
            }

            Log::info('Fetching colleges from external API', ['url' => $apiUrl, 'country' => $country, 'city' => $city]);

            $response = Http::timeout(30)->get($apiUrl, [
                'country' => $country,
                'city' => $city
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Colleges fetched successfully', ['count' => count($data), 'country' => $country, 'city' => $city]);
                return $data;
            } else {
                Log::warning('Failed to fetch colleges from external API, returning test data', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'country' => $country,
                    'city' => $city
                ]);
                return $this->getTestColleges($country, $city);
            }
        } catch (\Exception $e) {
            Log::warning('Error fetching colleges from external API, returning test data', [
                'error' => $e->getMessage(),
                'country' => $country,
                'city' => $city
            ]);
            return $this->getTestColleges($country, $city);
        }
    }

    /**
     * Get test colleges data
     */
    private function getTestColleges(string $country, string $city): array
    {
        $testColleges = [
            'Canada' => [
                'Toronto' => [
                    ['college_name' => 'University of Toronto'],
                    ['college_name' => 'Ryerson University'],
                    ['college_name' => 'York University'],
                ],
                'Vancouver' => [
                    ['college_name' => 'University of British Columbia'],
                    ['college_name' => 'Simon Fraser University'],
                    ['college_name' => 'British Columbia Institute of Technology'],
                ]
            ],
            'Australia' => [
                'Sydney' => [
                    ['college_name' => 'University of Sydney'],
                    ['college_name' => 'University of New South Wales'],
                    ['college_name' => 'Macquarie University'],
                ],
                'Melbourne' => [
                    ['college_name' => 'University of Melbourne'],
                    ['college_name' => 'Monash University'],
                    ['college_name' => 'RMIT University'],
                ]
            ]
        ];

        return $testColleges[$country][$city] ?? [['college_name' => 'Default College']];
    }

    /**
     * Get courses by country, city and college from external API
     */
    public function getCoursesByCountryCityCollege(string $country, string $city, string $college): array
    {
        try {
            $apiUrl = config('services.external_api.courses_url');
            
            if (!$apiUrl) {
                Log::warning('External API courses URL not configured, returning test data');
                return $this->getTestCourses($country, $city, $college);
            }

            Log::info('Fetching courses from external API', [
                'url' => $apiUrl, 
                'country' => $country, 
                'city' => $city, 
                'college' => $college
            ]);

            $response = Http::timeout(30)->get($apiUrl, [
                'country' => $country,
                'city' => $city,
                'college' => $college
            ]);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('Courses fetched successfully', [
                    'count' => count($data), 
                    'country' => $country, 
                    'city' => $city, 
                    'college' => $college
                ]);
                return $data;
            } else {
                Log::warning('Failed to fetch courses from external API, returning test data', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'country' => $country,
                    'city' => $city,
                    'college' => $college
                ]);
                return $this->getTestCourses($country, $city, $college);
            }
        } catch (\Exception $e) {
            Log::warning('Error fetching courses from external API, returning test data', [
                'error' => $e->getMessage(),
                'country' => $country,
                'city' => $city,
                'college' => $college
            ]);
            return $this->getTestCourses($country, $city, $college);
        }
    }

    /**
     * Get test courses data
     */
    private function getTestCourses(string $country, string $city, string $college): array
    {
        $testCourses = [
            'Canada' => [
                'Toronto' => [
                    'University of Toronto' => [
                        ['course_name' => 'Computer Science'],
                        ['course_name' => 'Engineering'],
                        ['course_name' => 'Business Administration'],
                        ['course_name' => 'Medicine'],
                        ['course_name' => 'Law'],
                    ],
                    'Ryerson University' => [
                        ['course_name' => 'Information Technology Management'],
                        ['course_name' => 'Business Management'],
                        ['course_name' => 'Graphic Communications Management'],
                        ['course_name' => 'Architecture'],
                    ],
                    'York University' => [
                        ['course_name' => 'Liberal Arts & Professional Studies'],
                        ['course_name' => 'Science'],
                        ['course_name' => 'Fine Arts'],
                        ['course_name' => 'Education'],
                    ]
                ],
                'Vancouver' => [
                    'University of British Columbia' => [
                        ['course_name' => 'Applied Science'],
                        ['course_name' => 'Arts'],
                        ['course_name' => 'Commerce'],
                        ['course_name' => 'Science'],
                        ['course_name' => 'Forestry'],
                    ],
                    'Simon Fraser University' => [
                        ['course_name' => 'Computing Science'],
                        ['course_name' => 'Business Administration'],
                        ['course_name' => 'Engineering Science'],
                        ['course_name' => 'Communication, Art and Technology'],
                    ],
                    'British Columbia Institute of Technology' => [
                        ['course_name' => 'Technology'],
                        ['course_name' => 'Applied & Natural Sciences'],
                        ['course_name' => 'Business & Media'],
                        ['course_name' => 'Health Sciences'],
                    ]
                ]
            ],
            'Australia' => [
                'Sydney' => [
                    'University of Sydney' => [
                        ['course_name' => 'Medicine and Health'],
                        ['course_name' => 'Engineering and Information Technologies'],
                        ['course_name' => 'Business'],
                        ['course_name' => 'Arts and Social Sciences'],
                        ['course_name' => 'Science'],
                    ],
                    'University of New South Wales' => [
                        ['course_name' => 'Engineering'],
                        ['course_name' => 'Business School'],
                        ['course_name' => 'Medicine'],
                        ['course_name' => 'Science'],
                        ['course_name' => 'Built Environment'],
                    ],
                    'Macquarie University' => [
                        ['course_name' => 'Business and Economics'],
                        ['course_name' => 'Science and Engineering'],
                        ['course_name' => 'Arts'],
                        ['course_name' => 'Human Sciences'],
                    ]
                ],
                'Melbourne' => [
                    'University of Melbourne' => [
                        ['course_name' => 'Architecture, Building and Planning'],
                        ['course_name' => 'Arts'],
                        ['course_name' => 'Business and Economics'],
                        ['course_name' => 'Education'],
                        ['course_name' => 'Engineering'],
                        ['course_name' => 'Medicine, Dentistry and Health Sciences'],
                    ],
                    'Monash University' => [
                        ['course_name' => 'Art, Design & Architecture'],
                        ['course_name' => 'Arts'],
                        ['course_name' => 'Business and Economics'],
                        ['course_name' => 'Education'],
                        ['course_name' => 'Engineering'],
                        ['course_name' => 'Information Technology'],
                    ],
                    'RMIT University' => [
                        ['course_name' => 'Business'],
                        ['course_name' => 'Design and Social Context'],
                        ['course_name' => 'Science, Engineering and Health'],
                        ['course_name' => 'Media and Communication'],
                    ]
                ]
            ],
            'United Kingdom' => [
                'London' => [
                    'University College London' => [
                        ['course_name' => 'Arts & Humanities'],
                        ['course_name' => 'Brain Sciences'],
                        ['course_name' => 'Engineering Sciences'],
                        ['course_name' => 'Laws'],
                        ['course_name' => 'Life Sciences'],
                        ['course_name' => 'Mathematical & Physical Sciences'],
                        ['course_name' => 'Medical Sciences'],
                    ],
                    'King\'s College London' => [
                        ['course_name' => 'Arts & Humanities'],
                        ['course_name' => 'Business'],
                        ['course_name' => 'Dentistry, Oral & Craniofacial Sciences'],
                        ['course_name' => 'Life Sciences & Medicine'],
                        ['course_name' => 'Natural & Mathematical Sciences'],
                        ['course_name' => 'Nursing, Midwifery & Palliative Care'],
                        ['course_name' => 'Psychiatry, Psychology & Neuroscience'],
                        ['course_name' => 'Social Science & Public Policy'],
                    ],
                    'Imperial College London' => [
                        ['course_name' => 'Engineering'],
                        ['course_name' => 'Medicine'],
                        ['course_name' => 'Natural Sciences'],
                        ['course_name' => 'Business School'],
                    ]
                ]
            ]
        ];

        return $testCourses[$country][$city][$college] ?? [['course_name' => 'Default Course']];
    }
}
