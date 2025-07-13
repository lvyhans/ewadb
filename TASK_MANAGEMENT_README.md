# Task Management Module

This module provides integration with the external task management API at `https://tarundemo.innerxcrm.com/b2bapi/task_management`.

## Features

- Fetch tasks from external API
- Get task counts
- Get full task details with pagination
- Advanced filtering by status, dates, and admission ID
- Proper validation and error handling
- No database involvement - all data comes from external API

## Environment Configuration

Add these variables to your `.env` file:

```env
TASK_MANAGEMENT_API_URL=https://tarundemo.innerxcrm.com/b2bapi
TASK_MANAGEMENT_API_TIMEOUT=30
TASK_MANAGEMENT_API_ENABLED=true
```

## API Endpoints

All endpoints require authentication using Sanctum tokens.

### Base URL: `/api/task-management`

### 1. Get Task Count
**Endpoint:** `POST /api/task-management/tasks/count`

Returns only the count of tasks matching the criteria.

### 2. Get Full Tasks
**Endpoint:** `POST /api/task-management/tasks/full`

Returns full task details with pagination.

### 3. Get Filtered Tasks
**Endpoint:** `POST /api/task-management/tasks/filtered`

Returns tasks with applied filters and metadata about the filters used.

### 4. Generic Task Fetch
**Endpoint:** `POST /api/task-management/tasks`

Generic endpoint that accepts all parameters including the `return` parameter.

## Request Parameters

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `b2b_admin_id` | Integer | ✅ (or b2b_member_id) | Admin ID for B2B context |
| `b2b_member_id` | Integer | ✅ (or b2b_admin_id) | Member ID for B2B context |
| `admission_id` | Integer | ❌ | Filter tasks by admission ID |
| `return` | String | ❌ | Either "count" or "full" (default: "count") |
| `task_status` | String | ❌ | "open" or "closed" (default: "open") |
| `deadline_start` | Date | ❌ | Filter tasks with deadline on/after this date (YYYY-MM-DD) |
| `deadline_end` | Date | ❌ | Filter tasks with deadline on/before this date (YYYY-MM-DD) |
| `page` | Integer | ❌ | Pagination page number (default: 1) |
| `limit` | Integer | ❌ | Records per page (default: 10, max: 100) |

## Example Requests

### Get Task Count
```bash
curl -X POST /api/task-management/tasks/count \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "b2b_admin_id": 1,
    "task_status": "open"
  }'
```

### Get Full Tasks with Filters
```bash
curl -X POST /api/task-management/tasks/full \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "b2b_admin_id": 1,
    "task_status": "open",
    "deadline_start": "2025-07-01",
    "deadline_end": "2025-07-31",
    "page": 1,
    "limit": 10
  }'
```

## Response Examples

### Task Count Response
```json
{
  "success": true,
  "status": "open",
  "count": 5
}
```

### Full Tasks Response
```json
{
  "success": true,
  "status": "open",
  "data": [
    {
      "id": 1,
      "title": "Task Title",
      "description": "Task Description",
      "deadline": "2025-07-15",
      "status": "open"
    }
  ],
  "pagination": {
    "current_page": 1,
    "total_pages": 3,
    "total_count": 25
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "b2b_admin_id": ["Either b2b_admin_id or b2b_member_id is required."]
  }
}
```

## Validation Rules

- Either `b2b_admin_id` or `b2b_member_id` is required
- `task_status` must be "open" or "closed"
- `return` must be "count" or "full"
- Date fields must be in YYYY-MM-DD format
- `deadline_end` must be on or after `deadline_start`
- `page` must be at least 1
- `limit` must be between 1 and 100

## Error Handling

The module includes comprehensive error handling:

- **422 Validation Error**: Invalid request parameters
- **500 Server Error**: External API communication issues
- **401 Unauthorized**: Missing or invalid authentication token

## Testing

Run the feature tests:

```bash
php artisan test tests/Feature/TaskManagementTest.php
```

## Architecture

### Components

1. **TaskManagementService**: Handles external API communication
2. **TaskManagementController**: API endpoints and response handling
3. **TaskManagementRequest**: Request validation and formatting
4. **Routes**: API route definitions with authentication middleware

### Key Features

- **No Database Dependencies**: All data comes from external API
- **Comprehensive Validation**: Request validation with detailed error messages
- **Error Logging**: All API calls and errors are logged
- **Authentication**: Protected by Sanctum middleware
- **Configurable**: API URL and timeout configurable via environment variables

## Next Steps

This module is ready for task fetching. The next phase will involve adding task submission capabilities with additional endpoints and validation rules.
