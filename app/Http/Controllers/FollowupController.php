<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadFollowup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class FollowupController extends Controller
{
    /**
     * Display a listing of the resource.
     * Redirects to dashboard instead.
     */
    public function index()
    {
        return redirect()->route('followups.dashboard');
    }

    /**
     * Display the followup dashboard.
     */
    public function dashboard()
    {
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        // Get stats
        $stats = [
            'today' => LeadFollowup::whereDate('scheduled_at', $today)
                                  ->where('status', 'pending')
                                  ->count(),
            'this_week' => LeadFollowup::whereBetween('scheduled_at', [$startOfWeek, $endOfWeek])
                                      ->where('status', 'pending')
                                      ->count(),
            'overdue' => LeadFollowup::where('scheduled_at', '<', $today)
                                    ->where('status', 'pending')
                                    ->count(),
            'completed' => LeadFollowup::where('status', 'completed')
                                      ->whereDate('completed_at', $today)
                                      ->count(),
        ];

        // Get today's followups
        $todaysFollowups = LeadFollowup::with(['lead', 'user'])
                                      ->whereDate('scheduled_at', $today)
                                      ->where('status', 'pending')
                                      ->orderBy('scheduled_at', 'asc')
                                      ->get();

        // Get overdue followups
        $overdueFollowups = LeadFollowup::with(['lead', 'user'])
                                       ->where('scheduled_at', '<', $today)
                                       ->where('status', 'pending')
                                       ->orderBy('scheduled_at', 'asc')
                                       ->take(10)
                                       ->get();

        // Get upcoming this week (excluding today)
        $upcomingFollowups = LeadFollowup::with(['lead', 'user'])
                                        ->whereBetween('scheduled_at', [$today->copy()->addDay(), $endOfWeek])
                                        ->where('status', 'pending')
                                        ->orderBy('scheduled_at', 'asc')
                                        ->take(10)
                                        ->get();

        // Get recently completed
        $recentCompleted = LeadFollowup::with(['lead', 'user'])
                                      ->where('status', 'completed')
                                      ->whereDate('completed_at', '>=', $today->copy()->subDays(7))
                                      ->orderBy('completed_at', 'desc')
                                      ->take(10)
                                      ->get();

        return view('followups.dashboard', compact(
            'stats',
            'todaysFollowups',
            'overdueFollowups',
            'upcomingFollowups',
            'recentCompleted'
        ));
    }

    /**
     * Show the form for creating a new resource.
     * Redirects to dashboard instead - follow-ups are created from lead pages.
     */
    public function create()
    {
        return redirect()->route('followups.dashboard');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_id' => 'required|exists:leads,id',
            'type' => 'required|in:call,email,meeting,visit,document,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'scheduled_time' => 'required',
            'priority' => 'required|in:low,medium,high,urgent',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Combine date and time
            $scheduledAt = Carbon::parse($request->scheduled_date . ' ' . $request->scheduled_time);

            $followup = LeadFollowup::create([
                'lead_id' => $request->lead_id,
                'user_id' => auth()->id(),
                'type' => $request->type,
                'subject' => $request->subject,
                'description' => $request->description,
                'status' => 'pending',
                'scheduled_at' => $scheduledAt,
                'priority' => $request->priority,
                'notes' => $request->notes,
            ]);

            // TODO: Add next_followup_date column to leads table if needed
            // Lead::where('id', $request->lead_id)->update([
            //     'next_followup_date' => $scheduledAt->toDateString()
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'Follow-up added successfully',
                'followup' => $followup->load('lead', 'user')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating follow-up: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $followup = LeadFollowup::with(['lead', 'user'])->findOrFail($id);
        return view('followups.show', compact('followup'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $followup = LeadFollowup::with(['lead'])->findOrFail($id);
        $leads = Lead::select('id', 'name', 'ref_no')->get();
        return view('followups.edit', compact('followup', 'leads'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $followup = LeadFollowup::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'type' => 'required|in:call,email,meeting,visit,document,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required',
            'priority' => 'required|in:low,medium,high,urgent',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Combine date and time
            $scheduledAt = Carbon::parse($request->scheduled_date . ' ' . $request->scheduled_time);

            $followup->update([
                'type' => $request->type,
                'subject' => $request->subject,
                'description' => $request->description,
                'scheduled_at' => $scheduledAt,
                'priority' => $request->priority,
                'notes' => $request->notes,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Follow-up updated successfully',
                'followup' => $followup->load('lead', 'user')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating follow-up: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $followup = LeadFollowup::findOrFail($id);
            $followup->delete();

            return response()->json([
                'success' => true,
                'message' => 'Follow-up deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting follow-up: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark followup as completed
     */
    public function complete(string $id)
    {
        try {
            $followup = LeadFollowup::findOrFail($id);
            
            $followup->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Follow-up marked as completed',
                'followup' => $followup->load('lead', 'user')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error completing follow-up: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reschedule a followup
     */
    public function reschedule(Request $request, string $id)
    {
        try {
            $followup = LeadFollowup::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'scheduled_date' => 'required|date|after_or_equal:today',
                'scheduled_time' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Combine date and time
            $scheduledAt = Carbon::parse($request->scheduled_date . ' ' . $request->scheduled_time);

            $followup->update([
                'scheduled_at' => $scheduledAt
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Follow-up rescheduled successfully',
                'followup' => $followup->load('lead', 'user')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rescheduling follow-up: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display today's followups page
     */
    public function todaysFollowups()
    {
        $today = Carbon::today();
        
        $todaysFollowups = LeadFollowup::with(['lead', 'user'])
                                      ->whereDate('scheduled_at', $today)
                                      ->where('status', 'pending')
                                      ->orderBy('scheduled_at', 'asc')
                                      ->paginate(15);

        return view('followups.today', compact('todaysFollowups'));
    }

    /**
     * Display overdue followups page
     */
    public function overdueFollowups()
    {
        $today = Carbon::today();
        
        $overdueFollowups = LeadFollowup::with(['lead', 'user'])
                                       ->where('scheduled_at', '<', $today)
                                       ->where('status', 'pending')
                                       ->orderBy('scheduled_at', 'asc')
                                       ->paginate(15);

        return view('followups.overdue', compact('overdueFollowups'));
    }
}
