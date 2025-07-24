<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadRevert;
use App\Notifications\NewLeadRevertNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class LeadRevertApiController extends Controller
{
    /**
     * Submit a new revert/remark for a lead using reference number
     */
    public function submitRevert(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ref_no' => 'required|string',
                'revert_message' => 'required|string|min:10|max:2000',
                'submitted_by' => 'required|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find lead by reference number
            $lead = Lead::where('ref_no', $request->ref_no)->first();

            if (!$lead) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lead not found with the provided reference number',
                    'ref_no' => $request->ref_no
                ], 404);
            }

            // Create new revert
            $revert = LeadRevert::create([
                'lead_id' => $lead->id,
                'ref_no' => $request->ref_no,
                'revert_message' => $request->revert_message,
                'revert_type' => 'remark', // Default to remark
                'submitted_by' => $request->submitted_by,
                'team_name' => 'External Team', // Default team name
                'priority' => 'normal', // Default priority
                'metadata' => null,
            ]);

            // Send notifications to lead user and their admin
            $this->sendRevertNotifications($lead, $revert);

            // Log the revert submission
            Log::info('New lead revert submitted', [
                'lead_id' => $lead->id,
                'ref_no' => $request->ref_no,
                'submitted_by' => $request->submitted_by,
                'team_name' => $request->team_name,
                'revert_id' => $revert->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Revert submitted successfully',
                'data' => [
                    'revert_id' => $revert->id,
                    'ref_no' => $revert->ref_no,
                    'submitted_at' => $revert->created_at->toISOString(),
                    'status' => $revert->status,
                    'priority' => $revert->priority
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error submitting lead revert', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting the revert'
            ], 500);
        }
    }

    /**
     * Get all reverts for a lead by reference number
     */
    public function getRevertsByRefNo(Request $request, $refNo)
    {
        try {
            $lead = Lead::where('ref_no', $refNo)->first();

            if (!$lead) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lead not found with the provided reference number',
                    'ref_no' => $refNo
                ], 404);
            }

            $reverts = LeadRevert::where('lead_id', $lead->id)
                ->with('resolver:id,name')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'lead' => [
                        'id' => $lead->id,
                        'ref_no' => $lead->ref_no,
                        'name' => $lead->name,
                        'status' => $lead->status
                    ],
                    'reverts' => $reverts->map(function ($revert) {
                        return [
                            'id' => $revert->id,
                            'revert_message' => $revert->revert_message,
                            'revert_type' => $revert->revert_type,
                            'submitted_by' => $revert->submitted_by,
                            'team_name' => $revert->team_name,
                            'priority' => $revert->priority,
                            'status' => $revert->status,
                            'submitted_at' => $revert->created_at->toISOString(),
                            'resolved_at' => $revert->resolved_at?->toISOString(),
                            'resolved_by' => $revert->resolver?->name,
                            'resolution_notes' => $revert->resolution_notes,
                            'is_overdue' => $revert->isOverdue(),
                            'metadata' => $revert->metadata
                        ];
                    }),
                    'statistics' => [
                        'total_reverts' => $reverts->count(),
                        'active_reverts' => $reverts->where('status', 'active')->count(),
                        'resolved_reverts' => $reverts->where('status', 'resolved')->count(),
                        'overdue_reverts' => $reverts->filter->isOverdue()->count()
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching lead reverts', [
                'error' => $e->getMessage(),
                'ref_no' => $refNo
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching reverts'
            ], 500);
        }
    }

    /**
     * Get revert status by revert ID (for external teams to track their submissions)
     */
    public function getRevertStatus($revertId)
    {
        try {
            $revert = LeadRevert::with(['lead:id,ref_no,name', 'resolver:id,name'])
                ->find($revertId);

            if (!$revert) {
                return response()->json([
                    'success' => false,
                    'message' => 'Revert not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $revert->id,
                    'lead_ref_no' => $revert->lead->ref_no,
                    'lead_name' => $revert->lead->name,
                    'revert_message' => $revert->revert_message,
                    'revert_type' => $revert->revert_type,
                    'submitted_by' => $revert->submitted_by,
                    'team_name' => $revert->team_name,
                    'priority' => $revert->priority,
                    'status' => $revert->status,
                    'submitted_at' => $revert->created_at->toISOString(),
                    'resolved_at' => $revert->resolved_at?->toISOString(),
                    'resolved_by' => $revert->resolver?->name,
                    'resolution_notes' => $revert->resolution_notes,
                    'is_overdue' => $revert->isOverdue(),
                    'metadata' => $revert->metadata
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching revert status', [
                'error' => $e->getMessage(),
                'revert_id' => $revertId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching revert status'
            ], 500);
        }
    }

    /**
     * Bulk submit multiple reverts for different leads
     */
    public function bulkSubmitReverts(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'reverts' => 'required|array|min:1|max:50',
                'reverts.*.ref_no' => 'required|string',
                'reverts.*.revert_message' => 'required|string|min:10|max:2000',
                'reverts.*.submitted_by' => 'required|string|max:100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $results = [];
            $successCount = 0;
            $errorCount = 0;

            foreach ($request->reverts as $index => $revertData) {
                try {
                    $lead = Lead::where('ref_no', $revertData['ref_no'])->first();

                    if (!$lead) {
                        $results[] = [
                            'index' => $index,
                            'ref_no' => $revertData['ref_no'],
                            'success' => false,
                            'message' => 'Lead not found'
                        ];
                        $errorCount++;
                        continue;
                    }

                    $revert = LeadRevert::create([
                        'lead_id' => $lead->id,
                        'ref_no' => $revertData['ref_no'],
                        'revert_message' => $revertData['revert_message'],
                        'revert_type' => 'remark', // Default to remark
                        'submitted_by' => $revertData['submitted_by'],
                        'team_name' => 'External Team', // Default team name
                        'priority' => 'normal', // Default priority
                        'metadata' => null,
                    ]);

                    // Send notifications to lead user and their admin
                    $this->sendRevertNotifications($lead, $revert);

                    $results[] = [
                        'index' => $index,
                        'ref_no' => $revertData['ref_no'],
                        'success' => true,
                        'revert_id' => $revert->id,
                        'message' => 'Revert submitted successfully'
                    ];
                    $successCount++;

                } catch (\Exception $e) {
                    $results[] = [
                        'index' => $index,
                        'ref_no' => $revertData['ref_no'] ?? 'unknown',
                        'success' => false,
                        'message' => 'Error: ' . $e->getMessage()
                    ];
                    $errorCount++;
                }
            }

            return response()->json([
                'success' => $errorCount === 0,
                'message' => "Processed {$successCount} successful, {$errorCount} failed",
                'summary' => [
                    'total_processed' => count($request->reverts),
                    'successful' => $successCount,
                    'failed' => $errorCount
                ],
                'results' => $results
            ], $errorCount === 0 ? 201 : 207); // 207 = Multi-Status

        } catch (\Exception $e) {
            Log::error('Error in bulk revert submission', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during bulk revert submission'
            ], 500);
        }
    }

    /**
     * Send notifications for new revert to lead user and admin
     */
    private function sendRevertNotifications(Lead $lead, LeadRevert $revert)
    {
        try {
            $usersToNotify = collect();

            Log::info('Starting notification process', [
                'lead_id' => $lead->id,
                'revert_id' => $revert->id,
                'lead_assigned_to' => $lead->assigned_to,
                'lead_created_by' => $lead->created_by
            ]);

            // Add the assigned user if exists
            if ($lead->assigned_to && $lead->assignedUser) {
                $usersToNotify->push($lead->assignedUser);
                Log::info('Added assigned user to notifications', [
                    'user_id' => $lead->assignedUser->id,
                    'user_name' => $lead->assignedUser->name
                ]);
            }

            // Add the lead creator if exists and different from assigned user
            if ($lead->created_by && $lead->creator && $lead->created_by !== $lead->assigned_to) {
                $usersToNotify->push($lead->creator);
                Log::info('Added creator to notifications', [
                    'user_id' => $lead->creator->id,
                    'user_name' => $lead->creator->name
                ]);
            }

            // Add admins of the users
            $usersToNotify->each(function ($user) use ($usersToNotify) {
                if ($user->admin_id && $user->admin) {
                    // Add admin if not already in the collection
                    if (!$usersToNotify->contains('id', $user->admin->id)) {
                        $usersToNotify->push($user->admin);
                        Log::info('Added admin to notifications', [
                            'admin_id' => $user->admin->id,
                            'admin_name' => $user->admin->name,
                            'for_user' => $user->id
                        ]);
                    }
                }
            });

            Log::info('Users to notify collected', [
                'total_users' => $usersToNotify->unique('id')->count(),
                'user_ids' => $usersToNotify->unique('id')->pluck('id')->toArray()
            ]);

            // Send notifications to all users
            $usersToNotify->unique('id')->each(function ($user) use ($lead, $revert) {
                try {
                    Log::info('Sending notification to user', [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'lead_id' => $lead->id,
                        'revert_id' => $revert->id
                    ]);
                    
                    $user->notify(new NewLeadRevertNotification($lead, $revert));
                    
                    Log::info('Notification sent successfully', [
                        'user_id' => $user->id,
                        'lead_id' => $lead->id,
                        'revert_id' => $revert->id
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Failed to send notification to user', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                        'lead_id' => $lead->id,
                        'revert_id' => $revert->id
                    ]);
                }
            });

            Log::info('Revert notifications sent', [
                'lead_id' => $lead->id,
                'revert_id' => $revert->id,
                'notified_users' => $usersToNotify->unique('id')->pluck('id')->toArray()
            ]);

        } catch (\Exception $e) {
            Log::error('Error sending revert notifications', [
                'error' => $e->getMessage(),
                'lead_id' => $lead->id,
                'revert_id' => $revert->id
            ]);
        }
    }
}
