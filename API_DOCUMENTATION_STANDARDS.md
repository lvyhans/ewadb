# API Documentation Template & Standards

## Overview

This document serves as a template and standard for all API documentation in the system. Use this template for creating documentation for new APIs and maintaining consistency across all endpoints.

---

## Current APIs

### 1. User Approval API (`/api/user-approval/*`)
- **File**: `USER_APPROVAL_API.md`
- **Purpose**: User approval workflow management for 3rd party integration
- **Authentication**: Sanctum Bearer Token
- **Endpoints**: 8 endpoints covering full CRUD operations
- **Status**: ✅ Complete and Production Ready

### 2. Lead Management API (`/api/leads/*`) 
- **File**: `LEAD_MANAGEMENT_API.md`
- **Purpose**: Lead management and follow-up tracking
- **Authentication**: Sanctum Bearer Token
- **Status**: 🔄 Planned/In Development

---

## API Documentation Template

When creating new API documentation, use this template:

```markdown
# {API_NAME} API Documentation

## Overview
Brief description of what this API does and its purpose.

**Base URL:** `{APP_URL}/api`  
**Authentication:** Bearer Token (Sanctum)  
**Content-Type:** `application/json`
**Version:** 1.0

---

## Authentication

All API endpoints require authentication using Laravel Sanctum tokens.

### Get Authentication Token
```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password"
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Login successful",
  "data": {
    "user": {...},
    "token": "1|abc123..."
  }
}
```

---

## Endpoints

### 1. Endpoint Name

Brief description of what this endpoint does.

```http
METHOD /api/endpoint-path
Authorization: Bearer {token}
Content-Type: application/json
```

**Query Parameters:**
- `param1` (required/optional): Description
- `param2` (optional): Description with default value

**Request Body:**
```json
{
  "field1": "value",
  "field2": 123
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Operation completed successfully",
  "data": {
    // Response data
  }
}
```

---

## Error Responses

Document all possible error responses with examples.

---

## Integration Examples

Provide code examples in multiple languages (JavaScript, Python, PHP, etc.)

---

## Change Log

### Version 1.0 (Date)
- Initial release

```

---

## API Development Standards

### 1. **URL Structure**
- Use RESTful conventions
- Prefix all APIs with `/api/`
- Use resource-based URLs: `/api/users`, `/api/leads`
- Use HTTP verbs correctly (GET, POST, PUT, DELETE)

### 2. **Authentication**
- All APIs use Laravel Sanctum Bearer tokens
- Include authentication requirements in documentation
- Provide token generation examples

### 3. **Response Format**
Standardize all API responses:

```json
{
  "status": "success|error",
  "message": "Human readable message",
  "data": {
    // Actual response data
  },
  "errors": {
    // Validation errors (if any)
  }
}
```

### 4. **HTTP Status Codes**
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

### 5. **Pagination**
For list endpoints, use consistent pagination:

```json
{
  "status": "success",
  "data": {
    "current_page": 1,
    "data": [...],
    "per_page": 15,
    "total": 100,
    "last_page": 7
  }
}
```

### 6. **Validation**
- Validate all input data
- Return descriptive error messages
- Use Laravel Form Request classes

### 7. **Security**
- All endpoints require authentication
- Implement proper authorization
- Validate user permissions
- Sanitize input data

---

## Documentation Checklist

When creating new API documentation, ensure you include:

- [ ] **Overview** - Purpose and description
- [ ] **Authentication** - How to get and use tokens
- [ ] **Base URL and headers**
- [ ] **All endpoints** with examples
- [ ] **Request/response formats**
- [ ] **Error responses** with status codes
- [ ] **Query parameters** with descriptions
- [ ] **Request body schemas**
- [ ] **Integration examples** in multiple languages
- [ ] **Rate limiting information**
- [ ] **Security considerations**
- [ ] **Change log** with version history
- [ ] **Support contact information**

---

## Integration Testing

For each API, create comprehensive tests:

1. **Authentication Tests**
   - Valid token access
   - Invalid token rejection
   - Missing token handling

2. **Endpoint Tests**
   - All CRUD operations
   - Input validation
   - Error handling
   - Edge cases

3. **Security Tests**
   - Authorization checks
   - Permission validation
   - Data access restrictions

---

## Future API Development

### Planned APIs:

1. **Lead Management API**
   - CRUD operations for leads
   - Follow-up management
   - Lead status tracking
   - Reporting and analytics

2. **Settings API**
   - System configuration management
   - User preferences
   - Application settings

3. **Reporting API**
   - Dashboard data
   - Export functionality
   - Custom reports

### Development Workflow:

1. **Planning**
   - Define API requirements
   - Design endpoint structure
   - Plan authentication/authorization

2. **Implementation**
   - Create controllers and models
   - Implement validation
   - Add authentication middleware

3. **Testing**
   - Unit tests for all methods
   - Integration tests for workflows
   - Security testing

4. **Documentation**
   - Use template provided above
   - Include examples and integration guides
   - Maintain change log

5. **Deployment**
   - Test in staging environment
   - Performance testing
   - Production deployment

---

## Best Practices

1. **Consistency** - Follow established patterns
2. **Documentation** - Keep docs updated with code changes
3. **Versioning** - Plan for API versioning from the start
4. **Performance** - Optimize queries and responses
5. **Security** - Always validate and authorize
6. **Testing** - Comprehensive test coverage
7. **Monitoring** - Log API usage and errors

---

**Last Updated**: June 17, 2025  
**Maintained by**: Development Team
