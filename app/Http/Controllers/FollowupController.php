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
     */
    public function index()
    {
        $followups = LeadFollowup::with(['lead', 'user'])
            ->orderBy('scheduled_at', 'desc')
            ->paginate(20);
            
        return view('followups.index', compact('followups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $leads = Lead::select('id', 'name', 'ref_no')->get();
        return view('followups.create', compact('leads'));
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
}
