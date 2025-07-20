# API Logging System for Tarun Demo API

This document explains the comprehensive logging system implemented for tracking all data sent to the Tarun Demo API when creating applications and leads.

## Overview

The system automatically logs all API requests and responses when:
- Creating new applications
- Creating new leads

## Log Files Location

The logs are stored in the following locations:

### Application API Logs
- **File**: `storage/logs/api_requests.json`
- **Purpose**: Logs all application creation API requests to Tarun Demo API
- **Max Entries**: 100 (automatically rotated)

### Lead API Logs
- **File**: `storage/logs/lead_api_requests.json`
- **Purpose**: Logs all lead creation API requests to Tarun Demo API
- **Max Entries**: 100 (automatically rotated)

### Legacy Logs (Backward Compatibility)
- **File**: `api_payload_logs.json` (project root)
- **File**: `api_lead_payload_logs.json` (project root)

## What Gets Logged

### Application Logs Include:
- **Request Information**:
  - Timestamp
  - Application ID and number
  - Applicant details (name, email, phone)
  - User who created the application
  - Attempt number (for retries)
  - Complete request payload
  - Payload size
  - Documents count
  - Course options count
  - Employment history count

- **Response Information**:
  - HTTP status code
  - Response headers
  - Complete response body
  - Success/failure status
  - JSON parsed response (if applicable)

- **System Information**:
  - PHP version
  - Laravel version
  - User agent
  - IP address

### Lead Logs Include:
- Similar structure as applications but adapted for lead data
- Lead reference number
- Lead-specific information

## Accessing the Logs

### Via Web Interface
1. Go to Applications page (`/applications`)
2. Click on "API Logs" button (green button)
3. View, filter, and download logs

### Via Log Viewer Features:
- **Filter by Application ID**: Search for specific application logs
- **Filter by Status**: View only successful or failed requests
- **View Details**: Click "View Details" to see complete request/response data
- **Download Logs**: Download complete log file as JSON
- **Clear Logs**: Remove all log entries

### Via File System
- Access log files directly in `storage/logs/` directory
- Files are in JSON format for easy parsing

## Log Structure

```json
{
  "note": "Comprehensive log of all API requests to Tarun Demo API when creating applications",
  "created_at": "2025-07-20T12:00:00.000000Z",
  "last_updated": "2025-07-20T15:30:00.000000Z",
  "total_count": 15,
  "successful_requests": 12,
  "failed_requests": 3,
  "requests": [
    {
      "timestamp": "2025-07-20T15:30:00.000000Z",
      "application_id": 123,
      "application_number": "APP-2025-001",
      "applicant_name": "John Doe",
      "applicant_email": "john@example.com",
      "applicant_phone": "1234567890",
      "created_by": "Admin User",
      "created_by_id": 1,
      "attempt_number": 1,
      "api_endpoint": "https://tarundemo.innerxcrm.com/b2bapi/enquiry_lead",
      "request_payload": { /* Complete request data */ },
      "payload_size": 1024,
      "documents_count": 5,
      "course_options_count": 2,
      "employment_history_count": 1,
      "response": {
        "status_code": 200,
        "headers": { /* Response headers */ },
        "body": "Response content",
        "successful": true,
        "json": { /* Parsed JSON response */ }
      },
      "system_info": {
        "php_version": "8.1.0",
        "laravel_version": "10.x",
        "user_agent": "Mozilla/5.0...",
        "ip_address": "127.0.0.1"
      }
    }
  ]
}
```

## Configuration

### Enable/Disable External API
Set in `.env` file:
```env
EXTERNAL_API_ENABLED=true
EXTERNAL_API_ENQUIRY_LEAD_URL=https://tarundemo.innerxcrm.com/b2bapi/enquiry_lead
```

### Logging Settings
- Logs are automatically enabled when external API is enabled
- No additional configuration needed
- Files are automatically created on first API call

## Monitoring and Maintenance

### Automatic Features:
- **File Rotation**: Only keeps last 100 entries per file
- **Error Handling**: Continues working even if logging fails
- **Multiple Formats**: Logs to both JSON files and Laravel logs

### Manual Maintenance:
- Use "Clear Logs" button in web interface
- Or manually delete log files from `storage/logs/`

## Troubleshooting

### If logs are not appearing:
1. Check that `EXTERNAL_API_ENABLED=true` in `.env`
2. Verify `storage/logs/` directory is writable
3. Check Laravel logs for any logging errors
4. Create an application to trigger logging

### If web interface shows errors:
1. Ensure routes are properly registered
2. Check file permissions on log files
3. Verify controller methods are accessible

## Routes

The following routes are available for log management:

- `GET /applications/api-logs` - View logs interface
- `GET /applications/api-logs/download` - Download logs as JSON
- `GET /applications/api-logs/clear` - Clear all logs

## Security Considerations

- Log files contain sensitive application data
- Ensure proper file permissions (755 for directories, 644 for files)
- Consider log retention policies for production environments
- Regularly clean up old log files if needed

## Integration with Laravel Logging

In addition to JSON files, the system also logs to Laravel's standard logging system:

- **Success**: Logged as `INFO` level
- **Failures**: Logged as `ERROR` level
- **Exceptions**: Logged as `WARNING` level

Check standard Laravel logs in `storage/logs/laravel.log` for additional debugging information.
