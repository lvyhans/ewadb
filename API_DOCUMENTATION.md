# User Approval API Documentation

## Base URL
```
http://your-domain.com/api
```

## Authentication
All endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:
```
Authorization: Bearer {your-token}
```

## Endpoints

### 1. Get Pending Users
**GET** `/external/user-approvals/pending`

Get a paginated list of all users pending approval.

**Query Parameters:**
- `per_page` (optional): Number of users per page (default: 15)
- `page` (optional): Page number (default: 1)

**Response:**
```json
{
    "status": "success",
    "message": "Pending users retrieved successfully",
    "data": {
        "users": [
            {
                "id": 1,
                "name": "John Doe",
                "email": "john@company.com",
                "company_name": "ABC Corp",
                "company_registration_number": "REG123456",
                "gstin": "29ABCDE1234F2Z5",
                "pan_number": "ABCDE1234F",
                "company_phone": "+919876543210",
                "company_email": "info@company.com",
                "company_city": "Mumbai",
                "company_state": "Maharashtra",
                "company_pincode": "400001",
                "company_address": "123 Business Street",
                "approval_status": "pending",
                "created_at": "2025-05-27T10:30:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 1,
            "per_page": 15,
            "total": 1,
            "from": 1,
            "to": 1
        }
    }
}
```

### 2. Get User Details
**GET** `/external/user-approvals/{userId}`

Get detailed information about a specific user including document status.

**Response:**
```json
{
    "status": "success",
    "message": "User details retrieved successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@company.com",
            "company_name": "ABC Corp",
            "company_registration_number": "REG123456",
            "gstin": "29ABCDE1234F2Z5",
            "pan_number": "ABCDE1234F",
            "company_phone": "+919876543210",
            "company_email": "info@company.com",
            "company_address": "123 Business Street",
            "company_city": "Mumbai",
            "company_state": "Maharashtra",
            "company_pincode": "400001",
            "approval_status": "pending",
            "rejection_reason": null,
            "approved_at": null,
            "approved_by": null,
            "created_at": "2025-05-27T10:30:00.000000Z",
            "updated_at": "2025-05-27T10:30:00.000000Z",
            "roles": ["Super Admin"]
        },
        "documents": {
            "company_registration_certificate": {
                "uploaded": true,
                "file_path": "documents/registration_cert_123.pdf"
            },
            "gst_certificate": {
                "uploaded": true,
                "file_path": "documents/gst_cert_123.pdf"
            },
            "pan_card": {
                "uploaded": true,
                "file_path": "documents/pan_card_123.pdf"
            },
            "address_proof": {
                "uploaded": true,
                "file_path": "documents/address_proof_123.pdf"
            },
            "bank_statement": {
                "uploaded": true,
                "file_path": "documents/bank_statement_123.pdf"
            }
        }
    }
}
```

### 3. Approve User
**POST** `/external/user-approvals/{userId}/approve`

Approve a pending user registration.

**Request Body:**
```json
{
    "approved_by": 1
}
```

**Response:**
```json
{
    "status": "success",
    "message": "User approved successfully",
    "data": {
        "user_id": 1,
        "name": "John Doe",
        "email": "john@company.com",
        "approval_status": "approved",
        "approved_at": "2025-05-27T11:30:00.000000Z",
        "approved_by": 1
    }
}
```

### 4. Reject User
**POST** `/external/user-approvals/{userId}/reject`

Reject a pending user registration.

**Request Body:**
```json
{
    "rejection_reason": "Invalid GST certificate",
    "approved_by": 1
}
```

**Response:**
```json
{
    "status": "success",
    "message": "User rejected successfully",
    "data": {
        "user_id": 1,
        "name": "John Doe",
        "email": "john@company.com",
        "approval_status": "rejected",
        "rejection_reason": "Invalid GST certificate",
        "approved_by": 1
    }
}
```

### 5. Bulk Update Users
**POST** `/external/user-approvals/bulk-update`

Approve or reject multiple users at once.

