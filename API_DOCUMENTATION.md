# Lead Management API Documentation

## Overview
This API provides comprehensive lead management functionality for your B2B visa consultation business. It includes lead creation, management, and followup tracking.

## Authentication
All API endpoints require authentication using Laravel Sanctum. Include the Bearer token in the Authorization header:

```
Authorization: Bearer {your-token-here}
```

## Base URL
```
http://your-domain.com/api
```

## Lead Management Endpoints

### 1. Get All Leads
**GET** `/api/leads`

**Parameters:**
- `status` (optional): Filter by lead status (new, contacted, qualified, converted, rejected)
- `assigned_to` (optional): Filter by assigned user ID
- `search` (optional): Search by name, phone, email, or ref_no
- `page` (optional): Page number for pagination

**Example Response:**
```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "ref_no": "LEAD000001",
                "name": "John Smith",
                "phone": "+1-555-0123",
                "email": "john.smith@email.com",
                "status": "new",
                "preferred_country": "Canada",
                "preferred_college": "University of Toronto",
                "created_at": "2025-06-25T05:42:28.000000Z",
                "creator": {
                    "id": 1,
                    "name": "Admin User"
                },
                "assigned_user": null,
                "latest_followup": null
            }
        ],
        "total": 3,
        "per_page": 15
    }
}
```

### 2. Create New Lead
**POST** `/api/leads`

**Form Data Fields from Your Visa Form:**
```json
{
    "name": "John Doe",
    "phone": "+1-555-1234",
    "email": "john@example.com",
    "dob": "1995-01-15",
    "father": "Robert Doe",
    "rphone": "+1-555-5678",
    "address": "123 Main St",
    "country": "Canada",
    "city": "Toronto",
    "college": "University of Toronto",
    "course": "Computer Science",
    "travel_history": "USA (2019), UK (2020)",
    "any_refusal": "None",
    "spouse_name": "Jane Doe",
    "any_gap": "None",
    "score_type": "ielts",
    "ielts_listening": 8.0,
    "ielts_reading": 7.5,
    "ielts_writing": 7.0,
    "ielts_speaking": 7.5,
    "ielts_overall": 7.5,
    "last_qual": "Graduation",
    "byear": "2017",
    "bmarks": "85%",
    "gra_major": "Engineering",
    "already_applied": "No previous applications",
    "source": "Google",
    "r_name": "Reference Name (if source is Reference)",
    "remarks": "Interested in Masters program",
    "employementhistory": [
        {
            "join_date": "2018-01-01",
            "left_date": "2023-12-31",
            "company_name": "Tech Corp",
            "job_position": "Software Engineer",
            "job_city": "New York"
        }
    ]
}
```

**Example Response:**
```json
{
    "success": true,
    "message": "Lead created successfully",
    "data": {
        "id": 4,
        "ref_no": "LEAD000004",
        "name": "John Doe",
        "phone": "+1-555-1234",
        "status": "new",
        "created_at": "2025-06-25T10:30:00.000000Z",
        "employment_history": [
            {
                "id": 1,
                "company_name": "Tech Corp",
                "job_position": "Software Engineer"
            }
        ]
    }
}
```

### 3. Get Single Lead
**GET** `/api/leads/{id}`

**Example Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "ref_no": "LEAD000001",
        "name": "John Smith",
        "phone": "+1-555-0123",
        "email": "john.smith@email.com",
        "dob": "1995-01-15",
        "preferred_country": "Canada",
        "score_type": "ielts",
        "ielts_overall": 7.5,
        "status": "contacted",
        "employment_history": [],
        "followups": [
            {
                "id": 1,
                "type": "call",
                "subject": "Initial consultation",
                "status": "completed",
                "scheduled_at": "2025-06-22T10:00:00.000000Z",
                "user": {
                    "name": "Visa Counselor"
                }
            }
        ]
    }
}
```

### 4. Update Lead
**PUT** `/api/leads/{id}`

**Request Body:**
```json
{
    "status": "qualified",
    "assigned_to": 2,
    "remarks": "Updated remarks"
}
```

### 5. Delete Lead
**DELETE** `/api/leads/{id}`

### 6. Lead Statistics
**GET** `/api/leads/stats/dashboard`

**Example Response:**
```json
{
    "success": true,
    "data": {
        "total_leads": 25,
        "new_leads": 8,
        "contacted_leads": 10,
        "qualified_leads": 5,
        "converted_leads": 2,
        "rejected_leads": 0,
        "leads_this_month": 12,
        "pending_followups": 15
    }
}
```

## Followup Management Endpoints

### 1. Get Lead Followups
**GET** `/api/leads/{leadId}/followups`

### 2. Create Followup
**POST** `/api/leads/{leadId}/followups`

**Request Body:**
```json
{
    "type": "call",
    "subject": "Follow-up consultation",
    "description": "Discuss course selection and application process",
    "scheduled_at": "2025-06-30T14:00:00Z",
    "next_followup": "2025-07-05T10:00:00Z"
}
```

### 3. Complete Followup
**PUT** `/api/leads/{leadId}/followups/{followupId}/complete`

**Request Body:**
```json
{
    "outcome": "Positive response, ready to proceed with application",
    "next_followup": "2025-07-02T11:00:00Z"
}
```

### 4. Cancel Followup
**PUT** `/api/leads/{leadId}/followups/{followupId}/cancel`

### 5. My Followups
**GET** `/api/followups/my-followups`

**Parameters:**
- `status` (optional): scheduled, completed, cancelled
- `date_from` (optional): Filter from date
- `date_to` (optional): Filter to date

### 6. Today's Followups
**GET** `/api/followups/today`

### 7. Overdue Followups
**GET** `/api/followups/overdue`

## Status Codes

- `200` - Success
- `201` - Created successfully
- `422` - Validation errors
- `401` - Unauthorized
- `404` - Not found
- `500` - Server error

## Error Response Format

```json
{
    "success": false,
    "message": "Validation errors",
    "errors": {
        "name": ["The name field is required."],
        "phone": ["The phone field is required."]
    }
}
```

## Lead Status Values

- `new` - Newly created lead
- `contacted` - Initial contact made
- `qualified` - Lead is qualified for services
- `converted` - Lead converted to client
- `rejected` - Lead not suitable/rejected

## Followup Types

- `call` - Phone call
- `email` - Email communication
- `meeting` - In-person or video meeting
- `whatsapp` - WhatsApp message
- `other` - Other communication method
