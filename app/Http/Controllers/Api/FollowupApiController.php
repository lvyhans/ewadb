<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeadFollowup;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FollowupApiController extends Controller
{
    /**
     * Get a specific followup for editing
     */
    public function show($id)
    {
        try {
            $followup = LeadFollowup::with(['lead', 'user'])->findOrFail($id);
            
            // Format the data for frontend
            $data = [
                'id' => $followup->id,
                'type' => $followup->type,
                'subject' => $followup->subject,
                'description' => $followup->description,
                'notes' => $followup->notes,
                'priority' => $followup->priority ?? 'medium',
                'scheduled_date' => $followup->scheduled_at->format('Y-m-d'),
                'scheduled_time' => $followup->scheduled_at->format('H:i'),
                'status' => $followup->status,
                'lead' => [
                    'id' => $followup->lead->id,
                    'name' => $followup->lead->name,
                    'ref_no' => $followup->lead->ref_no
                ]
            ];

            return response()->json([
                'success' => true,
                'followup' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Follow-up not found'
            ], 404);
        }
    }

    /**
     * Update a followup
     */
    public function update(Request $request, $id)
    {
        try {
            $followup = LeadFollowup::findOrFail($id);
            
            $request->validate([
                'type' => 'required|in:call,email,meeting,visit,document,other',
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
                'scheduled_date' => 'required|date',
                'scheduled_time' => 'required',
                'priority' => 'required|in:low,medium,high,urgent',
                'notes' => 'nullable|string'
            ]);

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
     * Mark followup as completed
     */
    public function complete($id)
    {
        try {
            $followup = LeadFollowup::findOrFail($id);
            
            $followup->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Follow-up marked as completed'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error completing follow-up: ' . $e->getMessage()
            ], 500);
        }
    }
}
