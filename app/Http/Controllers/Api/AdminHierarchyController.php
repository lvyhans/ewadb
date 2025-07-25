<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AdminHierarchyController extends Controller
{
    /**
     * Get all admins with their members and complete details
     * 
     * @return JsonResponse
     */
    public function getAllAdmins(): JsonResponse
    {
        try {
            $currentUser = auth()->user();
            
            // Only super admin can access this endpoint
            if (!$currentUser->isSuperAdmin()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. Only super administrators can access this endpoint.',
                    'code' => 'ACCESS_DENIED'
                ], 403);
            }
            
            // Get all admin users with their members and complete details
            $admins = User::whereHas('roles', function($q) {
                    $q->where('name', 'admin');
                })
                ->where('id', '!=', $currentUser->id) // Exclude super admin
                ->with([
                    'roles',
                    'members' => function($query) {
                        $query->with('roles');
                    }
                ])
                ->get()
                ->map(function($admin) {
                    return [
                        'admin_id' => $admin->id,
                        'admin_details' => [
                            'id' => $admin->id,
                            'name' => $admin->name,
                            'email' => $admin->email,
                            'company_name' => $admin->company_name,
                            'company_phone' => $admin->company_phone,
                            'company_email' => $admin->company_email,
                            'city' => $admin->city,
                            'state' => $admin->state,
                            'approval_status' => $admin->approval_status,
                            'created_at' => $admin->created_at,
                            'updated_at' => $admin->updated_at,
                            'is_super_admin' => $admin->isSuperAdmin(),
                            'is_regular_admin' => $admin->isRegularAdmin(),
                            'member_count' => $admin->members->count(),
                            'roles' => $admin->roles->map(function($role) {
                                return [
                                    'id' => $role->id,
                                    'name' => $role->name,
                                    'display_name' => $role->display_name
                                ];
                            })
                        ],
                        'group_name' => $admin->name . "'s Group",
                        'members' => $admin->members->map(function($member) {
                            return [
                                'id' => $member->id,
                                'name' => $member->name,
                                'email' => $member->email,
                                'company_name' => $member->company_name,
                                'company_registration_number' => $member->company_registration_number,
                                'gstin' => $member->gstin,
                                'pan_number' => $member->pan_number,
                                'company_address' => $member->company_address,
                                'company_city' => $member->company_city,
                                'company_state' => $member->company_state,
                                'company_pincode' => $member->company_pincode,
                                'company_phone' => $member->company_phone,
                                'company_email' => $member->company_email,
                                'admin_id' => $member->admin_id,
                                'admin_group_name' => $member->admin_group_name,
                                'approval_status' => $member->approval_status,
                                'approved_at' => $member->approved_at,
                                'created_at' => $member->created_at,
                                'updated_at' => $member->updated_at,
                                'roles' => $member->roles->map(function($role) {
                                    return [
                                        'id' => $role->id,
                                        'name' => $role->name,
                                        'display_name' => $role->display_name
                                    ];
                                }),
                                'documents' => [
                                    'company_registration_certificate' => $member->company_registration_certificate,
                                    'gst_certificate' => $member->gst_certificate,
                                    'pan_card' => $member->pan_card,
                                    'address_proof' => $member->address_proof,
                                    'bank_statement' => $member->bank_statement
                                ]
                            ];
                        })->toArray(),
                        'statistics' => [
                            'total_members' => $admin->members->count(),
                            'approved_members' => $admin->members->where('approval_status', 'approved')->count(),
                            'pending_members' => $admin->members->where('approval_status', 'pending')->count(),
                            'rejected_members' => $admin->members->where('approval_status', 'rejected')->count()
                        ]
                    ];
                });
            
            // Get overall system statistics
            $systemStats = [
                'total_admins' => $admins->count(),
                'total_members' => $admins->sum(function($admin) {
                    return count($admin['members']);
                }),
                'total_approved_members' => $admins->sum(function($admin) {
                    return $admin['statistics']['approved_members'];
                }),
                'total_pending_members' => $admins->sum(function($admin) {
                    return $admin['statistics']['pending_members'];
                }),
                'total_rejected_members' => $admins->sum(function($admin) {
                    return $admin['statistics']['rejected_members'];
                })
            ];
            
            return response()->json([
                'status' => 'success',
                'message' => 'Admin hierarchy retrieved successfully',
                'data' => [
                    'system_statistics' => $systemStats,
                    'admin_hierarchy' => $admins->toArray(),
                    'metadata' => [
                        'total_admin_groups' => $admins->count(),
                        'retrieved_at' => now()->toISOString(),
                        'api_version' => '1.0'
                    ]
                ]
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve admin hierarchy',
                'error' => $e->getMessage(),
                'code' => 'HIERARCHY_FETCH_FAILED'
            ], 500);
        }
    }
    
    /**
     * Get specific admin with members
     * 
     * @param int $adminId
     * @return JsonResponse
     */
    public function getAdminWithMembers($adminId): JsonResponse
    {
        try {
            $currentUser = auth()->user();
            
            // Authorization check
            if (!$currentUser->isSuperAdmin() && $currentUser->id != $adminId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. You can only view your own group.',
                    'code' => 'ACCESS_DENIED'
                ], 403);
            }
            
            $admin = User::whereHas('roles', function($q) {
                    $q->where('name', 'admin');
                })
                ->with(['roles', 'members.roles'])
                ->find($adminId);
            
            if (!$admin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Admin not found',
                    'code' => 'ADMIN_NOT_FOUND'
                ], 404);
            }
            
            $adminData = [
                'admin_id' => $admin->id,
                'admin_details' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'company_name' => $admin->company_name,
                    'company_phone' => $admin->company_phone,
                    'company_email' => $admin->company_email,
                    'city' => $admin->city,
                    'state' => $admin->state,
                    'approval_status' => $admin->approval_status,
                    'created_at' => $admin->created_at,
                    'updated_at' => $admin->updated_at,
                    'is_super_admin' => $admin->isSuperAdmin(),
                    'is_regular_admin' => $admin->isRegularAdmin(),
                    'member_count' => $admin->members->count(),
                    'roles' => $admin->roles->map(function($role) {
                        return [
                            'id' => $role->id,
                            'name' => $role->name,
                            'display_name' => $role->display_name
                        ];
                    })
                ],
                'group_name' => $admin->name . "'s Group",
                'members' => $admin->members->map(function($member) {
                    return [
                        'id' => $member->id,
                        'name' => $member->name,
                        'email' => $member->email,
                        'company_name' => $member->company_name,
                        'company_registration_number' => $member->company_registration_number,
                        'gstin' => $member->gstin,
                        'pan_number' => $member->pan_number,
                        'company_address' => $member->company_address,
                        'company_city' => $member->company_city,
                        'company_state' => $member->company_state,
                        'company_pincode' => $member->company_pincode,
                        'company_phone' => $member->company_phone,
                        'company_email' => $member->company_email,
                        'admin_id' => $member->admin_id,
                        'admin_group_name' => $member->admin_group_name,
                        'approval_status' => $member->approval_status,
                        'approved_at' => $member->approved_at,
                        'created_at' => $member->created_at,
                        'updated_at' => $member->updated_at,
                        'roles' => $member->roles->map(function($role) {
                            return [
                                'id' => $role->id,
                                'name' => $role->name,
                                'display_name' => $role->display_name
                            ];
                        }),
                        'documents' => [
                            'company_registration_certificate' => $member->company_registration_certificate,
                            'gst_certificate' => $member->gst_certificate,
                            'pan_card' => $member->pan_card,
                            'address_proof' => $member->address_proof,
                            'bank_statement' => $member->bank_statement
                        ]
                    ];
                })->toArray(),
                'statistics' => [
                    'total_members' => $admin->members->count(),
                    'approved_members' => $admin->members->where('approval_status', 'approved')->count(),
                    'pending_members' => $admin->members->where('approval_status', 'pending')->count(),
                    'rejected_members' => $admin->members->where('approval_status', 'rejected')->count()
                ]
            ];
            
            return response()->json([
                'status' => 'success',
                'message' => 'Admin group retrieved successfully',
                'data' => $adminData
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve admin group',
                'error' => $e->getMessage(),
                'code' => 'ADMIN_FETCH_FAILED'
            ], 500);
        }
    }
    
    /**
     * Get hierarchy statistics
     * 
     * @return JsonResponse
     */
    public function getHierarchyStats(): JsonResponse
    {
        try {
            $currentUser = auth()->user();
            
            if ($currentUser->isSuperAdmin()) {
                // Super admin sees all statistics
                $stats = [
                    'total_users' => User::where('id', '!=', $currentUser->id)->count(),
                    'total_admins' => User::whereHas('roles', function($q) {
                        $q->where('name', 'admin');
                    })->where('id', '!=', $currentUser->id)->count(),
                    'total_members' => User::whereHas('roles', function($q) {
                        $q->where('name', 'member');
                    })->count(),
                    'approved_users' => User::approved()->count(),
                    'pending_users' => User::pending()->count(),
                    'rejected_users' => User::rejected()->count()
                ];
            } elseif ($currentUser->isRegularAdmin()) {
                // Regular admin sees only their group statistics
                $stats = [
                    'my_members' => $currentUser->members()->count(),
                    'approved_members' => $currentUser->members()->approved()->count(),
                    'pending_members' => $currentUser->members()->pending()->count(),
                    'rejected_members' => $currentUser->members()->rejected()->count()
                ];
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Access denied. Only administrators can access statistics.',
                    'code' => 'ACCESS_DENIED'
                ], 403);
            }
            
            return response()->json([
                'status' => 'success',
                'message' => 'Statistics retrieved successfully',
                'data' => $stats
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage(),
                'code' => 'STATS_FETCH_FAILED'
            ], 500);
        }
    }
}
