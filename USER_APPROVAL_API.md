# User Approval API Documentation

## Overview
This API provides comprehensive user approval management functionality for integration with 3rd party software. The API allows external systems to manage user approval workflows including viewing pending users, approving/rejecting users, and retrieving approval statistics.

**Base URL:** `{APP_URL}/api`  
**Authentication:** Bearer Token (Sanctum)  
**Content-Type:** `application/json`

---

## Authentication

All approval API endpoints require authentication using Laravel Sanctum tokens.

### Get Authentication Token
```http
POST /api/auth/login
Content-Type: application/json

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
    "token": "1|abc123..."
  }
}
```

**Use token in subsequent requests:**
```http
Authorization: Bearer 1|abc123...
```

---

## User Approval Endpoints

### 1. Get All Users with Approval Status

Retrieve all users with optional filtering by approval status.

```http
GET /api/user-approval/users
Authorization: Bearer {token}
```

**Query Parameters:**
- `status` (optional): Filter by approval status (`approved`, `pending`, `rejected`)
- `per_page` (optional): Number of results per page (default: 15)
- `page` (optional): Page number for pagination

**Example Request:**
```http
GET /api/user-approval/users?status=pending&per_page=10&page=1
Authorization: Bearer 1|abc123...
```

**Response:**
```json
{
  "status": "success",
  "message": "Users retrieved successfully",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 2,
        "name": "John Doe",
        "email": "john@company.com",
        "company_name": "ABC Corp",
        "approval_status": "pending",
        "created_at": "2025-06-17T10:30:00.000000Z",
        "approved_at": null,
        "approved_by": null,
        "rejection_reason": null,
        "approver": null
      }
    ],
    "per_page": 10,
    "total": 1
  }
}
```

### 2. Get Pending Users Only

Retrieve all users with pending approval status.

```http
GET /api/user-approval/users/pending
Authorization: Bearer {token}
```

**Response:**
```json
{
  "status": "success",
  "message": "Pending users retrieved successfully",
  "data": {
    "users": [
      {
        "id": 2,
        "name": "John Doe",
        "email": "john@company.com",
        "company_name": "ABC Corp",
        "created_at": "2025-06-17T10:30:00.000000Z",
        "approval_status": "pending"
      }
    ],
    "count": 1
  }
}
```

### 3. Get User Details

Retrieve detailed information about a specific user.

```http
GET /api/user-approval/users/{userId}
Authorization: Bearer {token}
```

**Response:**
```json
{
  "status": "success",
  "message": "User details retrieved successfully",
  "data": {
    "id": 2,
    "name": "John Doe",
    "email": "john@company.com",
    "company_name": "ABC Corp",
    "company_registration_number": "REG123456",
    "gstin": "29ABCDE1234F1Z5",
    "pan_number": "ABCDE1234F",
    "company_address": "123 Business Street",
    "company_city": "Mumbai",
    "company_state": "Maharashtra",
    "company_pincode": "400001",
    "company_phone": "+91-9876543210",
    "company_email": "info@company.com",
    "approval_status": "pending",
    "created_at": "2025-06-17T10:30:00.000000Z",
    "approved_at": null,
    "approved_by": null,
    "rejection_reason": null,
    "approver": null
  }
}
```

### 4. Approve User

Approve a pending user account.

```http
POST /api/user-approval/users/{userId}/approve
Authorization: Bearer {token}
Content-Type: application/json

{
  "approved_by": 1
}
```

**Request Body:**
- `approved_by` (required): ID of the user performing the approval

**Response:**
```json
{
  "status": "success",
  "message": "User approved successfully",
  "data": {
    "user_id": 2,
    "approval_status": "approved",
    "approved_at": "2025-06-17T12:00:00.000000Z",
    "approved_by": 1
  }
}
```

### 5. Reject User

Reject a user account with a reason.

```http
POST /api/user-approval/users/{userId}/reject
Authorization: Bearer {token}
Content-Type: application/json

{
  "rejection_reason": "Incomplete documentation provided",
  "rejected_by": 1
}
```

**Request Body:**
- `rejection_reason` (optional): Reason for rejection (max 1000 characters)
- `rejected_by` (required): ID of the user performing the rejection

**Response:**
```json
{
  "status": "success",
  "message": "User rejected successfully",
  "data": {
    "user_id": 2,
    "approval_status": "rejected",
    "rejection_reason": "Incomplete documentation provided",
    "rejected_by": 1
  }
}
```

### 6. Set User Status to Pending

Reset a user's approval status to pending (useful for re-review).

```http
POST /api/user-approval/users/{userId}/pending
Authorization: Bearer {token}
```

**Response:**
```json
{
  "status": "success",
  "message": "User status set to pending successfully",
  "data": {
    "user_id": 2,
    "approval_status": "pending"
  }
}
```

