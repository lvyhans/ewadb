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
            $pendingUsers = User::pending()
                ->select(['id', 'name', 'email', 'company_name', 'created_at', 'approval_status'])
                ->orderBy('created_at', 'desc')
                ->get();

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
            $status = $request->query('status'); // approved, pending, rejected
            $perPage = $request->query('per_page', 15);

            $query = User::select([
                'id', 'name', 'email', 'company_name', 'approval_status', 
                'created_at', 'approved_at', 'approved_by', 'rejection_reason'
            ])->with('approver:id,name,email');

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
                'rejection_reason' => 'required|string|max:1000',
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
}
