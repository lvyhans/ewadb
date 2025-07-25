<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadRevert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeadRevertController extends Controller
{
    /**
     * Get all reverts for a specific lead
     */
    public function index(Request $request, $leadId)
    {
        try {
            $user = Auth::user();
            $lead = Lead::accessibleByUser($user)->findOrFail($leadId);
            
            $reverts = LeadRevert::where('lead_id', $leadId)
                ->with(['resolver:id,name'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return response()->json([
                'success' => true,
                'data' => [
                    'lead' => [
                        'id' => $lead->id,
                        'ref_no' => $lead->ref_no,
                        'name' => $lead->name,
                        'status' => $lead->status
                    ],
                    'reverts' => $reverts
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching reverts'
            ], 500);
        }
    }

    /**
     * Get all active reverts across all leads (for dashboard)
     */
    public function getAllActiveReverts(Request $request)
    {
        try {
            $user = Auth::user();
            $query = LeadRevert::with(['lead:id,ref_no,name,status', 'resolver:id,name'])
                ->whereHas('lead', function($leadQuery) use ($user) {
                    $leadQuery->accessibleByUser($user);
                })
                ->where('status', 'active');

            // Apply filters
            if ($request->has('priority')) {
                $query->where('priority', $request->priority);
            }

            if ($request->has('revert_type')) {
                $query->where('revert_type', $request->revert_type);
            }

            if ($request->has('team_name')) {
                $query->where('team_name', 'like', '%' . $request->team_name . '%');
            }

            if ($request->has('overdue_only') && $request->overdue_only) {
                // Filter for overdue reverts (this would need to be implemented in database query)
                $query->where(function($q) {
                    $q->where('priority', 'high')->where('created_at', '<', now()->subDays(2))
                      ->orWhere('priority', 'normal')->where('created_at', '<', now()->subDays(7));
                });
            }

            $reverts = $query->orderBy('priority', 'desc')
                ->orderBy('created_at', 'asc')
                ->paginate(50);

            return response()->json([
                'success' => true,
                'data' => $reverts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching active reverts'
            ], 500);
        }
    }

    /**
     * Resolve a revert
     */
    public function resolve(Request $request, $revertId)
    {
        try {
            $user = Auth::user();
            $validator = Validator::make($request->all(), [
                'resolution_notes' => 'nullable|string|max:1000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if revert belongs to an accessible lead
            $revert = LeadRevert::whereHas('lead', function($leadQuery) use ($user) {
                $leadQuery->accessibleByUser($user);
            })->findOrFail($revertId);

            if ($revert->status === 'resolved') {
                return response()->json([
                    'success' => false,
                    'message' => 'This revert has already been resolved'
                ], 400);
            }

            $revert->markAsResolved(Auth::id(), $request->resolution_notes);

            return response()->json([
                'success' => true,
                'message' => 'Revert resolved successfully',
                'data' => [
                    'id' => $revert->id,
                    'status' => $revert->status,
                    'resolved_at' => $revert->resolved_at,
                    'resolved_by' => Auth::user()->name,
                    'resolution_notes' => $revert->resolution_notes
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error resolving revert'
            ], 500);
        }
    }

    /**
     * Reopen a resolved revert
     */
    public function reopen($revertId)
    {
        try {
            $user = Auth::user();
            // Check if revert belongs to an accessible lead
            $revert = LeadRevert::whereHas('lead', function($leadQuery) use ($user) {
                $leadQuery->accessibleByUser($user);
            })->findOrFail($revertId);

            if ($revert->status !== 'resolved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only resolved reverts can be reopened'
                ], 400);
            }

            $revert->update([
                'status' => 'active',
                'resolved_at' => null,
                'resolved_by' => null,
                'resolution_notes' => null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Revert reopened successfully',
                'data' => [
                    'id' => $revert->id,
                    'status' => $revert->status
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error reopening revert'
            ], 500);
        }
    }

    /**
     * Archive a revert
     */
    public function archive($revertId)
    {
        try {
            $user = Auth::user();
            // Check if revert belongs to an accessible lead
            $revert = LeadRevert::whereHas('lead', function($leadQuery) use ($user) {
                $leadQuery->accessibleByUser($user);
            })->findOrFail($revertId);

            $revert->update(['status' => 'archived']);

            return response()->json([
                'success' => true,
                'message' => 'Revert archived successfully',
                'data' => [
                    'id' => $revert->id,
                    'status' => $revert->status
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error archiving revert'
            ], 500);
        }
    }

    /**
     * Get revert statistics
     */
    public function getStatistics()
    {
        try {
            $user = Auth::user();
            
            // Base query for accessible reverts
            $baseRevertQuery = LeadRevert::whereHas('lead', function($leadQuery) use ($user) {
                $leadQuery->accessibleByUser($user);
            });
            
            $stats = [
                'total_reverts' => $baseRevertQuery->count(),
                'active_reverts' => $baseRevertQuery->where('status', 'active')->count(),
                'resolved_reverts' => $baseRevertQuery->where('status', 'resolved')->count(),
                'archived_reverts' => $baseRevertQuery->where('status', 'archived')->count(),
                'high_priority_active' => $baseRevertQuery->where('status', 'active')->where('priority', 'high')->count(),
                'urgent_priority_active' => $baseRevertQuery->where('status', 'active')->where('priority', 'urgent')->count(),
                'overdue_reverts' => $baseRevertQuery->where('status', 'active')
                    ->where(function($query) {
                        $query->where('priority', 'high')->where('created_at', '<', now()->subDays(2))
                              ->orWhere('priority', 'normal')->where('created_at', '<', now()->subDays(7));
                    })->count(),
                'reverts_by_type' => $baseRevertQuery->selectRaw('revert_type, count(*) as count')
                    ->groupBy('revert_type')
                    ->pluck('count', 'revert_type'),
                'reverts_by_team' => $baseRevertQuery->selectRaw('team_name, count(*) as count')
                    ->groupBy('team_name')
                    ->orderBy('count', 'desc')
                    ->limit(10)
                    ->pluck('count', 'team_name'),
                'recent_activity' => $baseRevertQuery->with(['lead:id,ref_no,name'])
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function($revert) {
                        return [
                            'id' => $revert->id,
                            'lead_ref_no' => $revert->lead->ref_no,
                            'lead_name' => $revert->lead->name,
                            'revert_type' => $revert->revert_type,
                            'team_name' => $revert->team_name,
                            'priority' => $revert->priority,
                            'status' => $revert->status,
                            'created_at' => $revert->created_at
                        ];
                    })
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching statistics'
            ], 500);
        }
    }
}
