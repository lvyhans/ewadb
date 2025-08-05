<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CourseFinderController extends Controller
{
    private $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.external_api.course_finder_url', 'https://tarundemo.innerxcrm.com/b2bapi/course_finder');
    }

    /**
     * Display the course finder page
     */
    public function index()
    {
        return view('courses.finder');
    }

    /**
     * Search for courses using the external API
     */
    public function search(Request $request)
    {
        try {
            // Prepare the search parameters
            $searchParams = $this->prepareSearchParams($request);
            
            Log::info('Course search parameters:', $searchParams);

            // Make the API request
            $response = Http::timeout(30)
                ->post($this->apiUrl, $searchParams);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('Course search API response status:', ['status' => $data['status'] ?? 'unknown']);
                
                return response()->json($data);
            } else {
                Log::error('Course search API error:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to fetch courses from external API',
                    'error_code' => $response->status()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Course search exception:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while searching for courses',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available filter options from the API
     */
    public function getFilters()
    {
        try {
            // Make a basic request to get the filters
            $response = Http::timeout(30)
                ->post($this->apiUrl, [
                    'limit' => 1,
                    'page' => 1
                ]);

            if ($response->successful()) {
                $data = $response->json();
                
                return response()->json([
                    'status' => 'success',
                    'filters' => $data['filters'] ?? []
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to fetch filter options'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Get filters exception:', [
                'message' => $e->getMessage()
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching filter options'
            ], 500);
        }
    }

    /**
     * Prepare search parameters from the request
     */
    private function prepareSearchParams(Request $request): array
    {
        $searchParams = [];
        
        // Basic search parameters
        if ($request->filled('search')) {
            $searchParams['search'] = $request->input('search');
        }
        
        if ($request->filled('country')) {
            $searchParams['country'] = $request->input('country');
        }
        
        if ($request->filled('course_type')) {
            $searchParams['course_type'] = $request->input('course_type');
        }
        
        if ($request->filled('study_area')) {
            $searchParams['study_area'] = $request->input('study_area');
        }
        
        if ($request->filled('discipline_area')) {
            $searchParams['discipline_area'] = $request->input('discipline_area');
        }
        
        if ($request->filled('duration')) {
            $searchParams['duration'] = $request->input('duration');
        }

        // English proficiency parameters
        if ($request->filled('ielts_min')) {
            $searchParams['ielts_min'] = $request->input('ielts_min');
        }
        
        if ($request->filled('ielts_max')) {
            $searchParams['ielts_max'] = $request->input('ielts_max');
        }
        
        if ($request->filled('toefl_min')) {
            $searchParams['toefl_min'] = $request->input('toefl_min');
        }
        
        if ($request->filled('pte_min')) {
            $searchParams['pte_min'] = $request->input('pte_min');
        }
        
        if ($request->filled('duolingo_min')) {
            $searchParams['duolingo_min'] = $request->input('duolingo_min');
        }

        // Student profile parameters
        if ($request->filled('backlogs')) {
            $searchParams['backlogs'] = $request->input('backlogs');
        }
        
        if ($request->filled('gap')) {
            $searchParams['gap'] = $request->input('gap');
        }
        
        if ($request->filled('twelve_plus_three')) {
            $searchParams['twelve_plus_three'] = $request->input('twelve_plus_three');
        }
        
        if ($request->filled('conditional_admission')) {
            $searchParams['conditional_admission'] = $request->input('conditional_admission');
        }
        
        if ($request->filled('without_english_proficiency')) {
            $searchParams['without_english_proficiency'] = $request->input('without_english_proficiency');
        }

        // Financial parameters
        if ($request->filled('fees_min')) {
            $searchParams['fees_min'] = $request->input('fees_min');
        }
        
        if ($request->filled('fees_max')) {
            $searchParams['fees_max'] = $request->input('fees_max');
        }

        // Intake parameters
        if ($request->filled('intake')) {
            $searchParams['intake'] = $request->input('intake');
        }

        // Pagination parameters
        $searchParams['limit'] = $request->input('limit', 10);
        $searchParams['page'] = $request->input('page', 1);

        return $searchParams;
    }

    /**
     * Get course details by ID (if the API supports it)
     */
    public function getCourseDetails(Request $request, $courseId)
    {
        try {
            // For now, return a message that details should be viewed from the search results
            return response()->json([
                'status' => 'info',
                'message' => 'Course details are available in the search results. Please use the View button in the course table.',
                'course_id' => $courseId
            ]);
        } catch (\Exception $e) {
            Log::error('Get course details exception:', [
                'message' => $e->getMessage(),
                'course_id' => $courseId
            ]);
            
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching course details'
            ], 500);
        }
    }
}
