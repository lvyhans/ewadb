# Lead Revert Management API Documentation

This API allows external teams to submit reverts/remarks for leads using their reference numbers and track the status of their submissions.

## Base URL
```
https://your-domain.com/api/external/lead-reverts
```

## Authentication
All endpoints require token-based authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:

```
Authorization: Bearer your-api-token
```

## Endpoints

### 1. Submit a Lead Revert/Remark

**POST** `/submit`

Submit a new revert or remark for a lead using its reference number.

#### Request Body
```json
{
  "ref_no": "LEAD000123",
  "revert_message": "The candidate's IELTS score needs verification. Please provide updated scorecard.",
  "submitted_by": "John Doe"
}
```

#### Parameters
- `ref_no` (required): Lead reference number
- `revert_message` (required): The revert/remark content (10-2000 characters)
- `submitted_by` (required): Name/identifier of the person submitting (max 100 characters)

#### Response
```json
{
  "success": true,
  "message": "Revert submitted successfully",
  "data": {
    "revert_id": 456,
    "ref_no": "LEAD000123",
    "submitted_at": "2025-06-27T10:30:00.000000Z",
    "status": "active",
    "priority": "normal"
  }
}
```

### 2. Bulk Submit Multiple Reverts

**POST** `/bulk-submit`

Submit multiple reverts for different leads in a single request.

#### Request Body
```json
{
  "reverts": [
    {
      "ref_no": "LEAD000123",
      "revert_message": "Additional documents required for admission process",
      "submitted_by": "Jane Smith"
    },
    {
      "ref_no": "LEAD000124",
      "revert_message": "Interview schedule needs to be updated",
      "submitted_by": "Jane Smith"
    }
  ]
}
```

#### Response
```json
{
  "success": true,
  "message": "Processed 2 successful, 0 failed",
  "summary": {
    "total_processed": 2,
    "successful": 2,
    "failed": 0
  },
  "results": [
    {
      "index": 0,
      "ref_no": "LEAD000123",
      "success": true,
      "revert_id": 457,
      "message": "Revert submitted successfully"
    },
    {
      "index": 1,
      "ref_no": "LEAD000124",
      "success": true,
      "revert_id": 458,
      "message": "Revert submitted successfully"
    }
  ]
}
```

### 3. Get All Reverts for a Lead

**GET** `/lead/{ref_no}`

Retrieve all reverts/remarks for a specific lead using its reference number.

#### Example Request
```
GET /lead/LEAD000123
```

#### Response
```json
{
  "success": true,
  "data": {
    "lead": {
      "id": 123,
      "ref_no": "LEAD000123",
      "name": "John Doe",
      "status": "qualified"
    },
    "reverts": [
      {
        "id": 456,
        "revert_message": "The candidate's IELTS score needs verification. Please provide updated scorecard.",
        "revert_type": "remark",
        "submitted_by": "John Doe",
        "team_name": "External Team",
        "priority": "normal",
        "status": "active",
        "submitted_at": "2025-06-27T10:30:00.000000Z",
        "resolved_at": null,
        "resolved_by": null,
        "resolution_notes": null,
        "is_overdue": false,
        "metadata": null
      }
    ],
    "statistics": {
      "total_reverts": 1,
      "active_reverts": 1,
      "resolved_reverts": 0,
      "overdue_reverts": 0
    }
  }
}
```

### 4. Get Revert Status

**GET** `/status/{revert_id}`

Get the current status of a specific revert submission.

#### Example Request
```
GET /status/456
```

#### Response
```json
{
  "success": true,
  "data": {
    "id": 456,
    "lead_ref_no": "LEAD000123",
    "lead_name": "John Doe",
    "revert_message": "The candidate's IELTS score needs verification.",
    "revert_type": "remark",
    "submitted_by": "John Doe",
    "team_name": "External Team",
    "priority": "normal",
    "status": "resolved",
    "submitted_at": "2025-06-27T10:30:00.000000Z",
    "resolved_at": "2025-06-27T14:45:00.000000Z",
    "resolved_by": "Admin User",
    "resolution_notes": "Updated IELTS scorecard received and verified",
    "is_overdue": false,
    "metadata": null
  }
}
```

## Error Responses

### Validation Error (422)
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "ref_no": ["The ref no field is required."],
    "revert_message": ["The revert message must be at least 10 characters."]
  }
}
```

### Lead Not Found (404)
```json
{
  "success": false,
  "message": "Lead not found with the provided reference number",
  "ref_no": "LEAD000999"
}
```

### Server Error (500)
```json
{
  "success": false,
  "message": "An error occurred while submitting the revert"
}
```

## Revert Types
- `remark`: General remarks or comments
- `revert`: Request for changes or corrections
- `feedback`: Feedback on the lead or process
- `query`: Questions that need clarification
- `complaint`: Complaints or issues

## Priority Levels
- `low`: Non-urgent, can be addressed within a week
- `normal`: Standard priority (default)
- `high`: Needs attention within 2 days
- `urgent`: Immediate attention required

## Revert Status
- `active`: Newly submitted, awaiting resolution
- `resolved`: Addressed and closed by internal team
- `archived`: Archived for record keeping

## Usage Examples

### Submit a simple remark
```bash
curl -X POST https://your-domain.com/api/external/lead-reverts/submit \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your-api-token" \
  -d '{
    "ref_no": "LEAD000123",
    "revert_message": "Please update the candidate contact information",
    "submitted_by": "External Team Member"
  }'
```

### Check revert status
```bash
curl https://your-domain.com/api/external/lead-reverts/status/456 \
  -H "Authorization: Bearer your-api-token"
```

### Get all reverts for a lead
```bash
curl https://your-domain.com/api/external/lead-reverts/lead/LEAD000123 \
  -H "Authorization: Bearer your-api-token"
```

## Rate Limiting
- Individual submissions: 100 requests per hour per IP
- Bulk submissions: 10 requests per hour per IP (max 50 reverts per request)

## Best Practices
1. Use meaningful and specific revert messages
2. Always include your name or identifier in the submitted_by field
3. Keep API tokens secure and don't share them
4. Use bulk submission for multiple reverts to reduce API calls
5. Regularly check the status of submitted reverts

## Getting API Tokens
Contact your system administrator to obtain an API token for accessing these endpoints. Each external team should have their own unique token for security and tracking purposes.
