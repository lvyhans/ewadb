<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UserApprovalApiController extends Controller
{
    /**
     * Get all pending users for approval
     */
    public function getPendingUsers(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $page = $request->get('page', 1);

            $users = User::where('approval_status', 'pending')
                ->with('roles')
                ->select([
                    'id', 'name', 'email', 'company_name', 'company_registration_number',
                    'gstin', 'pan_number', 'company_phone', 'company_email',
                    'company_city', 'company_state', 'company_pincode',
                    'company_address', 'approval_status', 'created_at'
                ])
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'status' => 'success',
                'message' => 'Pending users retrieved successfully',
                'data' => [
                    'users' => $users->items(),
                    'pagination' => [
                        'current_page' => $users->currentPage(),
                        'last_page' => $users->lastPage(),
                        'per_page' => $users->perPage(),
                        'total' => $users->total(),
                        'from' => $users->firstItem(),
                        'to' => $users->lastItem()
                    ]
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
     * Get user details by ID
     */
    public function getUserDetails(Request $request, $userId): JsonResponse
    {
        try {
            $user = User::with('roles')->find($userId);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }

            // Get document status
            $documents = [
                'company_registration_certificate' => [
                    'uploaded' => !empty($user->company_registration_certificate),
                    'file_path' => $user->company_registration_certificate
                ],
                'gst_certificate' => [
                    'uploaded' => !empty($user->gst_certificate),
                    'file_path' => $user->gst_certificate
                ],
                'pan_card' => [
                    'uploaded' => !empty($user->pan_card),
                    'file_path' => $user->pan_card
                ],
                'address_proof' => [
                    'uploaded' => !empty($user->address_proof),
                    'file_path' => $user->address_proof
                ],
                'bank_statement' => [
                    'uploaded' => !empty($user->bank_statement),
                    'file_path' => $user->bank_statement
                ]
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'User details retrieved successfully',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'company_name' => $user->company_name,
                        'company_registration_number' => $user->company_registration_number,
                        'gstin' => $user->gstin,
                        'pan_number' => $user->pan_number,
                        'company_phone' => $user->company_phone,
                        'company_email' => $user->company_email,
                        'company_address' => $user->company_address,
                        'company_city' => $user->company_city,
                        'company_state' => $user->company_state,
                        'company_pincode' => $user->company_pincode,
                        'approval_status' => $user->approval_status,
                        'rejection_reason' => $user->rejection_reason,
                        'approved_at' => $user->approved_at,
                        'approved_by' => $user->approved_by,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                        'roles' => $user->roles->pluck('name')
                    ],
                    'documents' => $documents
                ]
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
            // No validation needed for approved_by - we'll use auth()->id()

            $user = User::find($userId);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }

            if ($user->approval_status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is not pending approval',
                    'current_status' => $user->approval_status
                ], 400);
            }

            DB::beginTransaction();

            $user->update([
                'approval_status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'rejection_reason' => null
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'User approved successfully',
                'data' => [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'approval_status' => $user->approval_status,
                    'approved_at' => $user->approved_at,
                    'approved_by' => $user->approved_by
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to approve user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject/Disapprove a user
     */
    public function rejectUser(Request $request, $userId): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'rejection_reason' => 'required|string|max:500'
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

            if ($user->approval_status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User is not pending approval',
                    'current_status' => $user->approval_status
                ], 400);
            }

            DB::beginTransaction();

            $user->update([
                'approval_status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'approved_by' => auth()->id(),
                'approved_at' => null
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'User rejected successfully',
                'data' => [
                    'user_id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'approval_status' => $user->approval_status,
                    'rejection_reason' => $user->rejection_reason,
                    'approved_by' => $user->approved_by
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to reject user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk approve/reject users
     */
    public function bulkUpdateUsers(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'action' => 'required|in:approve,reject',
                'user_ids' => 'required|array|min:1',
                'user_ids.*' => 'integer|exists:users,id',
                'rejection_reason' => 'required_if:action,reject|string|max:500'
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

            // Check if all users are pending
            $pendingUsers = User::whereIn('id', $userIds)
                ->where('approval_status', 'pending')
                ->count();

            if ($pendingUsers !== count($userIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Some users are not pending approval'
                ], 400);
            }

            DB::beginTransaction();

            if ($action === 'approve') {
                $updateData = [
                    'approval_status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                    'rejection_reason' => null
                ];
            } else {
                $updateData = [
                    'approval_status' => 'rejected',
                    'rejection_reason' => $request->rejection_reason,
                    'approved_by' => auth()->id(),
                    'approved_at' => null
                ];
            }

            $updatedCount = User::whereIn('id', $userIds)->update($updateData);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "Successfully {$action}d {$updatedCount} users",
                'data' => [
                    'action' => $action,
                    'updated_count' => $updatedCount,
                    'user_ids' => $userIds
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to bulk update users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get approval statistics
     */
    public function getApprovalStats(Request $request): JsonResponse
    {
        try {
            $stats = [
                'pending_count' => User::where('approval_status', 'pending')->count(),
                'approved_count' => User::where('approval_status', 'approved')->count(),
                'rejected_count' => User::where('approval_status', 'rejected')->count(),
                'total_registrations' => User::whereNotNull('approval_status')->count(),
            ];

            $stats['approval_rate'] = $stats['total_registrations'] > 0 
                ? round(($stats['approved_count'] / $stats['total_registrations']) * 100, 2) 
                : 0;

            // Recent registrations (last 7 days)
            $recentRegistrations = User::where('created_at', '>=', now()->subDays(7))
                ->whereNotNull('approval_status')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->get();

            return response()->json([
                'status' => 'success',
                'message' => 'Approval statistics retrieved successfully',
                'data' => [
                    'statistics' => $stats,
                    'recent_registrations' => $recentRegistrations
                ]
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
