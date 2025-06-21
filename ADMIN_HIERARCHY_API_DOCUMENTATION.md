# Admin Hierarchy and User Approval API Documentation

## Overview
This API provides comprehensive endpoints for managing admin hierarchies and user approvals in the CRM system. It's designed for third-party software integration with complete data delivery and robust approval workflows.

## Authentication
All API endpoints require Bearer token authentication using Laravel Sanctum.

```http
Authorization: Bearer {your-api-token}
```

## Base URL
```
{your-domain}/api/
```

---

## Admin Hierarchy Management

### 1. Get All Admins with Members

**Endpoint:** `GET /admin-hierarchy/admins`

**Description:** Retrieves all administrator accounts with their complete member details and statistics. Only accessible by Super Administrators.

**Authorization:** Super Admin only

**Response Format:**
```json
{
    "status": "success",
    "message": "Admin hierarchy retrieved successfully",
    "data": {
        "system_statistics": {
            "total_admins": 3,
            "total_members": 15,
            "total_approved_members": 12,
            "total_pending_members": 2,
            "total_rejected_members": 1
        },
        "admin_hierarchy": [
            {
                "admin_id": 2,
                "admin_details": {
                    "id": 2,
                    "name": "John Admin",
                    "email": "john@admin.com",
                    "company_name": "Admin Corp",
                    "company_phone": "+1234567890",
                    "company_email": "contact@admincorp.com",
                    "city": "New York",
                    "state": "NY",
                    "approval_status": "approved",
                    "created_at": "2025-06-21T10:00:00.000000Z",
                    "updated_at": "2025-06-21T10:00:00.000000Z",
                    "is_super_admin": false,
                    "is_regular_admin": true,
                    "member_count": 5,
                    "roles": [
                        {
                            "id": 1,
                            "name": "admin",
                            "display_name": "Administrator"
                        }
                    ]
                },
                "group_name": "John Admin's Group",
                "members": [
                    {
                        "id": 3,
                        "name": "Jane Member",
                        "email": "jane@member.com",
                        "company_name": "Member Company",
                        "company_registration_number": "REG123456",
                        "gstin": "GST123456789",
                        "pan_number": "ABCDE1234F",
                        "company_address": "123 Business St",
                        "company_city": "Los Angeles",
                        "company_state": "CA",
                        "company_pincode": "90210",
                        "company_phone": "+1987654321",
                        "company_email": "info@membercompany.com",
                        "admin_id": 2,
                        "admin_group_name": "John Admin's Group",
                        "approval_status": "approved",
                        "approved_at": "2025-06-21T11:00:00.000000Z",
                        "created_at": "2025-06-21T09:00:00.000000Z",
                        "updated_at": "2025-06-21T11:00:00.000000Z",
                        "roles": [
                            {
                                "id": 3,
                                "name": "member",
                                "display_name": "Member"
                            }
                        ],
                        "documents": {
                            "company_registration_certificate": "/storage/documents/reg_cert_123.pdf",
                            "gst_certificate": "/storage/documents/gst_cert_123.pdf",
                            "pan_card": "/storage/documents/pan_123.pdf",
                            "address_proof": "/storage/documents/address_123.pdf",
                            "bank_statement": "/storage/documents/bank_123.pdf"
                        }
                    }
                ],
                "statistics": {
                    "total_members": 5,
                    "approved_members": 4,
                    "pending_members": 1,
                    "rejected_members": 0
                }
            }
        ],
        "metadata": {
            "total_admin_groups": 3,
            "retrieved_at": "2025-06-21T12:00:00.000000Z",
            "api_version": "1.0"
        }
    }
}
```

**Error Responses:**
- `403 Forbidden`: Access denied (not super admin)
- `500 Internal Server Error`: Server error

---

### 2. Get Specific Admin with Members

**Endpoint:** `GET /admin-hierarchy/admins/{adminId}`

**Description:** Retrieves a specific administrator with their members.

**Authorization:** Super Admin or the specific admin

**Parameters:**
- `adminId` (integer, required): The ID of the admin

**Response Format:**
```json
{
    "status": "success",
    "message": "Admin group retrieved successfully",
    "data": {
        "admin_details": {
            "id": 2,
            "name": "John Admin",
            "email": "john@admin.com",
            "company_name": "Admin Corp",
            "company_phone": "+1234567890",
            "company_email": "contact@admincorp.com",
            "city": "New York",
            "state": "NY",
            "approval_status": "approved",
            "created_at": "2025-06-21T10:00:00.000000Z",
            "member_count": 5,
            "roles": ["Administrator"]
        },
        "group_name": "John Admin's Group",
        "members": [
            {
                "id": 3,
                "name": "Jane Member",
                "email": "jane@member.com",
                "company_name": "Member Company",
                "approval_status": "approved",
                "created_at": "2025-06-21T09:00:00.000000Z",
                "roles": ["Member"]
            }
        ]
    }
}
```

