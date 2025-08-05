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
                
                // Log the actual payload to JSON file for debugging
                $this->logLeadPayloadToFile($leadData, $lead);
                
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

                // Enhanced logging of request and response for leads
                $this->logLeadApiRequestResponse($leadData, $response, $apiUrl, $lead, $attempt);

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
    public function sendApplication($application, $additionalData = []): bool
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
                $applicationData = $this->prepareApplicationData($application, $additionalData);
                
                // Log the actual payload to JSON file for debugging
                $this->logPayloadToFile($applicationData, $application);
                
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

                // Enhanced logging of request and response
                $this->logApiRequestResponse($applicationData, $response, $apiUrl, $application, $attempt);

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
                'b2b_admin_id' => $lead->creator->id,
                'b2b_member_id' => ($lead->creator->role === 'member') ? $lead->creator->id : 0,
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
     * Prepare application data for external API (simplified version)
     */
    private function prepareApplicationData($application, $additionalData = []): array
    {
        // Load only necessary relationships
        $application->load(['employmentHistory', 'documents', 'courseOptions','creator']);
        
        // Log course options count for debugging
        \Log::info('Preparing application data for API', [
            'application_id' => $application->id,
            'application_number' => $application->application_number,
            'course_options_count' => $application->courseOptions->count(),
            'course_options_exist' => $application->courseOptions->isNotEmpty(),
        ]);
        
        $payloadData = [
            'type' => 'application',
            'application_number' => $application->application_number,
            'lead_ref_no' => $application->lead_ref_no,
            'name' => $application->name,
            'email' => $application->email,
            'phone' => $application->phone,
            'date_of_birth' => $application->date_of_birth ? $application->date_of_birth->format('Y-m-d') : null,
            'address' => $application->address,
            'city' => $application->city,
            'gender' => $additionalData['gender'] ?? '',
            'passport_no' => $additionalData['passport_no'] ?? '',
            'marital_status' => $additionalData['marital_status'] ?? '',
            'course_level' => $application->course_level,
            
            // Course options from course finder
            'admissions' => $application->courseOptions->map(function ($courseOption) {
                // Log each course option for debugging
                \Log::info('Processing course option for API payload', [
                    'application_id' => $courseOption->application_id,
                    'country' => $courseOption->country,
                    'city' => $courseOption->city,
                    'college' => $courseOption->college,
                    'course' => $courseOption->course,
                    'intake_year' => $courseOption->intake_year,
                    'intake_month' => $courseOption->intake_month,
                    'is_primary' => $courseOption->is_primary,
                    'priority_order' => $courseOption->priority_order,
                ]);
                
                return [
                    'country' => $courseOption->country ?? '',
                    'city' => $courseOption->city ?? '',
                    'college' => $courseOption->college ?? '',
                    'course' => $courseOption->course ?? '',
                    'intake_year' => $courseOption->intake_year ?? '',
                    'intake_month' => $courseOption->intake_month ?? '',
                ];
            })->toArray(),
            
            // English proficiency - Include detailed scores
            'english_proficiency' => $application->english_proficiency,
            'ielts_score' => $application->ielts_score,
            'ielts_overall' => $application->ielts_score, // Same as ielts_score for compatibility
            'ielts_listening' => $application->ielts_listening ?? null,
            'ielts_reading' => $application->ielts_reading ?? null,
            'ielts_writing' => $application->ielts_writing ?? null,
            'ielts_speaking' => $application->ielts_speaking ?? null,
            
            'toefl_score' => $application->toefl_score,
            'toefl_overall' => $application->toefl_score, // Same as toefl_score for compatibility
            'toefl_listening' => $application->toefl_listening ?? null,
            'toefl_reading' => $application->toefl_reading ?? null,
            'toefl_writing' => $application->toefl_writing ?? null,
            'toefl_speaking' => $application->toefl_speaking ?? null,
            
            'pte_score' => $application->pte_score,
            'pte_overall' => $application->pte_score, // Same as pte_score for compatibility
            'pte_listening' => $application->pte_listening ?? null,
            'pte_reading' => $application->pte_reading ?? null,
            'pte_writing' => $application->pte_writing ?? null,
            'pte_speaking' => $application->pte_speaking ?? null,
            
            'duolingo_overall' => $application->duolingo_overall ?? null,
            'duolingo_listening' => $application->duolingo_listening ?? null,
            'duolingo_reading' => $application->duolingo_reading ?? null,
            'duolingo_writing' => $application->duolingo_writing ?? null,
            'duolingo_speaking' => $application->duolingo_speaking ?? null,
            
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
                    'file_url' => $document->file_url,
                    'uploaded_at' => $document->created_at->toISOString(),
                ];
            })->toArray(),
            
            // Documents summary
            'documents_summary' => [
                'total_count' => $application->documents->count(),
                'mandatory_count' => $application->documents->where('is_mandatory', true)->count(),
                'optional_count' => $application->documents->where('is_mandatory', false)->count(),
                'document_types' => $application->documents->pluck('document_type')->unique()->values()->toArray(),
                'document_names' => $application->documents->pluck('document_name')->toArray(),
            ],
            
            // User information (who created the application)
            'created_by_user' => [
                'b2b_admin_id' => $application->creator->id,
                'b2b_member_id' => ($application->creator->role === 'member') ? $application->creator->id : 0,
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
        
        // Log the final payload structure with emphasis on admissions
        \Log::info('Final API payload prepared', [
            'application_id' => $application->id,
            'application_number' => $application->application_number,
            'total_payload_keys' => count($payloadData),
            'admissions_array_count' => count($payloadData['admissions'] ?? []),
            'admissions_array' => $payloadData['admissions'] ?? [],
            'has_course_options' => $application->courseOptions->isNotEmpty(),
        ]);
        
        return $payloadData;
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
     * Get countries from unified College Filter API
     */
    public function getCountries(): array
    {
        try {
            $apiUrl = config('services.external_api.college_filter_url', 'https://tarundemo.innerxcrm.com/b2bapi/adform');
            
            Log::info('Fetching countries from unified College Filter API', ['url' => $apiUrl]);

            $response = Http::timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($apiUrl, []);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['status']) && $data['status'] === 'success' && 
                    isset($data['level']) && $data['level'] === 'country' && 
                    isset($data['data'])) {
                    
                    // Transform the array of country names to the expected format
                    $countries = array_map(function($country) {
                        return ['country_name' => $country];
                    }, $data['data']);
                    
                    Log::info('Countries fetched successfully from unified API', [
                        'count' => count($countries),
                        'countries' => $data['data']
                    ]);
                    
                    return $countries;
                } else {
                    Log::warning('Unexpected response format from unified API, returning test data', [
                        'response' => $data
                    ]);
                    return $this->getTestCountries();
                }
            } else {
                Log::warning('Failed to fetch countries from unified API, returning test data', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return $this->getTestCountries();
            }
        } catch (\Exception $e) {
            Log::warning('Error fetching countries from unified API, returning test data', [
                'error' => $e->getMessage()
            ]);
            return $this->getTestCountries();
        }
    }

    /**
     * Get cities by country from unified College Filter API
     */
    public function getCitiesByCountry(string $country): array
    {
        try {
            $apiUrl = config('services.external_api.college_filter_url', 'https://tarundemo.innerxcrm.com/b2bapi/adform');
            
            Log::info('Fetching cities from unified College Filter API', [
                'url' => $apiUrl, 
                'country' => $country
            ]);

            $response = Http::timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($apiUrl, [
                    'country' => $country
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['status']) && $data['status'] === 'success' && 
                    isset($data['level']) && $data['level'] === 'city' && 
                    isset($data['data']) && isset($data['country']) && $data['country'] === $country) {
                    
                    // Transform the array of city names to the expected format
                    $cities = array_map(function($city) {
                        return ['city_name' => $city];
                    }, $data['data']);
                    
                    Log::info('Cities fetched successfully from unified API', [
                        'count' => count($cities),
                        'country' => $country,
                        'cities' => $data['data']
                    ]);
                    
                    return $cities;
                } else {
                    Log::warning('Unexpected response format from unified API, returning test data', [
                        'response' => $data,
                        'country' => $country
                    ]);
                    return $this->getTestCities($country);
                }
            } else {
                Log::warning('Failed to fetch cities from unified API, returning test data', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'country' => $country
                ]);
                return $this->getTestCities($country);
            }
        } catch (\Exception $e) {
            Log::warning('Error fetching cities from unified API, returning test data', [
                'error' => $e->getMessage(),
                'country' => $country
            ]);
            return $this->getTestCities($country);
        }
    }

    /**
     * Get colleges by country and city from unified College Filter API
     */
    public function getCollegesByCountryAndCity(string $country, string $city): array
    {
        try {
            $apiUrl = config('services.external_api.college_filter_url', 'https://tarundemo.innerxcrm.com/b2bapi/adform');
            
            Log::info('Fetching colleges from unified College Filter API', [
                'url' => $apiUrl, 
                'country' => $country, 
                'city' => $city
            ]);

            $response = Http::timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($apiUrl, [
                    'country' => $country,
                    'city' => $city
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['status']) && $data['status'] === 'success' && 
                    isset($data['level']) && $data['level'] === 'college' && 
                    isset($data['data']) && isset($data['country']) && $data['country'] === $country &&
                    isset($data['city']) && $data['city'] === $city) {
                    
                    // Transform the array of college names to the expected format
                    $colleges = array_map(function($college) {
                        return ['college_name' => $college];
                    }, $data['data']);
                    
                    Log::info('Colleges fetched successfully from unified API', [
                        'count' => count($colleges),
                        'country' => $country,
                        'city' => $city,
                        'colleges' => $data['data']
                    ]);
                    
                    return $colleges;
                } else {
                    Log::warning('Unexpected response format from unified API, returning test data', [
                        'response' => $data,
                        'country' => $country,
                        'city' => $city
                    ]);
                    return $this->getTestColleges($country, $city);
                }
            } else {
                Log::warning('Failed to fetch colleges from unified API, returning test data', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'country' => $country,
                    'city' => $city
                ]);
                return $this->getTestColleges($country, $city);
            }
        } catch (\Exception $e) {
            Log::warning('Error fetching colleges from unified API, returning test data', [
                'error' => $e->getMessage(),
                'country' => $country,
                'city' => $city
            ]);
            return $this->getTestColleges($country, $city);
        }
    }

    /**
     * Get courses by country, city and college from unified College Filter API
     */
    public function getCoursesByCountryCityCollege(string $country, string $city, string $college): array
    {
        try {
            $apiUrl = config('services.external_api.college_filter_url', 'https://tarundemo.innerxcrm.com/b2bapi/adform');
            
            Log::info('Fetching courses from unified College Filter API', [
                'url' => $apiUrl, 
                'country' => $country, 
                'city' => $city, 
                'college' => $college
            ]);

            $response = Http::timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($apiUrl, [
                    'country' => $country,
                    'city' => $city,
                    'college' => $college
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['status']) && $data['status'] === 'success' && 
                    isset($data['level']) && $data['level'] === 'course' && 
                    isset($data['data']) && isset($data['country']) && $data['country'] === $country &&
                    isset($data['city']) && $data['city'] === $city &&
                    isset($data['college']) && $data['college'] === $college) {
                    
                    // Transform the array of course names to the expected format
                    $courses = array_map(function($course) {
                        return ['course_name' => $course];
                    }, $data['data']);
                    
                    Log::info('Courses fetched successfully from unified API', [
                        'count' => count($courses),
                        'country' => $country,
                        'city' => $city,
                        'college' => $college,
                        'courses' => $data['data']
                    ]);
                    
                    return $courses;
                } else {
                    Log::warning('Unexpected response format from unified API, returning test data', [
                        'response' => $data,
                        'country' => $country,
                        'city' => $city,
                        'college' => $college
                    ]);
                    return $this->getTestCourses($country, $city, $college);
                }
            } else {
                Log::warning('Failed to fetch courses from unified API, returning test data', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'country' => $country,
                    'city' => $city,
                    'college' => $college
                ]);
                return $this->getTestCourses($country, $city, $college);
            }
        } catch (\Exception $e) {
            Log::warning('Error fetching courses from unified API, returning test data', [
                'error' => $e->getMessage(),
                'country' => $country,
                'city' => $city,
                'college' => $college
            ]);
            return $this->getTestCourses($country, $city, $college);
        }
    }

    /**
     * Unified College Filter API method
     * This method provides direct access to the College Filter API for flexible usage
     * 
     * @param array $filters - Array of filters (country, city, college)
     * @return array
     */
    public function getCollegeFilterData(array $filters = []): array
    {
        try {
            $apiUrl = config('services.external_api.college_filter_url', 'https://tarundemo.innerxcrm.com/b2bapi/adform');
            
            Log::info('Calling unified College Filter API', [
                'url' => $apiUrl,
                'filters' => $filters
            ]);

            $response = Http::timeout(30)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($apiUrl, $filters);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['status']) && $data['status'] === 'success') {
                    Log::info('College Filter API response received', [
                        'level' => $data['level'] ?? 'unknown',
                        'count' => $data['count'] ?? 0,
                        'filters_applied' => $filters
                    ]);
                    
                    return $data;
                } else {
                    Log::warning('Unexpected response from College Filter API', [
                        'response' => $data,
                        'filters' => $filters
                    ]);
                    
                    return [
                        'status' => 'error',
                        'message' => 'Unexpected API response format'
                    ];
                }
            } else {
                Log::error('College Filter API request failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'filters' => $filters
                ]);
                
                return [
                    'status' => 'error',
                    'message' => 'API request failed',
                    'http_status' => $response->status()
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception during College Filter API call', [
                'error' => $e->getMessage(),
                'filters' => $filters
            ]);
            
            return [
                'status' => 'error',
                'message' => 'API call exception: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check if phone number exists in external CRM
     */
    public function checkPhoneNumber(string $phoneNumber): array
    {
        try {
            $apiUrl = config('services.external_api.phone_check_url', 'https://tarundemo.innerxcrm.com/b2bapi/CheckNumber.php');
            
            Log::info('Checking phone number with external API', [
                'phone_number' => $phoneNumber,
                'api_url' => $apiUrl
            ]);

            $response = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($apiUrl, [
                    'phoneNumber' => $phoneNumber
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Phone number check response received', [
                    'phone_number' => $phoneNumber,
                    'response_status' => $response->status(),
                    'response_data' => $data
                ]);

                return [
                    'success' => true,
                    'status' => $data['status'] ?? 'unknown',
                    'message' => $data['message'] ?? 'Unknown response',
                    'exists' => ($data['status'] ?? '') === 'error' && 
                               str_contains($data['message'] ?? '', 'Already Exist')
                ];
            } else {
                Log::warning('Phone number check API returned error', [
                    'phone_number' => $phoneNumber,
                    'response_status' => $response->status(),
                    'response_body' => $response->body()
                ]);

                return [
                    'success' => false,
                    'status' => 'error',
                    'message' => 'Failed to check phone number',
                    'exists' => false
                ];
            }
        } catch (\Exception $e) {
            Log::error('Exception while checking phone number', [
                'phone_number' => $phoneNumber,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'status' => 'error',
                'message' => 'Service temporarily unavailable',
                'exists' => false
            ];
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

    /**
     * Log the payload to a JSON file for debugging purposes
     */
    private function logPayloadToFile(array $payload, $application): void
    {
        try {
            $logPath = base_path('api_payload_logs.json');
            
            // Create the log entry
            $logEntry = [
                'timestamp' => now()->toISOString(),
                'application_id' => $application->id,
                'application_number' => $application->application_number,
                'applicant_name' => $application->name,
                'created_by' => $application->creator->name ?? 'Unknown',
                'payload' => $payload
            ];
            
            // Read existing logs or create new structure
            $existingLogs = [];
            if (file_exists($logPath)) {
                $existingContent = file_get_contents($logPath);
                $existingLogs = json_decode($existingContent, true) ?? [];
            }
            
            // Initialize structure if needed
            if (!isset($existingLogs['payloads'])) {
                $existingLogs = [
                    'note' => 'This file contains actual API payloads sent when applications are created',
                    'last_updated' => now()->toISOString(),
                    'total_count' => 0,
                    'payloads' => []
                ];
            }
            
            // Add new payload
            $existingLogs['payloads'][] = $logEntry;
            $existingLogs['last_updated'] = now()->toISOString();
            $existingLogs['total_count'] = count($existingLogs['payloads']);
            
            // Keep only last 50 payloads to prevent file from getting too large
            if (count($existingLogs['payloads']) > 50) {
                $existingLogs['payloads'] = array_slice($existingLogs['payloads'], -50);
                $existingLogs['total_count'] = 50;
            }
            
            // Write back to file
            file_put_contents($logPath, json_encode($existingLogs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            
            Log::info('API payload logged to file', [
                'application_id' => $application->id,
                'log_file' => $logPath,
                'payload_size' => strlen(json_encode($payload))
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to log API payload to file', [
                'application_id' => $application->id ?? 'unknown',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Log the lead payload to a JSON file for debugging purposes
     */
    private function logLeadPayloadToFile(array $payload, $lead): void
    {
        try {
            $logPath = base_path('api_lead_payload_logs.json');
            
            // Create the log entry
            $logEntry = [
                'timestamp' => now()->toISOString(),
                'lead_id' => $lead->id,
                'lead_ref_no' => $lead->ref_no,
                'lead_name' => $lead->name,
                'created_by' => $lead->creator->name ?? 'Unknown',
                'payload' => $payload
            ];
            
            // Read existing logs or create new structure
            $existingLogs = [];
            if (file_exists($logPath)) {
                $existingContent = file_get_contents($logPath);
                $existingLogs = json_decode($existingContent, true) ?? [];
            }
            
            // Initialize structure if needed
            if (!isset($existingLogs['payloads'])) {
                $existingLogs = [
                    'note' => 'This file contains actual API payloads sent when leads are created',
                    'last_updated' => now()->toISOString(),
                    'total_count' => 0,
                    'payloads' => []
                ];
            }
            
            // Add new payload
            $existingLogs['payloads'][] = $logEntry;
            $existingLogs['last_updated'] = now()->toISOString();
            $existingLogs['total_count'] = count($existingLogs['payloads']);
            
            // Keep only last 50 payloads to prevent file from getting too large
            if (count($existingLogs['payloads']) > 50) {
                $existingLogs['payloads'] = array_slice($existingLogs['payloads'], -50);
                $existingLogs['total_count'] = 50;
            }
            
            // Write back to file
            file_put_contents($logPath, json_encode($existingLogs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            
            Log::info('Lead API payload logged to file', [
                'lead_id' => $lead->id,
                'log_file' => $logPath,
                'payload_size' => strlen(json_encode($payload))
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to log lead API payload to file', [
                'lead_id' => $lead->id ?? 'unknown',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Enhanced logging for API requests and responses
     */
    private function logApiRequestResponse(array $payload, $response, string $url, $application, int $attempt): void
    {
        try {
            // Create comprehensive log entry
            $logEntry = [
                'timestamp' => now()->toISOString(),
                'application_id' => $application->id,
                'application_number' => $application->application_number,
                'applicant_name' => $application->name,
                'applicant_email' => $application->email,
                'applicant_phone' => $application->phone,
                'created_by' => $application->creator->name ?? 'Unknown',
                'created_by_id' => $application->creator->id ?? null,
                'attempt_number' => $attempt,
                'api_endpoint' => $url,
                'request_payload' => $payload,
                'payload_size' => strlen(json_encode($payload)),
                'documents_count' => $application->documents->count(),
                'course_options_count' => $application->courseOptions->count(),
                'employment_history_count' => $application->employmentHistory->count(),
                'response' => [
                    'status_code' => $response->status(),
                    'headers' => $response->headers(),
                    'body' => $response->body(),
                    'successful' => $response->successful(),
                    'json' => $response->json() ?? null,
                ],
                'system_info' => [
                    'php_version' => PHP_VERSION,
                    'laravel_version' => app()->version(),
                    'user_agent' => request()->header('User-Agent'),
                    'ip_address' => request()->ip(),
                ]
            ];

            // Log to dedicated API logs file
            $this->writeToApiLogFile($logEntry);
            
            // Also log to Laravel logs with different levels based on response
            if ($response->successful()) {
                Log::info('API Request Successful', [
                    'application_id' => $application->id,
                    'url' => $url,
                    'status' => $response->status(),
                    'attempt' => $attempt
                ]);
            } else {
                Log::error('API Request Failed', [
                    'application_id' => $application->id,
                    'url' => $url,
                    'status' => $response->status(),
                    'response_body' => $response->body(),
                    'attempt' => $attempt
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to log API request/response', [
                'application_id' => $application->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Write API log entry to dedicated file
     */
    private function writeToApiLogFile(array $logEntry): void
    {
        try {
            $logPath = storage_path('logs/api_requests.json');
            
            // Ensure directory exists
            $logDir = dirname($logPath);
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            // Read existing logs
            $existingLogs = [];
            if (file_exists($logPath)) {
                $existingContent = file_get_contents($logPath);
                $existingLogs = json_decode($existingContent, true) ?? [];
            }

            // Initialize structure if needed
            if (!isset($existingLogs['requests'])) {
                $existingLogs = [
                    'note' => 'Comprehensive log of all API requests to Tarun Demo API when creating applications',
                    'created_at' => now()->toISOString(),
                    'last_updated' => now()->toISOString(),
                    'total_count' => 0,
                    'successful_requests' => 0,
                    'failed_requests' => 0,
                    'requests' => []
                ];
            }

            // Add new log entry
            $existingLogs['requests'][] = $logEntry;
            $existingLogs['last_updated'] = now()->toISOString();
            $existingLogs['total_count'] = count($existingLogs['requests']);
            
            // Update counters
            if ($logEntry['response']['successful']) {
                $existingLogs['successful_requests'] = ($existingLogs['successful_requests'] ?? 0) + 1;
            } else {
                $existingLogs['failed_requests'] = ($existingLogs['failed_requests'] ?? 0) + 1;
            }

            // Keep only last 100 requests to prevent file from getting too large
            if (count($existingLogs['requests']) > 100) {
                $existingLogs['requests'] = array_slice($existingLogs['requests'], -100);
                $existingLogs['total_count'] = 100;
            }

            // Write to file with proper formatting
            file_put_contents($logPath, json_encode($existingLogs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        } catch (\Exception $e) {
            Log::error('Failed to write API log file', [
                'error' => $e->getMessage(),
                'log_path' => $logPath ?? 'unknown'
            ]);
        }
    }

    /**
     * Enhanced logging for lead API requests and responses
     */
    private function logLeadApiRequestResponse(array $payload, $response, string $url, $lead, int $attempt): void
    {
        try {
            // Create comprehensive log entry for leads
            $logEntry = [
                'timestamp' => now()->toISOString(),
                'lead_id' => $lead->id,
                'lead_ref_no' => $lead->ref_no,
                'lead_name' => $lead->name,
                'lead_email' => $lead->email,
                'lead_phone' => $lead->phone,
                'created_by' => $lead->creator->name ?? 'Unknown',
                'created_by_id' => $lead->creator->id ?? null,
                'attempt_number' => $attempt,
                'api_endpoint' => $url,
                'request_payload' => $payload,
                'payload_size' => strlen(json_encode($payload)),
                'employment_history_count' => $lead->employmentHistory->count(),
                'response' => [
                    'status_code' => $response->status(),
                    'headers' => $response->headers(),
                    'body' => $response->body(),
                    'successful' => $response->successful(),
                    'json' => $response->json() ?? null,
                ],
                'system_info' => [
                    'php_version' => PHP_VERSION,
                    'laravel_version' => app()->version(),
                    'user_agent' => request()->header('User-Agent'),
                    'ip_address' => request()->ip(),
                ]
            ];

            // Log to dedicated lead API logs file
            $this->writeToLeadApiLogFile($logEntry);
            
            // Also log to Laravel logs with different levels based on response
            if ($response->successful()) {
                Log::info('Lead API Request Successful', [
                    'lead_id' => $lead->id,
                    'url' => $url,
                    'status' => $response->status(),
                    'attempt' => $attempt
                ]);
            } else {
                Log::error('Lead API Request Failed', [
                    'lead_id' => $lead->id,
                    'url' => $url,
                    'status' => $response->status(),
                    'response_body' => $response->body(),
                    'attempt' => $attempt
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to log lead API request/response', [
                'lead_id' => $lead->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Write lead API log entry to dedicated file
     */
    private function writeToLeadApiLogFile(array $logEntry): void
    {
        try {
            $logPath = storage_path('logs/lead_api_requests.json');
            
            // Ensure directory exists
            $logDir = dirname($logPath);
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }

            // Read existing logs
            $existingLogs = [];
            if (file_exists($logPath)) {
                $existingContent = file_get_contents($logPath);
                $existingLogs = json_decode($existingContent, true) ?? [];
            }

            // Initialize structure if needed
            if (!isset($existingLogs['requests'])) {
                $existingLogs = [
                    'note' => 'Comprehensive log of all lead API requests to Tarun Demo API when creating leads',
                    'created_at' => now()->toISOString(),
                    'last_updated' => now()->toISOString(),
                    'total_count' => 0,
                    'successful_requests' => 0,
                    'failed_requests' => 0,
                    'requests' => []
                ];
            }

            // Add new log entry
            $existingLogs['requests'][] = $logEntry;
            $existingLogs['last_updated'] = now()->toISOString();
            $existingLogs['total_count'] = count($existingLogs['requests']);
            
            // Update counters
            if ($logEntry['response']['successful']) {
                $existingLogs['successful_requests'] = ($existingLogs['successful_requests'] ?? 0) + 1;
            } else {
                $existingLogs['failed_requests'] = ($existingLogs['failed_requests'] ?? 0) + 1;
            }

            // Keep only last 100 requests to prevent file from getting too large
            if (count($existingLogs['requests']) > 100) {
                $existingLogs['requests'] = array_slice($existingLogs['requests'], -100);
                $existingLogs['total_count'] = 100;
            }

            // Write to file with proper formatting
            file_put_contents($logPath, json_encode($existingLogs, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        } catch (\Exception $e) {
            Log::error('Failed to write lead API log file', [
                'error' => $e->getMessage(),
                'log_path' => $logPath ?? 'unknown'
            ]);
        }
    }
}
