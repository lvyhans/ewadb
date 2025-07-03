<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\ApplicationEmployment;
use App\Models\Lead;
use App\Models\User;
use App\Services\ExternalApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function index()
    {
        $applications = Application::with(['creator', 'assignedUser'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('applications.index', compact('applications'));
    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('applications.create', compact('users'));
    }

    public function store(Request $request)
    {
        // Debug: Log the incoming request data
        \Log::info('=== APPLICATION STORE START ===');
        \Log::info('Request method: ' . $request->method());
        \Log::info('Has documents: ' . ($request->has('documents') ? 'YES' : 'NO'));
        \Log::info('Request data keys: ' . implode(', ', array_keys($request->all())));
        \Log::info('Files: ' . json_encode($request->allFiles()));
        
        if ($request->has('documents')) {
            \Log::info('Documents structure:', $request->documents);
        }
        
        try {
            // Basic validation first - exclude documents for now
            $basicRules = [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'source' => 'required|string',
                'remarks' => 'required|string',
            ];
            
            \Log::info('Validating basic fields...');
            $request->validate($basicRules);
            \Log::info('Basic validation passed');
            
            // Only add document validation if documents are present
            if ($request->has('documents') && is_array($request->documents)) {
                \Log::info('Adding document validation rules...');
                
                $documentRules = [
                    'documents' => 'array',
                    'documents.*.file' => 'sometimes|file|max:20480', // 20MB max
                    'documents.*.document_name' => 'sometimes|string|max:255',
                    'documents.*.is_mandatory' => 'sometimes|in:0,1',
                ];
                
                $request->validate($documentRules);
                \Log::info('Document validation passed');
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', $e->errors());
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            \Log::error('Unexpected validation error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()->with('error', 'Validation error: ' . $e->getMessage());
        }

        try {
            \Log::info('Starting database transaction...');
            DB::beginTransaction();

            // Map form fields to database fields
            $mappedData = [
                'application_number' => Application::generateApplicationNumber(),
                'created_by' => Auth::id(),
                'status' => 'pending',
                
                // Personal Details
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_of_birth' => $request->dob,
                'address' => $request->address,
                'city' => $request->f_city,
                'lead_ref_no' => $request->ref_no,
                
                // Country & College preferences
                'preferred_country' => $request->country,
                'preferred_city' => $request->city,
                'preferred_college' => $request->college,
                'field_of_study' => $request->course,
                
                // Background Information
                'travel_history' => !empty($request->travel_history) ? 1 : 0,
                'visa_refusal_history' => !empty($request->any_refusal) ? 1 : 0,
                'refusal_details' => $request->any_refusal,
                
                // Qualifications - Map form values to valid ENUM values
                'course_level' => $this->mapCourseLevel($request->last_qual),
                
                // 10th Grade
                'tenth_percentage' => $request->tenmarks,
                'tenth_year' => $request->tenyear,
                
                // 12th Grade  
                'twelfth_percentage' => $request->twelvemarks,
                'twelfth_year' => $request->twelveyear,
                
                // Graduation
                'bachelor_percentage' => $request->bmarks,
                'bachelor_year' => $request->byear,
                
                // Post Graduation
                'master_percentage' => $request->pgr_per,
                'master_year' => $request->pgr_year,
                
                // English Proficiency - Map form values to valid ENUM values
                'english_proficiency' => $this->mapEnglishProficiency($request->score_type),
                'ielts_score' => $request->ielts_overall,
                'pte_score' => $request->pte_overall,
                
                // Additional Information
                'remarks' => $request->remarks,
            ];
            
            // Remove null values
            $mappedData = array_filter($mappedData, function($value) {
                return $value !== null && $value !== '';
            });
            
            \Log::info('Mapped Application Data:', $mappedData);

            $application = Application::create($mappedData);
            
            \Log::info('Application Created Successfully:', ['id' => $application->id, 'application_number' => $application->application_number]);

            // Create employment history
            if ($request->has('employementhistory') && is_array($request->employementhistory)) {
                \Log::info('Processing Employment History:', $request->employementhistory);
                
                foreach ($request->employementhistory as $index => $employment) {
                    if (!empty($employment['company_name']) || !empty($employment['job_position'])) {
                        $employmentData = [
                            'application_id' => $application->id,
                            'company_name' => $employment['company_name'] ?? '',
                            'position' => $employment['job_position'] ?? '',
                            'start_date' => $employment['join_date'] ?? null,
                            'end_date' => $employment['left_date'] ?? null,
                            'location' => $employment['job_city'] ?? '',
                            'description' => $employment['description'] ?? '',
                        ];
                        
                        \Log::info('Creating Employment Record #' . $index . ':', $employmentData);
                        $empRecord = ApplicationEmployment::create($employmentData);
                        \Log::info('Employment Record Created:', ['id' => $empRecord->id]);
                    }
                }
            }

            // Handle document uploads (non-blocking - don't fail transaction if documents fail)
            if ($request->has('documents') && is_array($request->documents)) {
                \Log::info('Processing Document Uploads:', ['count' => count($request->documents)]);
                
                foreach ($request->documents as $index => $documentData) {
                    try {
                        \Log::info("Processing document upload $index:", [
                            'has_file' => isset($documentData['file']),
                            'file_valid' => isset($documentData['file']) && is_object($documentData['file']) && $documentData['file']->isValid(),
                            'document_data_keys' => is_array($documentData) ? array_keys($documentData) : 'not_array'
                        ]);
                        
                        if (isset($documentData['file']) && is_object($documentData['file']) && $documentData['file']->isValid()) {
                            $file = $documentData['file'];
                            
                            // Generate unique filename
                            $filename = time() . '_' . $index . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                            
                            // Store file in the application_documents directory
                            $filePath = $file->storeAs('application_documents', $filename, 'public');
                            
                            // Create document record
                            $document = ApplicationDocument::create([
                                'application_id' => $application->id,
                                'document_name' => $documentData['document_name'] ?? 'Unknown Document',
                                'document_type' => $documentData['document_type'] ?? 'general',
                                'is_mandatory' => isset($documentData['is_mandatory']) && $documentData['is_mandatory'] == '1',
                                'file_path' => $filePath,
                                'original_filename' => $file->getClientOriginalName(),
                                'file_size' => $file->getSize(),
                                'mime_type' => $file->getMimeType(),
                                'status' => 'uploaded',
                            ]);
                            
                            \Log::info('Document uploaded successfully', [
                                'document_id' => $document->id,
                                'filename' => $filename,
                                'size' => $file->getSize()
                            ]);
                            
                        } else {
                            \Log::info("Skipping document $index - no valid file provided");
                        }
                    } catch (\Exception $e) {
                        \Log::error('Failed to process document - continuing with application creation', [
                            'index' => $index,
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        // Continue processing other documents
                    }
                }
            } else {
                \Log::info('No documents to process or documents not in expected format');
            }

            DB::commit();
            
            \Log::info('Application Store Transaction Committed Successfully');

            // Send application data to external API (non-blocking)
            try {
                $externalApiService = new ExternalApiService();
                $externalApiService->sendApplication($application);
            } catch (\Exception $e) {
                // Log the error but don't fail the application creation
                \Log::error('Failed to send application to external API', [
                    'application_id' => $application->id,
                    'error' => $e->getMessage()
                ]);
            }

            return redirect()->route('applications.show', $application->id)
                ->with('success', 'Application created successfully! Application Number: ' . $application->application_number);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Application Store Error:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Error creating application: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $application = Application::with(['creator', 'assignedUser', 'employmentHistory', 'documents'])->findOrFail($id);
        return view('applications.show', compact('application'));
    }

    // Edit and Update methods removed - Applications are read-only after creation

    public function destroy($id)
    {
        try {
            $application = Application::findOrFail($id);
            $application->delete();

            return redirect()->route('applications.index')
                ->with('success', 'Application deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting application: ' . $e->getMessage());
        }
    }

    // API method to get lead data for auto-fill
    public function getLeadData(Request $request)
    {
        // Handle both GET and POST requests and different parameter names
        $leadRefNo = $request->input('ref_no') ?? $request->input('lead_ref_no');
        
        if (empty($leadRefNo)) {
            return response()->json(['success' => false, 'message' => 'Lead reference number is required']);
        }

        $lead = Lead::with('employmentHistory')->where('ref_no', $leadRefNo)->first();

        if (!$lead) {
            return response()->json(['success' => false, 'message' => 'Lead not found with this reference number']);
        }

        // Map lead data to application form fields - matching the exact field names in the form
        $leadData = [
            // Personal Details
            'ref_no' => $lead->ref_no,
            'name' => $lead->name,
            'dob' => $lead->dob,
            'father' => $lead->father_name,  // lead has 'father_name', form expects 'father'
            'phone' => $lead->phone,
            'rphone' => $lead->alt_phone,    // lead has 'alt_phone', form expects 'rphone'
            'email' => $lead->email,
            'f_city' => $lead->city,         // lead has 'city', form expects 'f_city'
            'address' => $lead->address,
            
            // Country & College
            'country' => $lead->preferred_country,  // lead has 'preferred_country', form expects 'country'
            'city' => $lead->preferred_city,        // lead has 'preferred_city', form expects 'city'
            'college' => $lead->preferred_college,  // lead has 'preferred_college', form expects 'college'
            'course' => $lead->preferred_course,    // lead has 'preferred_course', form expects 'course'
            
            // Background Information
            'travel_history' => $lead->travel_history,
            'any_refusal' => $lead->any_refusal,
            'spouse_name' => $lead->spouse_name,
            'any_gap' => $lead->any_gap,
            
            // Educational Qualifications
            'last_qual' => $lead->last_qualification,     // lead has 'last_qualification', form expects 'last_qual'
            'already_applied' => $lead->previous_visa_application,  // lead has 'previous_visa_application', form expects 'already_applied'
            
            // These fields might not exist in the lead model yet - keeping them for future compatibility
            'tenyear' => $lead->tenth_year ?? null,
            'tenmarks' => $lead->tenth_percentage ?? null,
            'ten_major' => $lead->tenth_major ?? null,
            
            'twelveyear' => $lead->twelfth_year ?? null,
            'twelvemarks' => $lead->twelfth_percentage ?? null,
            'twelvemajor' => $lead->twelfth_major ?? null,
            
            'ugdiplomayear' => $lead->diploma_year ?? null,
            'ugdiplomamarks' => $lead->diploma_percentage ?? null,
            'ugdiplomamajor' => $lead->diploma_major ?? null,
            
            'byear' => $lead->graduation_year ?? null,
            'bmarks' => $lead->graduation_percentage ?? null,
            'gra_major' => $lead->graduation_major ?? null,
            
            'pgr_year' => $lead->post_graduation_year ?? null,
            'pgr_per' => $lead->post_graduation_percentage ?? null,
            'pgr_major' => $lead->post_graduation_major ?? null,
            
            // English Proficiency
            'score_type' => $lead->score_type,
            'ielts_overall' => $lead->ielts_overall,
            'ielts_listening' => $lead->ielts_listening,
            'ielts_reading' => $lead->ielts_reading,
            'ielts_writing' => $lead->ielts_writing,
            'ielts_speaking' => $lead->ielts_speaking,
            'pte_overall' => $lead->pte_overall,
            'pte_listening' => $lead->pte_listening,
            'pte_reading' => $lead->pte_reading,
            'pte_writing' => $lead->pte_writing,
            'pte_speaking' => $lead->pte_speaking,
            'duolingo_overall' => $lead->duolingo_overall,
            'duolingo_listening' => $lead->duolingo_listening,
            'duolingo_reading' => $lead->duolingo_reading,
            'duolingo_writing' => $lead->duolingo_writing,
            'duolingo_speaking' => $lead->duolingo_speaking,
            
            // Source Information
            'source' => $lead->source,
            'r_name' => $lead->reference_name ?? null,    // might not exist in lead model
            
            // Additional Information
            'remarks' => $lead->remarks,
        ];
        
        // Get employment history with proper field mapping and date formatting
        $employmentHistory = [];
        if ($lead->employmentHistory) {
            $employmentHistory = $lead->employmentHistory->map(function ($employment) {
                return [
                    'join_date' => $employment->join_date ? $employment->join_date->format('Y-m-d') : '',
                    'left_date' => $employment->left_date ? $employment->left_date->format('Y-m-d') : '',
                    'company_name' => $employment->company_name,
                    'job_position' => $employment->job_position,
                    'job_city' => $employment->job_city,
                ];
            })->toArray();
        }

        return response()->json([
            'success' => true,
            'lead' => $leadData,
            'employment_history' => $employmentHistory
        ]);
    }
    
    /**
     * Map form course level values to database ENUM values
     */
    private function mapCourseLevel($formValue)
    {
        $mapping = [
            '10th' => 'certificate',
            '12th' => 'diploma',
            'diploma' => 'diploma',
            'bachelor' => 'bachelor',
            'graduation' => 'bachelor',
            'master' => 'master',
            'post graduation' => 'master',
            'phd' => 'phd',
            'doctorate' => 'phd',
        ];
        
        $normalizedValue = strtolower(trim($formValue ?? ''));
        return $mapping[$normalizedValue] ?? 'bachelor'; // default to bachelor if not found
    }
    
    /**
     * Map form english proficiency values to database ENUM values
     */
    private function mapEnglishProficiency($formValue)
    {
        $mapping = [
            'ielts' => 'ielts',
            'toefl' => 'toefl',
            'pte' => 'pte',
            'duolingo' => 'other',
            'cambridge' => 'other',
            'other' => 'other',
            'none' => 'none',
        ];
        
        $normalizedValue = strtolower(trim($formValue ?? ''));
        return $mapping[$normalizedValue] ?? 'other'; // default to other if not found
    }
    
    /**
     * Test external API connection for applications
     */
    public function testExternalApiApplication()
    {
        $externalApiService = new ExternalApiService();
        
        // Get the first application for testing, or create a sample data structure
        $application = Application::with(['employmentHistory', 'creator'])->first();
        
        if (!$application) {
            return response()->json([
                'success' => false,
                'message' => 'No applications found to test with'
            ]);
        }
        
        try {
            $result = $externalApiService->sendApplication($application);
            
            return response()->json([
                'success' => $result,
                'message' => $result ? 'Application data sent successfully' : 'Failed to send application data',
                'application_id' => $application->id,
                'application_number' => $application->application_number
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error testing application API: ' . $e->getMessage(),
                'application_id' => $application->id
            ]);
        }
    }

    /**
     * Test external API application integration
     */
    public function testExternalApiIntegration()
    {
        $externalApiService = new ExternalApiService();
        
        // Test the checklist API
        $checklistResult = $externalApiService->getDocumentChecklist('Canada', 'Bachelor');
        
        return response()->json([
            'message' => 'External API Test Results',
            'checklist_test' => $checklistResult,
            'api_config' => [
                'enabled' => config('services.external_api.enabled'),
                'enquiry_url' => config('services.external_api.enquiry_lead_url'),
                'checklist_url' => config('services.external_api.checklist_url')
            ]
        ]);
    }

    /**
     * Get document checklist for a country and qualification
     */
    public function getDocumentChecklist(Request $request)
    {
        $request->validate([
            'country' => 'required|string',
            'qualification' => 'nullable|string'
        ]);

        $externalApiService = new ExternalApiService();
        $result = $externalApiService->getDocumentChecklist(
            $request->country,
            $request->qualification
        );

        return response()->json($result);
    }

    /**
     * Test application creation without documents
     */
    public function testApplicationCreation()
    {
        try {
            \Log::info('Testing application creation...');
            
            // Test basic application creation
            $testData = [
                'application_number' => Application::generateApplicationNumber(),
                'created_by' => 1,
                'status' => 'pending',
                'name' => 'Test User',
                'phone' => '1234567890',
                'email' => 'test@example.com',
                'remarks' => 'Test application'
            ];
            
            $application = Application::create($testData);
            
            return response()->json([
                'success' => true,
                'message' => 'Test application created successfully',
                'application_id' => $application->id,
                'application_number' => $application->application_number
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating test application: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    /**
     * Debug application submission
     */
    public function debugApplicationSubmission(Request $request)
    {
        \Log::info('=== DEBUG APPLICATION SUBMISSION ===');
        \Log::info('Request method: ' . $request->method());
        \Log::info('Content type: ' . $request->header('Content-Type'));
        \Log::info('Has files: ' . ($request->hasFile('documents') ? 'yes' : 'no'));
        \Log::info('All request data keys: ' . implode(', ', array_keys($request->all())));
        
        if ($request->has('documents')) {
            \Log::info('Documents data structure:');
            foreach ($request->documents as $index => $doc) {
                \Log::info("Document $index:", [
                    'keys' => is_array($doc) ? array_keys($doc) : 'not_array',
                    'has_file' => isset($doc['file']),
                    'file_type' => isset($doc['file']) ? get_class($doc['file']) : 'none',
                    'document_name' => $doc['document_name'] ?? 'none',
                    'is_mandatory' => $doc['is_mandatory'] ?? 'none'
                ]);
            }
        }
        
        return response()->json([
            'status' => 'debug_complete',
            'message' => 'Check logs for detailed information'
        ]);
    }
    
    /**
     * Test application creation without documents
     */
    public function testSimpleApplicationCreation(Request $request)
    {
        \Log::info('=== TESTING SIMPLE APPLICATION CREATION ===');
        
        try {
            // Only basic validation
            $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'source' => 'required|string',
                'remarks' => 'required|string',
            ]);
            
            \Log::info('Basic validation passed');
            
            DB::beginTransaction();
            
            // Create minimal application
            $application = Application::create([
                'application_number' => Application::generateApplicationNumber(),
                'created_by' => Auth::id(),
                'status' => 'pending',
                'name' => $request->name,
                'phone' => $request->phone,
                'remarks' => $request->remarks,
            ]);
            
            \Log::info('Application created:', ['id' => $application->id]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Simple application created successfully',
                'application_id' => $application->id
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Simple application creation failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create application: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Test what application data would be sent to external API
     */
    public function testApplicationApiPayload($id)
    {
        try {
            $application = Application::with(['creator', 'assignedUser', 'employmentHistory', 'documents'])->findOrFail($id);
            
            $externalApiService = new ExternalApiService();
            
            // Use reflection to access the private prepareApplicationData method
            $reflection = new \ReflectionClass($externalApiService);
            $method = $reflection->getMethod('prepareApplicationData');
            $method->setAccessible(true);
            
            $payload = $method->invoke($externalApiService, $application);
            
            return response()->json([
                'application_id' => $application->id,
                'application_number' => $application->application_number,
                'payload' => $payload,
                'documents_included' => isset($payload['documents']),
                'documents_count' => isset($payload['documents']) ? count($payload['documents']) : 0,
                'documents_summary' => $payload['documents_summary'] ?? null
            ], 200, [], JSON_PRETTY_PRINT);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting application API payload: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    // Dropdown API methods for application form
    public function getCountries(Request $request)
    {
        try {
            $externalApiService = new ExternalApiService();
            $countries = $externalApiService->getCountries();
            
            // Deduplicate and sort
            $uniqueCountries = collect($countries)
                ->unique('country_name')
                ->sortBy('country_name')
                ->values()
                ->toArray();
            
            return response()->json($uniqueCountries);
        } catch (\Exception $e) {
            \Log::error('Error fetching countries: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch countries',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getCities(Request $request)
    {
        try {
            $country = $request->get('country');
            
            if (!$country) {
                return response()->json([
                    'error' => 'Country parameter is required'
                ], 400);
            }

            $externalApiService = new ExternalApiService();
            $cities = $externalApiService->getCitiesByCountry($country);
            
            // Deduplicate and sort
            $uniqueCities = collect($cities)
                ->unique('city_name')
                ->sortBy('city_name')
                ->values()
                ->toArray();
            
            return response()->json($uniqueCities);
        } catch (\Exception $e) {
            \Log::error('Error fetching cities: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch cities',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getColleges(Request $request)
    {
        try {
            $country = $request->get('country');
            $city = $request->get('city');
            
            if (!$country || !$city) {
                return response()->json([
                    'error' => 'Country and city parameters are required'
                ], 400);
            }

            $externalApiService = new ExternalApiService();
            $colleges = $externalApiService->getCollegesByCountryAndCity($country, $city);
            
            // Deduplicate and sort
            $uniqueColleges = collect($colleges)
                ->unique('college_name')
                ->sortBy('college_name')
                ->values()
                ->toArray();
            
            return response()->json($uniqueColleges);
        } catch (\Exception $e) {
            \Log::error('Error fetching colleges: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch colleges',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getCourses(Request $request)
    {
        try {
            $country = $request->get('country');
            $city = $request->get('city');
            $college = $request->get('college');
            
            if (!$country || !$city || !$college) {
                return response()->json([
                    'error' => 'Country, city, and college parameters are required'
                ], 400);
            }

            $externalApiService = new ExternalApiService();
            $courses = $externalApiService->getCoursesByCountryCityCollege($country, $city, $college);
            
            // Deduplicate and sort
            $uniqueCourses = collect($courses)
                ->unique('course_name')
                ->sortBy('course_name')
                ->values()
                ->toArray();
            
            return response()->json($uniqueCourses);
        } catch (\Exception $e) {
            \Log::error('Error fetching courses: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to fetch courses',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