**Error Responses:**
- `403 Forbidden`: Access denied
- `404 Not Found`: Admin not found
- `500 Internal Server Error`: Server error

---

### 3. Get Hierarchy Statistics

**Endpoint:** `GET /admin-hierarchy/stats`

**Description:** Retrieves statistics based on user role.

**Authorization:** Admin or Super Admin

**Response Format (Super Admin):**
```json
{
    "status": "success",
    "message": "Statistics retrieved successfully",
    "data": {
        "total_users": 20,
        "total_admins": 3,
        "total_members": 17,
        "approved_users": 18,
        "pending_users": 2,
        "rejected_users": 0
    }
}
```

**Response Format (Regular Admin):**
```json
{
    "status": "success",
    "message": "Statistics retrieved successfully",
    "data": {
        "my_members": 5,
        "approved_members": 4,
        "pending_members": 1,
        "rejected_members": 0
    }
}
```

---

## Enhanced User Approval Management

### 4. Enhanced User Approval

**Endpoint:** `POST /user-approval/users/{userId}/enhanced-approve`

**Description:** Approve a user with enhanced features for third-party integration.

**Authorization:** Super Admin or Admin managing the user

**Parameters:**
- `userId` (integer, required): The ID of the user to approve

**Request Body:**
```json
{
    "approved_by": 1,
    "notes": "Approved after document verification",
    "notification_preferences": {
        "email": true,
        "sms": false
    },
    "auto_assign_admin": true
}
```

**Request Fields:**
- `approved_by` (integer, required): ID of the user approving
- `notes` (string, optional): Additional notes
- `notification_preferences` (object, optional): Notification settings
- `auto_assign_admin` (boolean, optional): Auto-assign to current admin if orphaned

**Response Format:**
```json
{
    "status": "success",
    "message": "User approved successfully",
    "data": {
        "user": {
            "id": 3,
            "name": "Jane Member",
            "email": "jane@member.com",
            "company_name": "Member Company",
            "approval_status": "approved",
            "approved_at": "2025-06-21T12:00:00.000000Z",
            "approved_by": 1,
            "approver": {
                "id": 1,
                "name": "Super Admin",
                "email": "admin@system.com"
            },
            "admin": {
                "id": 2,
                "name": "John Admin",
                "group_name": "John Admin's Group"
            },
            "roles": [
                {
                    "id": 3,
                    "name": "member",
                    "display_name": "Member"
                }
            ]
        },
        "metadata": {
            "processed_at": "2025-06-21T12:00:00.000000Z",
            "processed_by": 1,
            "notes": "Approved after document verification",
            "auto_assigned_admin": true
        }
    }
}
```

**Error Responses:**
- `403 Forbidden`: Access denied
- `404 Not Found`: User not found
- `400 Bad Request`: User already approved
- `422 Unprocessable Entity`: Validation failed
- `500 Internal Server Error`: Server error

---

### 5. Enhanced User Rejection

**Endpoint:** `POST /user-approval/users/{userId}/enhanced-reject`

**Description:** Reject a user with enhanced features for third-party integration.

**Authorization:** Super Admin or Admin managing the user

**Parameters:**
- `userId` (integer, required): The ID of the user to reject

**Request Body:**
```json
{
    "rejection_reason": "Incomplete documentation provided",
    "rejected_by": 1,
    "notes": "Missing GST certificate and bank statement",
    "allow_reapplication": true
}
```

**Request Fields:**
- `rejection_reason` (string, required): Reason for rejection (max 500 chars)
- `rejected_by` (integer, required): ID of the user rejecting
- `notes` (string, optional): Additional notes (max 1000 chars)
- `allow_reapplication` (boolean, optional): Whether user can reapply

**Response Format:**
```json
{
    "status": "success",
    "message": "User rejected successfully",
    "data": {
        "user": {
            "id": 3,
            "name": "Jane Member",
            "email": "jane@member.com",
            "company_name": "Member Company",
            "approval_status": "rejected",
            "rejection_reason": "Incomplete documentation provided",
            "rejected_at": "2025-06-21T12:00:00.000000Z",
            "admin": {
                "id": 2,
                "name": "John Admin",
                "group_name": "John Admin's Group"
            },
            "allow_reapplication": true
        },
        "metadata": {
            "processed_at": "2025-06-21T12:00:00.000000Z",
            "processed_by": 1,
            "rejected_by": 1,
            "notes": "Missing GST certificate and bank statement"
        }
    }
}
```

