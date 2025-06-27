<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadFollowup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeadFollowupController extends Controller
{
    /**
     * Display followups for a specific lead
     */
    public function index($leadId)
    {
        $lead = Lead::findOrFail($leadId);
        $followups = $lead->followups()
                         ->with('user')
                         ->orderBy('scheduled_at', 'desc')
                         ->get();

        return response()->json([
            'success' => true,
            'data' => $followups
        ]);
    }

    /**
     * Store a new followup
     */
    public function store(Request $request, $leadId)
    {
        $lead = Lead::findOrFail($leadId);

        $validator = Validator::make($request->all(), [
            'type' => 'required|in:call,email,meeting,whatsapp,other',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'scheduled_at' => 'required|date|after:now',
            'next_followup' => 'nullable|date|after:scheduled_at',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $followup = LeadFollowup::create([
            'lead_id' => $leadId,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'subject' => $request->subject,
            'description' => $request->description,
            'status' => 'scheduled',
            'scheduled_at' => $request->scheduled_at,
            'next_followup' => $request->next_followup,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Followup scheduled successfully',
            'data' => $followup->load('user')
        ], 201);
    }

    /**
     * Update followup status to completed
     */
    public function complete(Request $request, $leadId, $followupId)
    {
        $followup = LeadFollowup::where('lead_id', $leadId)
                               ->where('id', $followupId)
                               ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'outcome' => 'required|string',
            'next_followup' => 'nullable|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        $followup->update([
            'status' => 'completed',
            'completed_at' => now(),
            'outcome' => $request->outcome,
            'next_followup' => $request->next_followup,
        ]);

        // If there's a next followup date, create a new scheduled followup
        if ($request->next_followup) {
            LeadFollowup::create([
                'lead_id' => $leadId,
                'user_id' => Auth::id(),
                'type' => $followup->type,
                'subject' => 'Follow-up: ' . $followup->subject,
                'description' => 'Scheduled follow-up from previous interaction',
                'status' => 'scheduled',
                'scheduled_at' => $request->next_followup,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Followup completed successfully',
            'data' => $followup->fresh('user')
        ]);
    }

    /**
     * Cancel a scheduled followup
     */
    public function cancel($leadId, $followupId)
    {
        $followup = LeadFollowup::where('lead_id', $leadId)
                               ->where('id', $followupId)
                               ->where('status', 'scheduled')
                               ->firstOrFail();

        $followup->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Followup cancelled successfully'
        ]);
    }

    /**
     * Get today's followups
     */
    public function todaysFollowups()
    {
        $followups = LeadFollowup::with(['lead', 'user'])
                                ->today()
                                ->pending()
                                ->orderBy('scheduled_at', 'asc')
                                ->get();

        return response()->json([
            'success' => true,
            'data' => $followups
        ]);
    }

    /**
     * Get overdue followups
     */
    public function overdueFollowups()
    {
        $followups = LeadFollowup::with(['lead', 'user'])
                                ->overdue()
                                ->orderBy('scheduled_at', 'asc')
                                ->get();

        return response()->json([
            'success' => true,
            'data' => $followups
        ]);
    }

    /**
     * Get user's followups
     */
    public function myFollowups(Request $request)
    {
        $query = LeadFollowup::with(['lead', 'user'])
                            ->where('user_id', Auth::id());

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('scheduled_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('scheduled_at', '<=', $request->date_to);
        }

        $followups = $query->orderBy('scheduled_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $followups
        ]);
    }
}
