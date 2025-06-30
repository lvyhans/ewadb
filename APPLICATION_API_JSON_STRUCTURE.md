# Application API JSON Structure

## Overview

When an application is submitted through the B2B portal, the data is automatically sent to the external API in JSON format. Below is the complete structure of the JSON payload.

## Complete JSON Structure

```json
{
    "type": "application",
    "application_number": "APP-000018",
    "lead_ref_no": null,
    "name": "John Doe",
    "email": "john.doe@email.com",
    "phone": "+1 (204) 205-7933",
    "date_of_birth": "1995-05-15",
    "gender": "male",
    "nationality": "Indian",
    "passport_number": "AB1234567",
    "passport_expiry": "2030-12-31",
    "marital_status": "single",
    "address": "123 Main Street",
    "city": "Toronto",
    "state": "Ontario",
    "postal_code": "M1M 1M1",
    "country": "Canada",
    "emergency_contact_name": "Jane Doe",
    "emergency_contact_phone": "+1 (204) 123-4567",
    "emergency_contact_relationship": "sister",
    
    // Study preferences
    "preferred_country": "Canada",
    "preferred_city": "Calgary",
    "preferred_college": "University of Calgary",
    "course_level": "bachelor",
    "field_of_study": "Computer Science",
    "intake_year": "2025",
    "intake_month": "September",
    
    // Course options from course finder (NEW FEATURE)
    "course_options": [
        {
            "country": "Canada",
            "city": "Toronto",
            "college": "University of Toronto",
            "course": "Computer Science",
            "course_type": "Bachelor",
            "fees": "$50,000",
            "duration": "4 Years",
            "college_detail_id": "123",
            "is_primary": true,
            "priority_order": 1
        },
        {
            "country": "Canada",
            "city": "Vancouver",
            "college": "UBC",
            "course": "Engineering",
            "course_type": "Bachelor",
            "fees": "$45,000",
            "duration": "4 Years",
            "college_detail_id": "456",
            "is_primary": false,
            "priority_order": 2
        }
    ],
    
    // Course options summary (NEW FEATURE)
    "course_options_summary": {
        "total_count": 2,
        "primary_course": {
            "course": "Computer Science",
            "college": "University of Toronto",
            "country": "Canada"
        },
        "countries": ["Canada"],
        "colleges": ["University of Toronto", "UBC"]
    },
    
    // English proficiency
    "english_proficiency": "ielts",
    "ielts_score": "7.5",
    "toefl_score": null,
    "pte_score": null,
    "other_english_test": null,
    
    // Educational qualifications
    "bachelor_degree": "Bachelor of Science",
    "bachelor_university": "Delhi University",
    "bachelor_percentage": "85.5",
    "bachelor_year": "2020",
    "master_degree": null,
    "master_university": null,
    "master_percentage": null,
    "master_year": null,
    "twelfth_board": "CBSE",
    "twelfth_school": "ABC School",
    "twelfth_percentage": "90.5",
    "twelfth_year": "2016",
    "tenth_board": "CBSE",
    "tenth_school": "ABC School",
    "tenth_percentage": "95.0",
    "tenth_year": "2014",
    
    // Background information
    "visa_refusal_history": false,
    "refusal_details": null,
    "travel_history": true,
    "financial_support": "self",
    "sponsor_name": null,
    "sponsor_relationship": null,
    "estimated_budget": "100000",
    "remarks": "Looking for admission in Fall 2025",
    "status": "pending",
    "visa_counselor": null,
    
    // Employment history
    "employment_history": [
        {
            "start_date": "2020-06-01",
            "end_date": "2024-05-31",
            "company_name": "Tech Corp",
            "position": "Software Developer",
            "location": "Delhi",
            "description": "Developed web applications using Laravel and React"
        }
    ],
    
    // Documents
    "documents": [
        {
            "document_name": "Passport",
            "document_type": "general",
            "original_filename": "passport.pdf",
            "file_size": "2048576",
            "mime_type": "application/pdf",
            "is_mandatory": true,
            "status": "uploaded",
            "file_url": "http://localhost:8000/storage/application_documents/1751301045_0_passport.pdf",
            "uploaded_at": "2025-06-30T16:30:45.000000Z"
        },
        {
            "document_name": "12th Grade Certificate",
            "document_type": "general",
            "original_filename": "12th_certificate.pdf",
            "file_size": "1048576",
            "mime_type": "application/pdf",
            "is_mandatory": true,
            "status": "uploaded",
            "file_url": "http://localhost:8000/storage/application_documents/1751301045_1_12th_certificate.pdf",
            "uploaded_at": "2025-06-30T16:30:45.000000Z"
        }
    ],
    
    // Documents summary
    "documents_summary": {
        "total_count": 2,
        "mandatory_count": 2,
        "optional_count": 0,
        "document_types": ["general"],
        "document_names": ["Passport", "12th Grade Certificate"]
    },
    
    // User who created the application (ENHANCED)
    "created_by_user": {
        "id": 1,
        "name": "Agent Smith",
        "email": "agent@yourcompany.com",
        "phone": "+1-234-567-8900",
        "city": "Toronto",
        "state": "Ontario",
        "zip": "M1M 1M1",
        "company_name": "Education Consultancy Ltd",
        "company_registration_number": "REG123456",
        "gstin": "GST123456789",
        "pan_number": "PAN123456",
        "company_address": "123 Business Street",
        "company_city": "Toronto",
        "company_state": "Ontario",
        "company_pincode": "M1M 1M1",
        "company_phone": "+1-234-567-8901",
        "company_email": "contact@educationconsultancy.com",
        "approval_status": "approved",
        "approved_at": "2025-06-01T10:30:00.000000Z",
        "admin_group_name": "Toronto Branch",
        "admin_id": 2,
        "roles": ["member"],
        "primary_role": "member",
        "is_super_admin": false,
        "is_regular_admin": false,
        "user_type": "member"
    },
    
    // Admin details (if user is under an admin) (NEW)
    "admin_details": {
        "id": 2,
        "name": "John Admin",
        "email": "admin@yourcompany.com",
        "phone": "+1-234-567-8902",
        "company_name": "Education Consultancy Ltd",
        "company_registration_number": "REG123456",
        "gstin": "GST123456789",
        "pan_number": "PAN123456",
        "company_address": "123 Business Street",
        "company_city": "Toronto",
        "company_state": "Ontario",
        "company_pincode": "M1M 1M1",
        "company_phone": "+1-234-567-8901",
        "company_email": "contact@educationconsultancy.com",
        "approval_status": "approved",
        "approved_at": "2025-05-15T09:00:00.000000Z",
        "admin_group_name": "Toronto Branch",
        "is_super_admin": false,
        "total_members": 5,
        "active_members": 4
    },
    
    // Organization/Company hierarchy info (NEW)
    "organization_info": {
        "company_name": "Education Consultancy Ltd",
        "company_registration_number": "REG123456",
        "gstin": "GST123456789",
        "admin_group_name": "Toronto Branch",
        "hierarchy_level": "member",
        "is_independent_admin": false,
        "under_admin": true
    },
    
    // Timestamps
    "created_at": "2025-06-30T16:30:45.000000Z",
    "updated_at": "2025-06-30T16:30:45.000000Z"
}
```