---

### 6. Bulk User Actions

**Endpoint:** `POST /user-approval/users/bulk-actions`

**Description:** Perform bulk approve, reject, or set pending actions on multiple users.

**Authorization:** Super Admin or Admin managing the users

**Request Body:**
```json
{
    "action": "approve",
    "user_ids": [3, 4, 5, 6],
    "approved_by": 1,
    "notes": "Bulk approval after verification process"
}
```

**Request Fields:**
- `action` (string, required): "approve", "reject", or "pending"
- `user_ids` (array, required): Array of user IDs to process
- `approved_by` (integer, required if action=approve): ID of approver
- `rejection_reason` (string, required if action=reject): Reason for rejection
- `notes` (string, optional): Additional notes

**Response Format:**
```json
{
    "status": "success",
    "message": "Bulk approve operation completed",
    "data": {
        "summary": {
            "total_requested": 4,
            "total_accessible": 4,
            "successful_operations": 3,
            "failed_operations": 1,
            "action_performed": "approve"
        },
        "results": [
            {
                "user_id": 3,
                "status": "success",
                "action": "approved",
                "message": "User approved successfully"
            },
            {
                "user_id": 4,
                "status": "success",
                "action": "approved",
                "message": "User approved successfully"
            },
            {
                "user_id": 5,
                "status": "skipped",
                "action": "approve",
                "message": "User already approved"
            },
            {
                "user_id": 6,
                "status": "error",
                "action": "approve",
                "message": "Failed to process user: Database error"
            }
        ],
        "metadata": {
            "processed_at": "2025-06-21T12:00:00.000000Z",
            "processed_by": 1,
            "notes": "Bulk approval after verification process"
        }
    }
}
```

---

## Error Handling

### Standard Error Response Format
```json
{
    "status": "error",
    "message": "Error description",
    "error": "Detailed error message",
    "code": "ERROR_CODE"
}
```

### Common Error Codes
- `ACCESS_DENIED`: Insufficient permissions
- `USER_NOT_FOUND`: User does not exist
- `ADMIN_NOT_FOUND`: Admin does not exist
- `VALIDATION_FAILED`: Request validation failed
- `ALREADY_APPROVED`: User already approved
- `ALREADY_REJECTED`: User already rejected
- `HIERARCHY_FETCH_FAILED`: Failed to retrieve hierarchy
- `APPROVAL_FAILED`: Approval operation failed
- `REJECTION_FAILED`: Rejection operation failed
- `BULK_OPERATION_FAILED`: Bulk operation failed

---

## Rate Limiting

API endpoints are rate limited to:
- 60 requests per minute for standard endpoints
- 30 requests per minute for bulk operations
- 100 requests per minute for read-only endpoints

---

## Data Filtering and Permissions

### Access Control Matrix

| User Type | Admin Hierarchy | Own Group | All Users | Bulk Operations |
|-----------|----------------|-----------|-----------|-----------------|
| Super Admin | ✅ Full Access | ✅ | ✅ | ✅ |
| Regular Admin | ❌ | ✅ | ❌ | ✅ (Own group only) |
| Member | ❌ | ❌ | ❌ | ❌ |

### Data Isolation
- Super Admins: Can access all data except their own profile in listings
- Regular Admins: Can only access their assigned members
- Members: Can only access their own data

---

## Integration Examples

### Example 1: Get All Admin Hierarchy (Third-party Dashboard)
```bash
curl -X GET "https://your-domain.com/api/admin-hierarchy/admins" \
     -H "Authorization: Bearer {token}" \
     -H "Accept: application/json"
```

### Example 2: Bulk Approve Users (External Approval System)
```bash
curl -X POST "https://your-domain.com/api/user-approval/users/bulk-actions" \
     -H "Authorization: Bearer {token}" \
     -H "Content-Type: application/json" \
     -d '{
       "action": "approve",
       "user_ids": [10, 11, 12],
       "approved_by": 1,
       "notes": "Approved via external system"
     }'
```

### Example 3: Enhanced User Rejection
```bash
curl -X POST "https://your-domain.com/api/user-approval/users/5/enhanced-reject" \
     -H "Authorization: Bearer {token}" \
     -H "Content-Type: application/json" \
     -d '{
       "rejection_reason": "Documents not meeting compliance standards",
       "rejected_by": 2,
       "notes": "Requires updated GST certificate",
       "allow_reapplication": true
     }'
```

---

## Support

For API support and questions, contact the development team or refer to the system documentation.

**API Version:** 1.0  
**Last Updated:** June 21, 2025
