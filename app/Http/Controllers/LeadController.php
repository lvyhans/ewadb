<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadEmploymentHistory;
use App\Models\LeadFollowup;
use App\Services\ExternalApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    /**
     * Display a listing of leads
     */
    public function index(Request $request)
    {
        $query = Lead::with(['creator', 'assignedUser', 'latestFollowup'])
                    ->orderBy('created_at', 'desc');

        // Special filters for follow-ups
        if ($request->has('filter')) {
            if ($request->filter === 'today_followups') {
                $query->whereHas('followups', function($q) {
                    $q->whereDate('scheduled_at', today())
                      ->where('status', 'scheduled');
                });
            } elseif ($request->filter === 'overdue_followups') {
                $query->whereHas('followups', function($q) {
                    $q->where('scheduled_at', '<', now())
                      ->where('status', 'scheduled');
                });
            }
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by assigned user
        if ($request->has('assigned_to') && $request->assigned_to) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('ref_no', 'like', "%{$search}%");
            });
        }

        $leads = $query->paginate(15);

        // If this is a web request, return view instead of JSON
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => true,
                'data' => $leads,
            ]);
        }

        return view('leads.index', compact('leads'));
    }

    /**
     * Store a new lead
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'preferred_country' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
            'score_type' => 'nullable|in:ielts,pte,duolingo',
            'last_qualification' => 'nullable|in:12th,Diploma,Graduation,Post Graduation',
            'status' => 'nullable|in:new,contacted,qualified,converted,rejected',
            'employementhistory' => 'nullable|array',
            'employementhistory.*.join_date' => 'required_with:employementhistory|date',
            'employementhistory.*.left_date' => 'required_with:employementhistory|date',
            'employementhistory.*.company_name' => 'required_with:employementhistory|string',
            'employementhistory.*.job_position' => 'required_with:employementhistory|string',
            'employementhistory.*.job_city' => 'required_with:employementhistory|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        
        try {
            // Generate reference number if not provided
            $refNo = $request->ref_no ?: Lead::generateRefNo();
            
            // Map form fields to database fields
            $leadData = [
                'ref_no' => $refNo,
                'name' => $request->name,
                'dob' => $request->dob,
                'father_name' => $request->father,
                'phone' => $request->phone,
                'alt_phone' => $request->rphone,
                'email' => $request->email,
                'city' => $request->f_city ?? $request->city,
                'address' => $request->address,
                'preferred_country' => $request->country,
                'preferred_city' => $request->city,
                'preferred_college' => $request->college,
                'preferred_course' => $request->course,
                'travel_history' => $request->travel_history,
                'any_refusal' => $request->any_refusal,
                'spouse_name' => $request->spouse_name,
                'any_gap' => $request->any_gap,
                'score_type' => $request->score_type,
                
                // English proficiency scores
                'ielts_listening' => $request->ielts_listening,
                'ielts_reading' => $request->ielts_reading,
                'ielts_writing' => $request->ielts_writing,
                'ielts_speaking' => $request->ielts_speaking,
                'ielts_overall' => $request->ielts_overall,
                
                'pte_listening' => $request->pte_listening,
                'pte_reading' => $request->pte_reading,
                'pte_writing' => $request->pte_writing,
                'pte_speaking' => $request->pte_speaking,
                'pte_overall' => $request->pte_overall,
                
                'duolingo_listening' => $request->duolingo_listening,
                'duolingo_reading' => $request->duolingo_reading,
                'duolingo_writing' => $request->duolingo_writing,
                'duolingo_speaking' => $request->duolingo_speaking,
                'duolingo_overall' => $request->duolingo_overall,
                
                // Qualifications
                'last_qualification' => $request->last_qual,
                'twelfth_year' => $request->twelveyear,
                'twelfth_percentage' => $request->twelvemarks,
                'twelfth_major' => $request->twelvemajor,
                'tenth_year' => $request->tenyear,
                'tenth_percentage' => $request->tenmarks,
                'tenth_major' => $request->ten_major,
                'diploma_year' => $request->ugdiplomayear,
                'diploma_percentage' => $request->ugdiplomamarks,
                'diploma_major' => $request->ugdiplomamajor,
                'graduation_year' => $request->byear,
                'graduation_percentage' => $request->bmarks,
                'graduation_major' => $request->gra_major,
                'post_graduation_year' => $request->pgr_year,
                'post_graduation_percentage' => $request->pgr_per,
                'post_graduation_major' => $request->pgr_major,
                
                'previous_visa_application' => $request->already_applied,
                'source' => $request->source,
                'reference_name' => $request->r_name,
                'remarks' => $request->remarks,
                'status' => $request->status ?? 'new',
                'created_by' => Auth::id(),
            ];

            $lead = Lead::create($leadData);

            // Save employment history
            if ($request->has('employementhistory') && is_array($request->employementhistory)) {
                foreach ($request->employementhistory as $employment) {
                    if (!empty($employment['company_name'])) {
                        LeadEmploymentHistory::create([
                            'lead_id' => $lead->id,
                            'join_date' => $employment['join_date'],
                            'left_date' => $employment['left_date'],
                            'company_name' => $employment['company_name'],
                            'job_position' => $employment['job_position'],
                            'job_city' => $employment['job_city'],
                        ]);
                    }
                }
            }

            DB::commit();

            // Send lead data to external API (non-blocking)
            try {
                $externalApiService = new ExternalApiService();
                $externalApiService->sendEnquiryLead($lead);
            } catch (\Exception $e) {
                // Log the error but don't fail the lead creation
                \Log::error('Failed to send lead to external API', [
                    'lead_id' => $lead->id,
                    'error' => $e->getMessage()
                ]);
            }

            // Handle web vs API response
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lead created successfully',
                    'data' => $lead->load(['employmentHistory', 'creator'])
                ], 201);
            } else {
                return redirect()->route('leads.index')->with('success', 'Lead created successfully!');
            }

        } catch (\Exception $e) {
            DB::rollback();
            
            // Handle web vs API error response
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating lead: ' . $e->getMessage()
                ], 500);
            } else {
                return back()->withInput()->withErrors(['error' => 'Error creating lead: ' . $e->getMessage()]);
            }
        }
    }

    /**
     * Display the specified lead
     */
    public function show($id)
    {
        $lead = Lead::with([
            'employmentHistory', 
            'followups.user', 
            'creator', 
            'assignedUser',
            'reverts' => function($query) {
                $query->with('resolver:id,name')->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);

        // Add revert statistics
        $revertStats = [
            'total_reverts' => $lead->reverts->count(),
            'active_reverts' => $lead->reverts->where('status', 'active')->count(),
            'resolved_reverts' => $lead->reverts->where('status', 'resolved')->count(),
            'overdue_reverts' => $lead->reverts->filter->isOverdue()->count(),
            'high_priority_active' => $lead->reverts->where('status', 'active')->where('priority', 'high')->count(),
            'urgent_priority_active' => $lead->reverts->where('status', 'active')->where('priority', 'urgent')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $lead,
            'revert_statistics' => $revertStats
        ]);
    }

    /**
     * Update the specified lead
     */
    public function update(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'nullable|email|max:255',
            'status' => 'nullable|in:new,contacted,qualified,converted,rejected',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $lead->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Lead updated successfully',
            'data' => $lead->fresh(['creator', 'assignedUser'])
        ]);
    }

    /**
     * Remove the specified lead
     */
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lead deleted successfully'
        ]);
    }

    /**
     * Get lead statistics
     */
    public function statistics()
    {
        $stats = [
            'total_leads' => Lead::count(),
            'new_leads' => Lead::where('status', 'new')->count(),
            'contacted_leads' => Lead::where('status', 'contacted')->count(),
            'qualified_leads' => Lead::where('status', 'qualified')->count(),
            'converted_leads' => Lead::where('status', 'converted')->count(),
            'rejected_leads' => Lead::where('status', 'rejected')->count(),
            'leads_this_month' => Lead::whereMonth('created_at', now()->month)
                                     ->whereYear('created_at', now()->year)
                                     ->count(),
            'pending_followups' => LeadFollowup::where('status', 'scheduled')
                                              ->where('scheduled_at', '<=', now())
                                              ->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Web method to display leads index page
     */
    public function webIndex(Request $request)
    {
        $query = Lead::with(['creator', 'assignedUser', 'latestFollowup'])
                    ->orderBy('created_at', 'desc');

        // Special filters for follow-ups
        if ($request->has('filter')) {
            if ($request->filter === 'today_followups') {
                $query->whereHas('followups', function($q) {
                    $q->whereDate('scheduled_at', today())
                      ->where('status', 'scheduled');
                });
            } elseif ($request->filter === 'overdue_followups') {
                $query->whereHas('followups', function($q) {
                    $q->where('scheduled_at', '<', now())
                      ->where('status', 'scheduled');
                });
            }
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by assigned user
        if ($request->has('assigned_to') && $request->assigned_to) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('ref_no', 'like', "%{$search}%");
            });
        }

        $leads = $query->paginate(15);
        
        // Calculate statistics for all leads (not just the current page)
        $statistics = [
            'total' => Lead::count(),
            'new' => Lead::where('status', 'new')->count(),
            'contacted' => Lead::where('status', 'contacted')->count(),
            'qualified' => Lead::where('status', 'qualified')->count(),
            'converted' => Lead::where('status', 'converted')->count(),
            'rejected' => Lead::where('status', 'rejected')->count(),
        ];
        
        // Add filter information for display
        $filterType = $request->filter;
        $pageTitle = 'All Leads';
        
        if ($filterType === 'today_followups') {
            $pageTitle = "Today's Follow-ups";
        } elseif ($filterType === 'overdue_followups') {
            $pageTitle = 'Overdue Follow-ups';
        }

        return view('leads.index', compact('leads', 'pageTitle', 'filterType', 'statistics'));
    }

    /**
     * Display a specific lead in web view
     */
    public function webShow($id)
    {
        $lead = Lead::with(['creator', 'assignedUser', 'employmentHistory', 'followups.user'])
                   ->findOrFail($id);
        
        return view('leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified lead (Web)
     */
    public function edit($id)
    {
        $lead = Lead::with(['creator', 'assignedUser', 'employmentHistory'])
                   ->findOrFail($id);
        
        return view('leads.edit', compact('lead'));
    }

    /**
     * Update the specified lead (Web)
     */
    public function webUpdate(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'dob' => 'nullable|date',
            'father_name' => 'nullable|string|max:255',
            'alt_phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'preferred_country' => 'nullable|string|max:255',
            'preferred_city' => 'nullable|string|max:255',
            'preferred_college' => 'nullable|string|max:255',
            'preferred_course' => 'nullable|string|max:255',
            'travel_history' => 'nullable|string',
            'any_refusal' => 'nullable|string',
            'spouse_name' => 'nullable|string|max:255',
            'any_gap' => 'nullable|string',
            'last_qualification' => 'nullable|in:10th,12th,diploma,bachelor,graduation,master,post graduation,phd',
            'previous_visa_application' => 'nullable|string',
            'score_type' => 'nullable|in:ielts,toefl,pte,duolingo,other,none',
            'ielts_overall' => 'nullable|numeric|between:0,9',
            'ielts_listening' => 'nullable|numeric|between:0,9',
            'ielts_reading' => 'nullable|numeric|between:0,9',
            'ielts_writing' => 'nullable|numeric|between:0,9',
            'ielts_speaking' => 'nullable|numeric|between:0,9',
            'pte_overall' => 'nullable|numeric|between:0,90',
            'pte_listening' => 'nullable|numeric|between:0,90',
            'pte_reading' => 'nullable|numeric|between:0,90',
            'pte_writing' => 'nullable|numeric|between:0,90',
            'pte_speaking' => 'nullable|numeric|between:0,90',
            'duolingo_overall' => 'nullable|numeric|between:0,160',
            'duolingo_listening' => 'nullable|numeric|between:0,160',
            'duolingo_reading' => 'nullable|numeric|between:0,160',
            'duolingo_writing' => 'nullable|numeric|between:0,160',
            'duolingo_speaking' => 'nullable|numeric|between:0,160',
            'source' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
            'status' => 'nullable|in:new,contacted,qualified,converted,rejected',
            'assigned_to' => 'nullable|exists:users,id',
            'employementhistory' => 'nullable|array',
            'employementhistory.*.join_date' => 'nullable|date',
            'employementhistory.*.left_date' => 'nullable|date',
            'employementhistory.*.company_name' => 'nullable|string',
            'employementhistory.*.job_position' => 'nullable|string',
            'employementhistory.*.job_city' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // Update lead basic information
            $leadData = $request->except(['employementhistory']);
            $lead->update($leadData);

            // Update employment history
            if ($request->has('employementhistory') && is_array($request->employementhistory)) {
                // Delete existing employment history
                $lead->employmentHistory()->delete();
                
                // Create new employment history records
                foreach ($request->employementhistory as $employment) {
                    if (!empty($employment['company_name'])) {
                        $lead->employmentHistory()->create([
                            'join_date' => $employment['join_date'] ?? null,
                            'left_date' => $employment['left_date'] ?? null,
                            'company_name' => $employment['company_name'],
                            'job_position' => $employment['job_position'] ?? null,
                            'job_city' => $employment['job_city'] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('leads.show', $lead->id)
                           ->with('success', 'Lead updated successfully!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error updating lead: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Test external API connection
     */
    public function testExternalApi()
    {
        $externalApiService = new ExternalApiService();
        $result = $externalApiService->testConnection();
        
        return response()->json($result);
    }
}