## Key Features

### 1. **Course Options (NEW)**
- Contains all courses selected from the course finder
- Each course option includes detailed information (fees, duration, college details)
- Primary course is marked with `is_primary: true`
- Courses are ordered by `priority_order`

### 2. **Course Options Summary (NEW)**
- Quick overview of all selected courses
- Primary course details for easy reference
- Unique countries and colleges list

### 3. **Document Management**
- Full document details with file URLs
- Document status and metadata
- Summary with counts and types

### 4. **Employment History**
- Complete work experience details
- Formatted dates

### 5. **User Information (ENHANCED)**
- Complete user profile with contact details
- Company registration and business information
- Tax details (GSTIN, PAN)
- User roles and permissions
- Admin status flags

### 6. **Admin Details (NEW)**
- Full admin information when user is a member under an admin
- Admin's company and contact details
- Member count statistics
- Admin approval status and dates

### 7. **Organization Hierarchy (NEW)**
- Company structure and hierarchy information
- Admin group details
- Business relationship mapping
- Independent vs. managed admin identification

### 8. **Admin ID Reference (NEW)**
- Direct reference to the admin ID when user is a member
- Provides quick lookup without parsing admin_details
- null for independent admins and super admins
- Enables efficient admin-member relationship queries

## Admin ID Field Usage

The `admin_id` field in the `created_by_user` section provides direct reference to the admin when the user is a member:

### For Members (admin_id will have a value):
```json
{
    "created_by_user": {
        "id": 3,
        "name": "Agent Smith",
        "admin_id": 2,          // <-- Points to the admin's user ID
        "user_type": "member"
    },
    "admin_details": {
        "id": 2,               // <-- Same as admin_id above
        "name": "Branch Admin"
    }
}
```