**Request Body (Approve):**
```json
{
    "action": "approve",
    "user_ids": [1, 2, 3],
    "approved_by": 1
}
```

**Request Body (Reject):**
```json
{
    "action": "reject",
    "user_ids": [1, 2, 3],
    "approved_by": 1,
    "rejection_reason": "Documents incomplete"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Successfully approved 3 users",
    "data": {
        "action": "approve",
        "updated_count": 3,
        "user_ids": [1, 2, 3]
    }
}
```

### 6. Get Approval Statistics
**GET** `/external/user-approvals/statistics`

Get statistics about user approvals.

**Response:**
```json
{
    "status": "success",
    "message": "Approval statistics retrieved successfully",
    "data": {
        "statistics": {
            "pending_count": 5,
            "approved_count": 25,
            "rejected_count": 3,
            "total_registrations": 33,
            "approval_rate": 75.76
        },
        "recent_registrations": [
            {
                "date": "2025-05-27",
                "count": 3
            },
            {
                "date": "2025-05-26",
                "count": 2
            }
        ]
    }
}
```

## Error Responses

### Validation Error (422)
```json
{
    "status": "error",
    "message": "Validation failed",
    "errors": {
        "approved_by": ["The approved by field is required."]
    }
}
```

### User Not Found (404)
```json
{
    "status": "error",
    "message": "User not found"
}
```

### User Not Pending (400)
```json
{
    "status": "error",
    "message": "User is not pending approval",
    "current_status": "approved"
}
```

### Server Error (500)
```json
{
    "status": "error",
    "message": "Failed to approve user",
    "error": "Database connection failed"
}
```

## Authentication Endpoints

### Login to Get Token
**POST** `/auth/login`

**Request Body:**
```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "Admin User",
            "email": "admin@example.com"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "expires_at": "2025-05-28T10:30:00.000000Z"
    }
}
```

## Integration Examples

### cURL Examples

**Get Pending Users:**
```bash
curl -X GET "http://your-domain.com/api/external/user-approvals/pending" \
  -H "Authorization: Bearer your-token" \
  -H "Accept: application/json"
```

**Approve User:**
```bash
curl -X POST "http://your-domain.com/api/external/user-approvals/1/approve" \
  -H "Authorization: Bearer your-token" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"approved_by": 1}'
```

**Reject User:**
```bash
curl -X POST "http://your-domain.com/api/external/user-approvals/1/reject" \
  -H "Authorization: Bearer your-token" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"rejection_reason": "Invalid documents", "approved_by": 1}'
```

### PHP Integration Example

```php
<?php

class UserApprovalAPI {
    private $baseUrl;
    private $token;
    
    public function __construct($baseUrl, $token) {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->token = $token;
    }
    
    public function getPendingUsers($page = 1, $perPage = 15) {
        return $this->makeRequest('GET', "/external/user-approvals/pending?page={$page}&per_page={$perPage}");
    }
    
    public function approveUser($userId, $approvedBy) {
        return $this->makeRequest('POST', "/external/user-approvals/{$userId}/approve", [
            'approved_by' => $approvedBy
        ]);
    }
    
    public function rejectUser($userId, $reason, $approvedBy) {
        return $this->makeRequest('POST', "/external/user-approvals/{$userId}/reject", [
            'rejection_reason' => $reason,
            'approved_by' => $approvedBy
        ]);
    }
    
    private function makeRequest($method, $endpoint, $data = null) {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->baseUrl . '/api' . $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->token,
                'Content-Type: application/json',
                'Accept: application/json'
            ]
        ]);
        
        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return [
            'status_code' => $httpCode,
            'response' => json_decode($response, true)
        ];
    }
}

// Usage
$api = new UserApprovalAPI('http://your-domain.com', 'your-token');
$result = $api->approveUser(1, 1);
```

## Notes

- All endpoints return JSON responses
- Authentication is required for all endpoints
- The `approved_by` parameter should be the ID of the user performing the action
- User IDs in the system are integers
- All timestamps are in UTC format
- The API supports pagination for listing endpoints
- Bulk operations are atomic - if one fails, all fail