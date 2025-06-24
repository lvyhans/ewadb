<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserApprovalController extends Controller
{
    /**
     * Get all pending users
     */
    public function getPendingUsers(): JsonResponse
    {
        try {
            $currentUser = auth()->user();
            $query = User::pending()
                ->select(['id', 'name', 'email', 'company_name', 'created_at', 'approval_status', 'admin_id']);
            
            // Apply admin hierarchy filtering
            if ($currentUser->isSuperAdmin()) {
                // Super admin can see all users except themselves
                $query->where('id', '!=', $currentUser->id);
            } elseif ($currentUser->isRegularAdmin()) {
                // Regular admins can only see their own members
                $query->where('admin_id', $currentUser->id);
            } else {
                // Members can only see themselves
                $query->where('id', $currentUser->id);
            }
            
            $pendingUsers = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Pending users retrieved successfully',
                'data' => [
                    'users' => $pendingUsers,
                    'count' => $pendingUsers->count()
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve pending users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all users with approval status
     */
    public function getAllUsers(Request $request): JsonResponse
    {
        try {
            $currentUser = auth()->user();
            $status = $request->query('status'); // approved, pending, rejected
            $perPage = $request->query('per_page', 15);

            $query = User::select([
                'id', 'name', 'email', 'company_name', 'approval_status', 
                'created_at', 'approved_at', 'approved_by', 'rejection_reason', 'admin_id'
            ])->with('approver:id,name,email');

            // Apply admin hierarchy filtering
            if ($currentUser->isSuperAdmin()) {
                // Super admin can see all users except themselves
                $query->where('id', '!=', $currentUser->id);
            } elseif ($currentUser->isRegularAdmin()) {
                // Regular admins can only see their own members
                $query->where('admin_id', $currentUser->id);
            } else {
                // Members can only see themselves
                $query->where('id', $currentUser->id);
            }

            if ($status && in_array($status, ['approved', 'pending', 'rejected'])) {
                $query->where('approval_status', $status);
            }

            $users = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'message' => 'Users retrieved successfully',
                'data' => $users
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific user details
     */
    public function getUserDetails($userId): JsonResponse
    {
        try {
            $user = User::with('approver:id,name,email')
                ->select([
                    'id', 'name', 'email', 'company_name', 'company_registration_number',
                    'gstin', 'pan_number', 'company_address', 'company_city', 'company_state',
                    'company_pincode', 'company_phone', 'company_email', 'approval_status',
                    'created_at', 'approved_at', 'approved_by', 'rejection_reason'
                ])
                ->find($userId);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User details retrieved successfully',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve user details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a user
     */
    public function approveUser(Request $request, $userId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'approved_by' => 'required|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }

            if ($user->approval_status === 'approved') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is already approved'
                ], 400);
            }

            $user->update([
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $request->approved_by,
                'rejection_reason' => null
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User approved successfully',
                'data' => [
                    'user_id' => $user->id,
                    'approval_status' => $user->approval_status,
                    'approved_at' => $user->approved_at,
                    'approved_by' => $user->approved_by
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to approve user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a user
     */
    public function rejectUser(Request $request, $userId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'rejection_reason' => 'nullable|string|max:1000',
                'rejected_by' => 'required|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }

            $user->update([
                'approval_status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'approved_at' => null,
                'approved_by' => $request->rejected_by
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User rejected successfully',
                'data' => [
                    'user_id' => $user->id,
                    'approval_status' => $user->approval_status,
                    'rejection_reason' => $user->rejection_reason,
                    'rejected_by' => $user->approved_by
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to reject user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user status to pending (reset approval)
     */
    public function setPendingStatus(Request $request, $userId): JsonResponse
    {
        try {
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }

            $user->update([
                'approval_status' => 'pending',
                'approved_at' => null,
                'approved_by' => null,
                'rejection_reason' => null
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'User status set to pending successfully',
                'data' => [
                    'user_id' => $user->id,
                    'approval_status' => $user->approval_status
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update user status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk approve users
     */
    public function bulkApproveUsers(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_ids' => 'required|array|min:1',
                'user_ids.*' => 'integer|exists:users,id',
                'approved_by' => 'required|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updated = User::whereIn('id', $request->user_ids)
                ->where('approval_status', '!=', 'approved')
                ->update([
                    'approval_status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => $request->approved_by,
                    'rejection_reason' => null
                ]);

            return response()->json([
                'status' => 'success',
                'message' => "Successfully approved {$updated} users",
                'data' => [
                    'approved_count' => $updated,
                    'requested_count' => count($request->user_ids)
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to bulk approve users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get approval statistics
     */
    public function getApprovalStats(): JsonResponse
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'approved_users' => User::approved()->count(),
                'pending_users' => User::pending()->count(),
                'rejected_users' => User::rejected()->count(),
                'approval_rate' => 0
            ];

            if ($stats['total_users'] > 0) {
                $stats['approval_rate'] = round(($stats['approved_users'] / $stats['total_users']) * 100, 2);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Approval statistics retrieved successfully',
                'data' => $stats
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve approval statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enhanced single user approval for third-party integration
     */
    public function enhancedApproveUser(Request $request, $userId): JsonResponse
    {
        try {
            $currentUser = auth()->user();
            
            // Validate request data
            $validator = Validator::make($request->all(), [
                'notes' => 'nullable|string|max:500',
                'auto_assign_admin' => 'boolean',
                'approved_by' => 'nullable|integer|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::findOrFail($userId);

            // Apply admin hierarchy filtering
            if (!$currentUser->isSuperAdmin()) {
                if ($currentUser->isRegularAdmin()) {
                    // Regular admins can only approve their own members
                    if ($user->admin_id !== $currentUser->id) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'You can only approve users assigned to you',
                            'code' => 'ACCESS_DENIED'
                        ], 403);
                    }
                } else {
                    // Members cannot approve users
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You do not have permission to approve users',
                        'code' => 'ACCESS_DENIED'
                    ], 403);
                }
            }

            if ($user->approval_status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is not in pending status',
                    'code' => 'INVALID_STATUS'
                ], 400);
            }

            // Auto-assign admin if requested and user doesn't have one
            if ($request->auto_assign_admin && !$user->admin_id) {
                $user->admin_id = $currentUser->isSuperAdmin() ? $currentUser->id : $currentUser->admin_id;
            }

            // Update user approval status
            $user->update([
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $request->approved_by ?: $currentUser->id,
                'rejection_reason' => null
            ]);

            // Get updated user data with relationships
            $user->refresh();
            $user->load(['roles', 'admin', 'approver']);

            return response()->json([
                'status' => 'success',
                'message' => 'User approved successfully',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'company_name' => $user->company_name,
                        'approval_status' => $user->approval_status,
                        'approved_at' => $user->approved_at,
                        'approved_by' => $user->approved_by,
                        'approver' => $user->approver ? [
                            'id' => $user->approver->id,
                            'name' => $user->approver->name,
                            'email' => $user->approver->email
                        ] : null,
                        'admin' => $user->admin ? [
                            'id' => $user->admin->id,
                            'name' => $user->admin->name,
                            'group_name' => $user->admin_group_name
                        ] : null,
                        'roles' => $user->roles->map(function($role) {
                            return [
                                'id' => $role->id,
                                'name' => $role->name,
                                'display_name' => $role->display_name
                            ];
                        })
                    ],
                    'metadata' => [
                        'processed_at' => now()->toISOString(),
                        'processed_by' => $currentUser->id,
                        'notes' => $request->notes,
                        'auto_assigned_admin' => $request->auto_assign_admin && !$user->admin_id
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to approve user',
                'error' => $e->getMessage(),
                'code' => 'APPROVAL_FAILED'
            ], 500);
        }
    }

    /**
     * Enhanced single user rejection for third-party integration
     */
    public function enhancedRejectUser(Request $request, $userId): JsonResponse
    {
        try {
            $currentUser = auth()->user();
            
            // Validate request data
            $validator = Validator::make($request->all(), [
                'rejection_reason' => 'nullable|string|max:500',
                'notes' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::findOrFail($userId);

            // Apply admin hierarchy filtering
            if (!$currentUser->isSuperAdmin()) {
                if ($currentUser->isRegularAdmin()) {
                    // Regular admins can only reject their own members
                    if ($user->admin_id !== $currentUser->id) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'You can only reject users assigned to you',
                            'code' => 'ACCESS_DENIED'
                        ], 403);
                    }
                } else {
                    // Members cannot reject users
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You do not have permission to reject users',
                        'code' => 'ACCESS_DENIED'
                    ], 403);
                }
            }

            if ($user->approval_status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is not in pending status',
                    'code' => 'INVALID_STATUS'
                ], 400);
            }

            // Update user rejection status
            $user->update([
                'approval_status' => 'rejected',
                'approved_at' => null,
                'approved_by' => null,
                'rejection_reason' => $request->rejection_reason
            ]);

            // Get updated user data with relationships
            $user->refresh();
            $user->load(['roles', 'admin']);

            return response()->json([
                'status' => 'success',
                'message' => 'User rejected successfully',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'company_name' => $user->company_name,
                        'approval_status' => $user->approval_status,
                        'rejection_reason' => $user->rejection_reason,
                        'admin' => $user->admin ? [
                            'id' => $user->admin->id,
                            'name' => $user->admin->name,
                            'group_name' => $user->admin_group_name
                        ] : null,
                        'roles' => $user->roles->map(function($role) {
                            return [
                                'id' => $role->id,
                                'name' => $role->name,
                                'display_name' => $role->display_name
                            ];
                        })
                    ],
                    'metadata' => [
                        'processed_at' => now()->toISOString(),
                        'processed_by' => $currentUser->id,
                        'notes' => $request->notes
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to reject user',
                'error' => $e->getMessage(),
                'code' => 'REJECTION_FAILED'
            ], 500);
        }
    }

    /**
     * Bulk user actions for third-party integration
     */
    public function bulkUserActions(Request $request): JsonResponse
    {
        try {
            $currentUser = auth()->user();
            
            // Validate request data
            $validator = Validator::make($request->all(), [
                'action' => 'required|in:approve,reject',
                'user_ids' => 'required|array|min:1|max:100',
                'user_ids.*' => 'integer|exists:users,id',
                'notes' => 'nullable|string|max:500',
                'rejection_reason' => 'nullable|string|max:500',
                'auto_assign_admin' => 'boolean'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $userIds = $request->user_ids;
            $action = $request->action;
            
            // Get users and apply admin hierarchy filtering
            $query = User::whereIn('id', $userIds)->where('approval_status', 'pending');
            
            if (!$currentUser->isSuperAdmin()) {
                if ($currentUser->isRegularAdmin()) {
                    // Regular admins can only process their own members
                    $query->where('admin_id', $currentUser->id);
                } else {
                    // Members cannot process users
                    return response()->json([
                        'status' => 'error',
                        'message' => 'You do not have permission to perform bulk actions',
                        'code' => 'ACCESS_DENIED'
                    ], 403);
                }
            }

            $users = $query->get();

            if ($users->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No eligible users found for bulk action',
                    'code' => 'NO_USERS_FOUND'
                ], 404);
            }

            $results = [
                'successful' => [],
                'failed' => [],
                'summary' => [
                    'total_requested' => count($userIds),
                    'processed' => 0,
                    'successful' => 0,
                    'failed' => 0
                ]
            ];

            foreach ($users as $user) {
                try {
                    if ($action === 'approve') {
                        // Auto-assign admin if requested and user doesn't have one
                        if ($request->auto_assign_admin && !$user->admin_id) {
                            $user->admin_id = $currentUser->isSuperAdmin() ? $currentUser->id : $currentUser->admin_id;
                        }

                        $user->update([
                            'approval_status' => 'approved',
                            'approved_at' => now(),
                            'approved_by' => $currentUser->id,
                            'rejection_reason' => null
                        ]);
                    } else {
                        $user->update([
                            'approval_status' => 'rejected',
                            'approved_at' => null,
                            'approved_by' => null,
                            'rejection_reason' => $request->rejection_reason
                        ]);
                    }

                    $results['successful'][] = [
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'new_status' => $user->approval_status
                    ];
                    $results['summary']['successful']++;

                } catch (\Exception $e) {
                    $results['failed'][] = [
                        'user_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'error' => $e->getMessage()
                    ];
                    $results['summary']['failed']++;
                }
                $results['summary']['processed']++;
            }

            $statusCode = $results['summary']['failed'] > 0 ? 207 : 200; // 207 for partial success

            return response()->json([
                'status' => $results['summary']['failed'] === 0 ? 'success' : 'partial_success',
                'message' => sprintf(
                    'Bulk %s completed: %d successful, %d failed',
                    $action,
                    $results['summary']['successful'],
                    $results['summary']['failed']
                ),
                'data' => $results,
                'metadata' => [
                    'action' => $action,
                    'processed_at' => now()->toISOString(),
                    'processed_by' => $currentUser->id,
                    'notes' => $request->notes
                ]
            ], $statusCode);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to perform bulk action',
                'error' => $e->getMessage(),
                'code' => 'BULK_ACTION_FAILED'
            ], 500);
        }
    }
}
