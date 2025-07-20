<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ApplicationCourseOption;
use App\Models\ApplicationDocument;
use App\Models\ApplicationEmployment;
use App\Models\Lead;
use App\Models\User;
use App\Services\ExternalApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get pagination parameters
            $page = $request->get('page', 1);
            $entries = $request->get('entries', 20); // Default to 20 entries
            
            // Validate entries parameter
            $allowedEntries = [5, 20, 100, 250, 500];
            if (!in_array($entries, $allowedEntries)) {
                $entries = 20; // Default fallback
            }
            
            $limit = $entries;
            
            // API endpoint
            $apiUrl = 'https://tarundemo.innerxcrm.com/b2bapi/application';
            
            // Request payload - dynamically set based on user role
            $payload = $this->buildApiPayload([
                'limit' => $limit,
                'page' => $page
            ]);
            
            // Make API call
            $response = $this->makeApiCall($apiUrl, $payload);
            
            if ($response['status'] === 'success') {
                $apiData = $response;
                $applications = collect($apiData['data']);
                
                // Create pagination-like object for view compatibility
                $paginationData = (object) [
                    'data' => $applications,
                    'total' => $apiData['total_count'],
                    'per_page' => $apiData['limit'],
                    'current_page' => $apiData['page'],
                    'last_page' => $apiData['total_pages'],
                    'from' => (($apiData['page'] - 1) * $apiData['limit']) + 1,
                    'to' => min($apiData['page'] * $apiData['limit'], $apiData['total_count'])
                ];
                
                return view('applications.index', compact('applications', 'paginationData', 'apiData', 'entries'));
            } else {
                throw new \Exception('API request failed');
            }
            
        } catch (\Exception $e) {
            Log::error('Error fetching applications from API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'url' => $apiUrl,
                'payload' => $payload
            ]);
            
            // Fallback to local database if API fails
            $applications = Application::with(['creator', 'assignedUser', 'documents'])
                ->orderBy('created_at', 'desc')
                ->paginate($entries);
                
            $paginationData = null;
            $apiData = null;
            
            return view('applications.index', compact('applications', 'paginationData', 'apiData', 'entries'))
                ->with('warning', 'API temporarily unavailable. Showing local data instead.');
        }
    }
    
    /**
     * Build API payload with proper user credentials based on role
     */
    private function buildApiPayload($additionalParams = [])
    {
        $user = Auth::user();
        
        if (!$user) {
            throw new \Exception('User not authenticated');
        }
        
        $payload = [];
        
        if ($user->hasRole('admin')) {
            // If user is admin, send their ID as b2b_admin_id
            $payload['b2b_admin_id'] = $user->id;
            
            Log::info('Building API payload for admin user', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'b2b_admin_id' => $user->id
            ]);
        } else {
            // If user is member, send both member ID and admin ID
            $payload['b2b_member_id'] = $user->id;
            
            // Get the admin ID for this member
            if ($user->admin_id) {
                $payload['b2b_admin_id'] = $user->admin_id;
            } else {
                // If no admin_id is set, try to find the admin relationship
                if ($user->admin) {
                    $payload['b2b_admin_id'] = $user->admin->id;
                } else {
                    throw new \Exception('Member user does not have an associated admin');
                }
            }
            
            Log::info('Building API payload for member user', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'b2b_member_id' => $user->id,
                'b2b_admin_id' => $payload['b2b_admin_id']
            ]);
        }
        
        // Merge additional parameters
        $finalPayload = array_merge($payload, $additionalParams);
        
        Log::info('Final API payload built', [
            'payload' => $finalPayload
        ]);
        
        return $finalPayload;
    }

    private function makeApiCall($url, $payload)
    {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            throw new \Exception('cURL Error: ' . $error);
        }
        
        if ($httpCode !== 200) {
            throw new \Exception('HTTP Error: ' . $httpCode);
        }
        
        $decodedResponse = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('JSON Decode Error: ' . json_last_error_msg());
        }
        
        return $decodedResponse;
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
        \Log::info('Has course_options: ' . ($request->has('course_options') ? 'YES' : 'NO'));
        \Log::info('Request data keys: ' . implode(', ', array_keys($request->all())));
        \Log::info('Files: ' . json_encode($request->allFiles()));
        
        // Specific logging for course options
        if ($request->has('course_options')) {
            \Log::info('Course options found in request:', [
                'is_array' => is_array($request->course_options),
                'count' => is_array($request->course_options) ? count($request->course_options) : 0,
                'course_options_data' => $request->course_options
            ]);
        } else {
            \Log::warning('No course_options found in request - this might be the issue');
            \Log::info('Available request keys:', array_keys($request->all()));
        }
        
        if ($request->has('documents')) {
            \Log::info('Documents structure:', $request->documents);
        }
        
        try {
            // Basic validation first - exclude documents for now
            $basicRules = [
                'name' => 'required|string|max:255',
                'phone' => 'required|numeric|digits:10',
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
                'ielts_listening' => $request->ielts_listening,
                'ielts_reading' => $request->ielts_reading,
                'ielts_writing' => $request->ielts_writing,
                'ielts_speaking' => $request->ielts_speaking,
                'toefl_score' => $request->toefl_overall,
                'toefl_listening' => $request->toefl_listening,
                'toefl_reading' => $request->toefl_reading,
                'toefl_writing' => $request->toefl_writing,
                'toefl_speaking' => $request->toefl_speaking,
                'pte_score' => $request->pte_overall,
                'pte_listening' => $request->pte_listening,
                'pte_reading' => $request->pte_reading,
                'pte_writing' => $request->pte_writing,
                'pte_speaking' => $request->pte_speaking,
                'duolingo_overall' => $request->duolingo_overall,
                'duolingo_listening' => $request->duolingo_listening,
                'duolingo_reading' => $request->duolingo_reading,
                'duolingo_writing' => $request->duolingo_writing,
                'duolingo_speaking' => $request->duolingo_speaking,
                
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

            // Process course options from course finder or create single option from main form
            if ($request->has('course_options') && is_array($request->course_options)) {
                \Log::info('Processing Course Options from Course Finder:', $request->course_options);
                
                foreach ($request->course_options as $index => $courseOption) {
                    $courseData = [
                        'application_id' => $application->id,
                        'country' => $courseOption['country'] ?? '',
                        'city' => $courseOption['city'] ?? '',
                        'college' => $courseOption['college'] ?? '',
                        'course' => $courseOption['course'] ?? '',
                        'course_type' => null, // Not collected from course finder
                        'fees' => null, // Not collected from course finder
                        'duration' => null, // Not collected from course finder
                        'intake_year' => $courseOption['intake_year'] ?? '',
                        'intake_month' => $courseOption['intake_month'] ?? '',
                        'college_detail_id' => $courseOption['college_detail_id'] ?? '',
                        'is_primary' => $index === 0, // First option is primary
                        'priority_order' => $index + 1,
                    ];
                    
                    \Log::info('Creating Course Option #' . ($index + 1) . ':', $courseData);
                    $courseRecord = ApplicationCourseOption::create($courseData);
                    \Log::info('Course Option Created:', ['id' => $courseRecord->id]);
                }
            } else {
                \Log::warning('No course_options array found in request');
                \Log::info('Checking for traditional form course fields...');
                
                // Create single course option from main form fields (traditional flow)
                if ($request->country || $request->college || $request->course) {
                    \Log::info('Creating Single Course Option from Main Form');
                    \Log::info('Traditional form course data:', [
                        'country' => $request->country,
                        'city' => $request->city,
                        'college' => $request->college,
                        'course' => $request->course,
                        'intake_year' => $request->intake_year,
                        'intake_month' => $request->intake_month,
                    ]);
                    
                    $singleCourseData = [
                        'application_id' => $application->id,
                        'country' => $request->country ?? '',
                        'city' => $request->city ?? '',
                        'college' => $request->college ?? '',
                        'course' => $request->course ?? '',
                        'course_type' => null,
                        'fees' => null,
                        'duration' => null,
                        'intake_year' => $request->intake_year ?? '',
                        'intake_month' => $request->intake_month ?? '',
                        'college_detail_id' => null,
                        'is_primary' => true,
                        'priority_order' => 1,
                    ];
                    
                    \Log::info('Creating Single Course Option:', $singleCourseData);
                    $courseRecord = ApplicationCourseOption::create($singleCourseData);
                    \Log::info('Single Course Option Created:', ['id' => $courseRecord->id]);
                } else {
                    \Log::warning('No course data found in request - neither course_options array nor traditional form fields');
                    \Log::info('Traditional form fields check:', [
                        'country' => $request->country ?? 'NULL',
                        'college' => $request->college ?? 'NULL', 
                        'course' => $request->course ?? 'NULL'
                    ]);
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
                
                // Additional form data that should not be stored in database but sent to API
                $additionalApiData = [
                    'gender' => $request->gender,
                    'passport_no' => $request->passport_no,
                    'marital_status' => $request->marital_status,
                ];
                
                $externalApiService->sendApplication($application, $additionalApiData);
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
        $application = Application::with(['creator', 'assignedUser', 'employmentHistory', 'documents', 'courseOptions'])->findOrFail($id);
        return view('applications.show', compact('application'));
    }

    // Edit and Update methods removed - Applications are read-only after creation

    public function destroy($id)
    {
        try {
            $application = Application::findOrFail($id);
            $application->delete();
            
            return redirect()->route('applications.index')
                ->with('success', 'Application deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('applications.index')
                ->with('error', 'Error deleting application: ' . $e->getMessage());
        }
    }

    /**
     * Get documents for a specific application (AJAX endpoint)
     */
    public function getDocuments($id)
    {
        try {
            $application = Application::findOrFail($id);
            $documents = $application->documents()->get();
            
            return response()->json([
                'success' => true,
                'documents' => $documents->map(function ($doc) {
                    return [
                        'id' => $doc->id,
                        'document_name' => $doc->document_name,
                        'file_path' => $doc->file_path,
                        'file_size' => $doc->file_size,
                        'is_mandatory' => $doc->is_mandatory,
                        'created_at' => $doc->created_at,
                        'updated_at' => $doc->updated_at,
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching documents: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View a specific document
     */
    public function viewDocument($id)
    {
        try {
            $document = ApplicationDocument::findOrFail($id);
            $filePath = storage_path('app/public/' . $document->file_path);
            
            if (!file_exists($filePath)) {
                abort(404, 'Document not found');
            }
            
            return response()->file($filePath);
        } catch (\Exception $e) {
            abort(404, 'Document not found');
        }
    }

    /**
     * Download a specific document
     */
    public function downloadDocument($id)
    {
        try {
            $document = ApplicationDocument::findOrFail($id);
            $filePath = storage_path('app/public/' . $document->file_path);
            
            if (!file_exists($filePath)) {
                abort(404, 'Document not found');
            }
            
            return response()->download($filePath, $document->document_name);
        } catch (\Exception $e) {
            abort(404, 'Document not found');
        }
    }

    /**
     * Download all documents for an application as a ZIP file
     */
    public function downloadAllDocuments($id)
    {
        try {
            $application = Application::findOrFail($id);
            $documents = $application->documents()->get();
            
            if ($documents->isEmpty()) {
                return redirect()->back()->with('error', 'No documents found for this application');
            }
            
            // Create a temporary ZIP file
            $zipFileName = 'application_' . $application->application_number . '_documents.zip';
            $zipPath = storage_path('app/temp/' . $zipFileName);
            
            // Create temp directory if it doesn't exist
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
                return redirect()->back()->with('error', 'Could not create ZIP file');
            }
            
            foreach ($documents as $document) {
                $filePath = storage_path('app/public/' . $document->file_path);
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $document->document_name);
                }
            }
            
            $zip->close();
            
            // Return the ZIP file as a download and delete it afterward
            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error downloading documents: ' . $e->getMessage());
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
            'gender' => $lead->gender ?? '',
            'passport_no' => $lead->passport_no ?? '',
            'marital_status' => $lead->marital_status ?? '',
            
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
                'phone' => 'required|numeric|digits:10',
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

    /**
     * Format file size for display
     */
    public static function formatFileSize($bytes)
    {
        if ($bytes === 0) return '0 Bytes';
        $k = 1024;
        $sizes = ['Bytes', 'KB', 'MB', 'GB'];
        $i = floor(log($bytes) / log($k));
        return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
    }

    public function showApiApplication($visaFormId)
    {
        // Redirect to detailed view - we only want one view with all details
        return redirect()->route('applications.show-api', $visaFormId);
    }

    /**
     * Get detailed application information including admissions and documents
     */
    public function getApplicationDetails($visaFormId)
    {
        try {
            // API endpoint for application details
            $apiUrl = 'https://tarundemo.innerxcrm.com/b2bapi/application_details';
            
            // Request payload - dynamically set based on user role
            $payload = $this->buildApiPayload([
                'visa_form_id' => $visaFormId
            ]);
            
            $response = $this->makeApiCall($apiUrl, $payload);
            
            if ($response['status'] === 'success') {
                return response()->json([
                    'success' => true,
                    'data' => $response
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch application details'
                ], 404);
            }
            
        } catch (\Exception $e) {
            Log::error('Error fetching application details from API', [
                'visa_form_id' => $visaFormId,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch application details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show detailed application view with admissions and documents
     */
    public function showApiApplicationDetails($visaFormId)
    {
        try {
            // Get basic application data by filtering the main application endpoint
            $basicApiUrl = 'https://tarundemo.innerxcrm.com/b2bapi/application';
            $basicPayload = $this->buildApiPayload([
                'visa_form_id' => $visaFormId
            ]);
            
            $basicResponse = $this->makeApiCall($basicApiUrl, $basicPayload);
            
            // Get detailed application data (admissions and documents)
            $detailsApiUrl = 'https://tarundemo.innerxcrm.com/b2bapi/application_details';
            $detailsPayload = $this->buildApiPayload([
                'visa_form_id' => $visaFormId
            ]);
            
            $detailsResponse = $this->makeApiCall($detailsApiUrl, $detailsPayload);
            
            if ($basicResponse['status'] === 'success' && !empty($basicResponse['data']) &&
                $detailsResponse['status'] === 'success') {
                
                // Find the specific application in the response data
                $application = null;
                foreach ($basicResponse['data'] as $app) {
                    if ($app['visa_form_id'] == $visaFormId) {
                        $application = $app;
                        break;
                    }
                }
                
                if (!$application) {
                    return redirect()->route('applications.index')
                        ->with('error', 'Application not found in API.');
                }
                
                $details = $detailsResponse;
                
                // Fetch journey data for each admission
                $journeyData = [];
                if (isset($details['admissions']) && is_array($details['admissions'])) {
                    foreach ($details['admissions'] as $admission) {
                        if (isset($admission['admissions_id'])) {
                            $admissionId = $admission['admissions_id'];
                            try {
                                $journey = $this->getJourneyData($admissionId);
                                if ($journey && isset($journey['status']) && $journey['status'] === 'success') {
                                    $journeyData[$admissionId] = $journey;
                                } else {
                                    Log::warning('Journey API returned unsuccessful status', [
                                        'admission_id' => $admissionId,
                                        'response' => $journey
                                    ]);
                                }
                            } catch (\Exception $e) {
                                Log::warning('Failed to fetch journey data for admission', [
                                    'admission_id' => $admissionId,
                                    'error' => $e->getMessage()
                                ]);
                            }
                        }
                    }
                }
                
                return view('applications.show-api-details', compact('application', 'details', 'visaFormId', 'journeyData'));
            } else {
                return redirect()->route('applications.index')
                    ->with('error', 'Application not found in API.');
            }
            
        } catch (\Exception $e) {
            Log::error('Error fetching detailed application from API', [
                'visa_form_id' => $visaFormId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('applications.index')
                ->with('error', 'Unable to fetch application details from API.');
        }
    }
    
    /**
     * Debug API endpoints to understand the structure and available data
     */
    public function debugApiEndpoints(Request $request)
    {
        try {
            $visaFormId = $request->get('visa_form_id', '388'); // Default test ID
            
            Log::info('=== DEBUG API ENDPOINTS ===');
            
            // Test 1: Get applications list to see available IDs
            $listApiUrl = 'https://tarundemo.innerxcrm.com/b2bapi/application';
            $listPayload = $this->buildApiPayload([
                'limit' => 5,
                'page' => 1
            ]);
            
            Log::info('Testing applications list API', [
                'url' => $listApiUrl,
                'payload' => $listPayload
            ]);
            
            $listResponse = $this->makeApiCall($listApiUrl, $listPayload);
            
            $availableIds = [];
            if ($listResponse['status'] === 'success' && !empty($listResponse['data'])) {
                $availableIds = collect($listResponse['data'])
                    ->pluck('visa_form_id')
                    ->take(3)
                    ->toArray();
            }
            
            Log::info('Available visa form IDs from list', ['ids' => $availableIds]);
            
            // Test 2: Try basic application API with first available ID
            $basicResults = [];
            if (!empty($availableIds)) {
                $testId = $availableIds[0];
                $basicApiUrl = 'https://tarundemo.innerxcrm.com/b2bapi/application/' . $testId;
                $basicPayload = $this->buildApiPayload([
                    'visa_form_id' => $testId
                ]);
                
                Log::info('Testing basic application API', [
                    'url' => $basicApiUrl,
                    'payload' => $basicPayload,
                    'test_id' => $testId
                ]);
                
                try {
                    $basicResponse = $this->makeApiCall($basicApiUrl, $basicPayload);
                    $basicResults = [
                        'status' => 'success',
                        'response' => $basicResponse,
                        'test_id' => $testId
                    ];
                    Log::info('Basic API call successful', ['test_id' => $testId]);
                } catch (\Exception $e) {
                    $basicResults = [
                        'status' => 'error',
                        'error' => $e->getMessage(),
                        'test_id' => $testId
                    ];
                    Log::error('Basic API call failed', ['test_id' => $testId, 'error' => $e->getMessage()]);
                }
            }
            
            // Test 3: Try application_details API with first available ID
            $detailsResults = [];
            if (!empty($availableIds)) {
                $testId = $availableIds[0];
                $detailsApiUrl = 'https://tarundemo.innerxcrm.com/b2bapi/application_details';
                $detailsPayload = $this->buildApiPayload([
                    'visa_form_id' => $testId
                ]);
                
                Log::info('Testing application details API', [
                    'url' => $detailsApiUrl,
                    'payload' => $detailsPayload,
                    'test_id' => $testId
                ]);
                
                try {
                    $detailsResponse = $this->makeApiCall($detailsApiUrl, $detailsPayload);
                    $detailsResults = [
                        'status' => 'success',
                        'response' => $detailsResponse,
                        'test_id' => $testId
                    ];
                    Log::info('Details API call successful', ['test_id' => $testId]);
                } catch (\Exception $e) {
                    $detailsResults = [
                        'status' => 'error',
                        'error' => $e->getMessage(),
                        'test_id' => $testId
                    ];
                    Log::error('Details API call failed', ['test_id' => $testId, 'error' => $e->getMessage()]);
                }
            }
            
            return response()->json([
                'debug_summary' => 'API endpoints testing completed',
                'available_visa_form_ids' => $availableIds,
                'tested_id' => $availableIds[0] ?? null,
                'applications_list' => [
                    'status' => $listResponse['status'] ?? 'error',
                    'count' => count($listResponse['data'] ?? [])
                ],
                'basic_application_api' => $basicResults,
                'application_details_api' => $detailsResults,
                'logs' => 'Check Laravel logs for detailed information'
            ], 200, [], JSON_PRETTY_PRINT);
            
        } catch (\Exception $e) {
            Log::error('Debug API endpoints failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Debug failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Test the API integration - temporary method for debugging
     */
    public function testApiIntegration($visaFormId = 388)
    {
        try {
            // Simplified payload for testing without authentication
            $basicPayload = ['b2b_admin_id' => 1, 'visa_form_id' => $visaFormId];
            $detailsPayload = ['b2b_admin_id' => 1, 'visa_form_id' => $visaFormId];
            
            // Get basic application data by filtering the main application endpoint
            $basicApiUrl = 'https://tarundemo.innerxcrm.com/b2bapi/application';
            $basicResponse = $this->makeApiCall($basicApiUrl, $basicPayload);
            
            // Get detailed application data (admissions and documents)
            $detailsApiUrl = 'https://tarundemo.innerxcrm.com/b2bapi/application_details';
            $detailsResponse = $this->makeApiCall($detailsApiUrl, $detailsPayload);
            
            $application = null;
            if ($basicResponse['status'] === 'success' && !empty($basicResponse['data'])) {
                foreach ($basicResponse['data'] as $app) {
                    if ($app['visa_form_id'] == $visaFormId) {
                        $application = $app;
                        break;
                    }
                }
            }
            
            return response()->json([
                'basic_api_status' => $basicResponse['status'] ?? 'failed',
                'basic_api_data_count' => count($basicResponse['data'] ?? []),
                'application_found' => $application ? true : false,
                'details_api_status' => $detailsResponse['status'] ?? 'failed',
                'details_admissions_count' => $detailsResponse['admissions_count'] ?? 0,
                'details_documents_count' => $detailsResponse['documents_count'] ?? 0,
                'application_name' => $application['name'] ?? 'Not found',
                'application_id' => $application['visa_form_id'] ?? 'Not found',
                'basic_response_keys' => array_keys($basicResponse),
                'details_response_keys' => array_keys($detailsResponse),
            ], 200, [], JSON_PRETTY_PRINT);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => array_slice(explode("\n", $e->getTraceAsString()), 0, 10)
            ], 500, [], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Get application journey details for a specific admission
     */
    public function getApplicationJourney(Request $request)
    {
        try {
            $applicationId = $request->get('application_id');
            
            if (!$applicationId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Application ID is required'
                ], 400);
            }

            // API endpoint for application journey
            $apiUrl = 'https://tarundemo.innerxcrm.com/b2bapi/application_journey';
            
            // Request payload - dynamically set based on user role
            $payload = $this->buildApiPayload([
                'application_id' => $applicationId
            ]);
            
            $response = $this->makeApiCall($apiUrl, $payload);
            
            if ($response['status'] === 'success') {
                return response()->json([
                    'success' => true,
                    'data' => $response
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch application journey'
                ], 404);
            }
            
        } catch (\Exception $e) {
            Log::error('Error fetching application journey from API', [
                'application_id' => $request->get('application_id'),
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch application journey: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show application journey page for a specific application
     * This method expects a visa_form_id and will get the correct admissions_id
     */
    public function showApplicationJourney($visaFormId)
    {
        try {
            Log::info('Fetching application journey', ['visa_form_id' => $visaFormId]);
            
            // Get the admissions for this visa_form_id first
            $admissionId = $this->getFirstAdmissionId($visaFormId);
            
            if (!$admissionId) {
                return redirect()->route('applications.index')
                    ->with('error', 'No admissions found for this application. This application may not have any admissions yet.');
            }
            
            Log::info('Found admission ID for journey', ['admission_id' => $admissionId, 'visa_form_id' => $visaFormId]);
            
            // Get journey data using the correct admissions_id
            $journeyData = $this->getJourneyData($admissionId);
            
            if (!$journeyData) {
                // Try to get at least admission data
                $admissionData = $this->getAdmissionData($visaFormId, $admissionId);
                if ($admissionData) {
                    $journeyData = [
                        'status' => 'partial',
                        'admission' => $admissionData,
                        'message' => 'Journey tracking is currently unavailable, but admission details are shown.'
                    ];
                } else {
                    return redirect()->route('applications.index')
                        ->with('error', 'No data found for this application.');
                }
            }
            
            // Process journey data if we have it
            if (isset($journeyData['journey'])) {
                $processedJourney = $this->processJourneyData($journeyData);
                $journeyData['processed_journey'] = $processedJourney;
            }
            
            // Get application info for context
            $applicationInfo = $this->getApplicationInfo($visaFormId, $journeyData);
            
            return view('applications.journey', compact('journeyData', 'applicationInfo', 'visaFormId', 'admissionId'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching application journey', [
                'visa_form_id' => $visaFormId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('applications.index')
                ->with('error', 'Unable to fetch application journey: ' . $e->getMessage());
        }
    }
    
    /**
     * Process and format journey data from API response
     */
    private function processJourneyData($journeyData)
    {
        if (!isset($journeyData['journey'])) {
            return null;
        }

        $journey = $journeyData['journey'];
        $journeyFull = $journey['journey_full'] ?? [];
        $journeyDone = $journey['journey_done'] ?? [];
        
        // Create a structured journey with status for each step
        $processedJourney = [];
        
        foreach ($journeyFull as $index => $step) {
            $isCompleted = in_array($step, $journeyDone);
            $isActive = false;
            
            // Determine if this is the current active step (first incomplete step)
            if (!$isCompleted) {
                $allPreviousCompleted = true;
                for ($i = 0; $i < $index; $i++) {
                    if (!in_array($journeyFull[$i], $journeyDone)) {
                        $allPreviousCompleted = false;
                        break;
                    }
                }
                $isActive = $allPreviousCompleted;
            }
            
            $processedJourney[] = [
                'step' => $step,
                'order' => $index + 1,
                'status' => $isCompleted ? 'completed' : ($isActive ? 'active' : 'pending'),
                'is_completed' => $isCompleted,
                'is_active' => $isActive,
                'progress_percentage' => $this->calculateStepProgress($step, $journeyData['admission'] ?? [])
            ];
        }
        
        // Calculate overall progress
        $overallProgress = count($journeyFull) > 0 ? (count($journeyDone) / count($journeyFull)) * 100 : 0;
        
        return [
            'steps' => $processedJourney,
            'total_steps' => count($journeyFull),
            'completed_steps' => count($journeyDone),
            'overall_progress' => round($overallProgress, 1),
            'current_step' => $this->getCurrentStepDetails($processedJourney),
            'next_step' => $this->getNextStepDetails($processedJourney)
        ];
    }

    /**
     * Calculate progress percentage for individual steps based on admission data
     */
    private function calculateStepProgress($step, $admissionData)
    {
        // Map steps to their corresponding data fields in admission
        $stepMappings = [
            'OFFER APPLIED' => ['mark_complete'],
            'OFFER UPLOAD' => ['offer_letter'],
            'OFFER DENIED' => ['offer_letter_info.offer_denied'],
            'TT RECEIPT' => ['tt_info.tt_receipt_date', 'tt_info.tt_file'],
            'TT MAIL' => ['tt_info.tt_receipt_mail_date'],
            'INSTITUTE PAYMENT' => ['institute_payment'],
            'FILLING DOCUMENT' => ['filling_docs'],
            'VISA LODGMENT' => ['visa_lodgment'],
            'VISA STATUS' => ['visa_info.visa_status', 'visa_info.visa_date']
        ];
        
        if (!isset($stepMappings[$step])) {
            return 0;
        }
        
        $fields = $stepMappings[$step];
        $completedFields = 0;
        $totalFields = count($fields);
        
        foreach ($fields as $field) {
            if (strpos($field, '.') !== false) {
                // Handle nested fields
                $parts = explode('.', $field);
                $value = $admissionData;
                foreach ($parts as $part) {
                    $value = $value[$part] ?? null;
                    if ($value === null) break;
                }
            } else {
                $value = $admissionData[$field] ?? null;
            }
            
            // Check if field has meaningful data
            if ($this->hasValidData($value)) {
                $completedFields++;
            }
        }
        
        return $totalFields > 0 ? round(($completedFields / $totalFields) * 100) : 0;
    }

    /**
     * Check if a value contains valid/meaningful data
     */
    private function hasValidData($value)
    {
        if ($value === null || $value === '' || $value === '0000-00-00' || $value === '0000-00-00 00:00:00') {
            return false;
        }
        
        if (is_array($value)) {
            return !empty($value);
        }
        
        if (is_numeric($value)) {
            return $value > 0;
        }
        
        return !empty(trim($value));
    }

    /**
     * Get details of the current active step
     */
    private function getCurrentStepDetails($processedJourney)
    {
        foreach ($processedJourney as $step) {
            if ($step['is_active']) {
                return $step;
            }
        }
        
        // If no active step found, return the last completed step
        $completedSteps = array_filter($processedJourney, function($step) {
            return $step['is_completed'];
        });
        
        return !empty($completedSteps) ? end($completedSteps) : null;
    }

    /**
     * Get details of the next pending step
     */
    private function getNextStepDetails($processedJourney)
    {
        $foundActive = false;
        
        foreach ($processedJourney as $step) {
            if ($foundActive && $step['status'] === 'pending') {
                return $step;
            }
            
            if ($step['is_active']) {
                $foundActive = true;
            }
        }
        
        return null;
    }

    /**
     * Get admission data from application_details API when journey data is not available
     */
    private function getAdmissionData($visaFormId, $admissionId)
    {
        try {
            $apiUrl = 'https://tarundemo.innerxcrm.com/b2bapi/application_details';
            $payload = $this->buildApiPayload([
                'visa_form_id' => $visaFormId
            ]);
            
            $response = $this->makeApiCall($apiUrl, $payload);
            
            if ($response['status'] === 'success' && !empty($response['admissions'])) {
                // Find the specific admission
                foreach ($response['admissions'] as $admission) {
                    if ($admission['admissions_id'] == $admissionId) {
                        return $admission;
                    }
                }
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Error getting admission data', [
                'visa_form_id' => $visaFormId,
                'admission_id' => $admissionId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
    
    /**
     * Get journey data for a specific admission ID
     */
    private function getJourneyData($admissionId)
    {
        try {
            $apiUrl = 'https://tarundemo.innerxcrm.com/b2bapi/application_journey';
            $payload = $this->buildApiPayload([
                'application_id' => $admissionId // Note: API uses 'application_id' but actually expects admission_id
            ]);
            
            Log::info('Journey API payload', ['payload' => $payload, 'url' => $apiUrl]);
            
            $response = $this->makeApiCall($apiUrl, $payload);
            
            Log::info('Journey API response', ['response' => $response]);
            
            if ($response['status'] === 'success') {
                return $response;
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::error('Error calling journey API', [
                'admission_id' => $admissionId,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}
