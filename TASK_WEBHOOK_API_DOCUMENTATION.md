# Task Management Webhook API Documentation

## Overview

This API allows Tarundemo to send task status updates to our system. When a task status changes (created, reopened, or removed), Tarundemo can notify our system, and we'll create an internal notification for the relevant user.

## Endpoint

```
POST /api/webhooks/tasks
```

## Authentication

This endpoint does not require authentication to allow external systems to access it.

## Request Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| b2b_member_id | integer | Yes | The user ID in our system who should receive the notification |
| task_id | string | Yes | The unique identifier of the task (system extracts numeric ID if complex format is provided) |
| task_name | string | Yes | The name or title of the task |
| task_status | string | Yes | The status of the task (must be one of: 'created', 'reopen', 'removed') |

## Status Values

| Status | Description |
|--------|-------------|
| created | A new task has been created |
| reopen | An existing task has been reopened |
| removed | A task has been removed or deleted |

## Example Request

```bash
curl -X POST \
  -H "Content-Type: application/json" \
  -d '{
    "b2b_member_id": 1,
    "task_id": "task-123",
    "task_name": "Complete visa application form",
    "task_status": "created"
  }' \
  https://yourdomain.com/api/webhooks/tasks
```

## Responses

### Success Response

**Code:** 200 OK

```json
{
  "success": true,
  "message": "Task notification created successfully"
}
```

### Error Responses

**Code:** 422 Unprocessable Entity (Validation Failed)

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "b2b_member_id": ["The b2b member id field is required."],
    "task_status": ["The selected task status is invalid."]
  }
}
```

**Code:** 500 Internal Server Error

```json
{
  "success": false,
  "message": "Failed to process task webhook: [error message]"
}
```

## Notification Details

When a webhook is received, our system:

1. Creates a notification record in our database
2. The notification includes:
   - Task ID
   - Task name
   - Task status
   - A human-readable message based on the status
   - A link to view the specific task in our task management system
   - Timestamp of when the notification was created

When users click on a task notification, they will be directed to the task management page with the task_id parameter automatically applied as a filter. This ensures that users can immediately see the specific task that the notification is about.

## Implementation Notes

- All requests are logged for audit purposes
- The system validates that the specified user exists before creating a notification
- Invalid requests are rejected with detailed error messages
- The API handles concurrent requests efficiently

## Error Handling

The API implements comprehensive error handling:
- Input validation errors return a 422 status code with detailed error messages
- Server errors return a 500 status code with an error message
- All errors are logged for debugging purposes

## Testing the API

You can test the API using the following curl command:

```bash
curl -X POST \
  -H "Content-Type: application/json" \
  -d '{
    "b2b_member_id": 1,
    "task_id": "test-task-123",
    "task_name": "Test Task",
    "task_status": "created"
  }' \
  http://127.0.0.1:8000/api/webhooks/tasks
```

For testing in production, replace the URL with your production domain.

## Internal Implementation

The webhook API is implemented in `app/Http/Controllers/Api/TaskWebhookController.php` and uses Laravel's notification system to create notifications. When a valid request is received, it:

1. Validates the incoming request parameters
2. Finds the user with the specified ID
3. Extracts the numeric part of the task ID (e.g., "task-456-fix-redirect" becomes "456")
4. Creates a notification for that user using the `TaskStatusChangedNotification` class with the extracted task ID
5. Returns a success response

This webhook API provides a reliable way for Tarundemo to notify our system about task status changes, ensuring that users are kept up-to-date with their assigned tasks.