### For Admins (admin_id will be null):
```json
{
    "created_by_user": {
        "id": 1,
        "name": "Super Admin",
        "admin_id": null,       // <-- null because this user is an admin
        "user_type": "admin"
    },
    "admin_details": null       // <-- No admin details because user is admin
}
```

This field enables:
- Quick admin lookup without parsing nested objects
- Efficient database queries for admin-member relationships
- Simple validation of user hierarchy
- Direct admin reference for reporting and analytics

## API Payload Summary

The complete API payload now includes **69 fields** organized into the following sections:

### Core Application Data (28 fields)
- Basic personal information
- Contact details
- Passport and nationality info
- Emergency contacts

### Study Information (30 fields)
- Educational qualifications (10th, 12th, Bachelor's, Master's)
- English proficiency scores
- Study preferences
- **Course options with fees and duration (NEW)**
- Background information

### Relationships & Documents (6 fields)
- Employment history
- Document uploads with URLs
- Document summaries

### User & Organization Data (5 fields - ENHANCED)
- **Complete user profile with business details**
- **Admin hierarchy information**
- **Organization structure and company data**
- **Role-based permissions and status**
- **Direct admin ID reference for members**

### Key Enhancements

1. **Course Options Integration** - All selected courses from course finder
2. **Enhanced User Data** - Complete business and personal information
3. **Admin Hierarchy** - Full organizational structure details
4. **Company Information** - Business registration, tax details, contact info
5. **Role Management** - User types, permissions, and admin status
6. **Member Management** - Admin-member relationships and statistics

## User & Admin Scenarios

### Scenario 1: Super Admin
```json
{
    "created_by_user": {
        "id": 1,
        "name": "Super Admin",
        "user_type": "admin",
        "primary_role": "admin",
        "is_super_admin": true,
        "is_regular_admin": false,
        "admin_id": null,
        "roles": ["admin"]
    },
    "admin_details": null,
    "organization_info": {
        "hierarchy_level": "admin",
        "is_independent_admin": true,
        "under_admin": false
    }
}
```

### Scenario 2: Regular Admin
```json
{
    "created_by_user": {
        "id": 2,
        "name": "Branch Admin",
        "user_type": "admin",
        "primary_role": "admin",
        "is_super_admin": false,
        "is_regular_admin": true,
        "admin_id": null,
        "roles": ["admin"],
        "company_name": "Education Branch Ltd"
    },
    "admin_details": null,
    "organization_info": {
        "hierarchy_level": "admin",
        "is_independent_admin": true,
        "under_admin": false
    }
}
```

### Scenario 3: Member under Admin
```json
{
    "created_by_user": {
        "id": 3,
        "name": "Agent Smith",
        "user_type": "member",
        "primary_role": "member",
        "is_super_admin": false,
        "is_regular_admin": false,
        "admin_id": 2,
        "roles": ["member"]
    },
    "admin_details": {
        "id": 2,
        "name": "Branch Admin",
        "company_name": "Education Branch Ltd",
        "total_members": 5,
        "active_members": 4
    },
    "organization_info": {
        "hierarchy_level": "member",
        "is_independent_admin": false,
        "under_admin": true
    }
}
```

## Data Types

| Field Type | Description | Example |
|------------|-------------|---------|
| `string` | Text data | "John Doe" |
| `boolean` | True/false | `true`, `false` |
| `number` | Numeric data | `85.5` |
| `date` | ISO date format | "2025-06-30" |
| `datetime` | ISO datetime format | "2025-06-30T16:30:45.000000Z" |
| `array` | List of items | `["Canada", "USA"]` |
| `object` | Nested data structure | `{"course": "...", "college": "..."}` |
| `null` | No data | `null` |

## API Endpoint

- **Method**: POST
- **Content-Type**: application/json
- **URL**: Configured in `config/services.php` under `external_api.enquiry_lead_url`
- **Timeout**: 30 seconds
- **Retries**: 3 attempts with 1-second delay

## Error Handling

- Non-blocking: Application creation succeeds even if API call fails
- Detailed logging for debugging
- Automatic retry mechanism (3 attempts)
- Graceful fallback on API unavailability

## Usage

This JSON structure is automatically generated when:
1. A new application is created through the B2B portal
2. The external API integration is enabled
3. The API URL is properly configured

The data is sent asynchronously after the application is successfully saved to the local database.