### 7. Bulk Approve Users

Approve multiple users at once.

```http
POST /api/user-approval/users/bulk-approve
Authorization: Bearer {token}
Content-Type: application/json

{
  "user_ids": [2, 3, 4],
  "approved_by": 1
}
```

**Request Body:**
- `user_ids` (required): Array of user IDs to approve
- `approved_by` (required): ID of the user performing the approvals

**Response:**
```json
{
  "status": "success",
  "message": "Successfully approved 3 users",
  "data": {
    "approved_count": 3,
    "requested_count": 3
  }
}
```

### 8. Get Approval Statistics

Retrieve overview statistics about user approvals.

```http
GET /api/user-approval/stats
Authorization: Bearer {token}
```

**Response:**
```json
{
  "status": "success",
  "message": "Approval statistics retrieved successfully",
  "data": {
    "total_users": 10,
    "approved_users": 7,
    "pending_users": 2,
    "rejected_users": 1,
    "approval_rate": 70.0
  }
}
```

---

## Error Responses

All endpoints follow a consistent error response format:

### Validation Error (422)
```json
{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "approved_by": ["The approved by field is required."],
    "rejection_reason": ["The rejection reason field is required."]
  }
}
```

### Not Found Error (404)
```json
{
  "status": "error",
  "message": "User not found"
}
```

### Unauthorized Error (401)
```json
{
  "status": "error",
  "message": "Unauthenticated"
}
```

### Server Error (500)
```json
{
  "status": "error",
  "message": "Failed to approve user",
  "error": "Database connection error"
}
```

### Business Logic Error (400)
```json
{
  "status": "error",
  "message": "User is already approved"
}
```

---

## Approval Status Values

The system supports three approval statuses:

| Status | Description |
|--------|-------------|
| `pending` | User account is awaiting approval (default for new registrations) |
| `approved` | User account has been approved and can login |
| `rejected` | User account has been rejected and cannot login |

---

## User Registration Flow

1. **User Registration**: New users register through the public registration form
2. **Status Assignment**: All new users (except first admin) are assigned `pending` status
3. **Login Restriction**: Users with `pending` or `rejected` status cannot login
4. **API Approval**: 3rd party systems use this API to approve/reject users
5. **Login Access**: Only `approved` users can successfully login

---

## Integration Examples

### Example: Processing Pending Users

```javascript
// Get all pending users
const pendingUsers = await fetch('/api/user-approval/users/pending', {
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json'
  }
});

const users = await pendingUsers.json();

// Approve a user
for (const user of users.data.users) {
  const approval = await fetch(`/api/user-approval/users/${user.id}/approve`, {
    method: 'POST',
    headers: {
      'Authorization': 'Bearer ' + token,
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      approved_by: 1 // Admin user ID
    })
  });
  
  console.log('Approved user:', user.email);
}
```

### Example: Bulk Processing

```python
import requests

# Authenticate
auth_response = requests.post('http://localhost:8000/api/auth/login', {
    'email': 'admin@example.com',
    'password': 'password'
})
token = auth_response.json()['data']['token']

headers = {
    'Authorization': f'Bearer {token}',
    'Content-Type': 'application/json'
}

# Get pending users
pending_response = requests.get(
    'http://localhost:8000/api/user-approval/users/pending',
    headers=headers
)
pending_users = pending_response.json()['data']['users']

# Bulk approve all pending users
user_ids = [user['id'] for user in pending_users]
if user_ids:
    bulk_response = requests.post(
        'http://localhost:8000/api/user-approval/users/bulk-approve',
        headers=headers,
        json={
            'user_ids': user_ids,
            'approved_by': 1
        }
    )
    print(f"Approved {bulk_response.json()['data']['approved_count']} users")
```

---

## Rate Limiting

API endpoints are subject to Laravel's default rate limiting:
- **Authentication endpoints**: 5 requests per minute per IP
- **General API endpoints**: 60 requests per minute per authenticated user

---

## Security Considerations

1. **Authentication Required**: All approval endpoints require valid authentication tokens
2. **Authorization**: Ensure the authenticated user has permission to perform approval operations
3. **Input Validation**: All input data is validated before processing
4. **Audit Trail**: All approval actions are logged with timestamps and approver information
5. **HTTPS Required**: Use HTTPS in production for secure token transmission

---

## Change Log

### Version 1.0 (June 17, 2025)
- Initial release of User Approval API
- Support for approve/reject/pending status management
- Bulk operations support
- Comprehensive statistics and reporting
- Full integration with Laravel Sanctum authentication

---

## Support

For technical support or API integration assistance, please contact the development team.

**API Version**: 1.0  
**Last Updated**: June 17, 2025  
**Laravel Version**: 11.x  
**PHP Version**: 8.4+
