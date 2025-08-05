# B2B Application Management System

A comprehensive Laravel-based application management system for educational institutions and students.

## Features

### 🎓 College Filter API Integration
- **Real-time Data**: Integrated with unified College Filter API for live country, city, college, and course data
- **Cascading Dropdowns**: Intelligent filtering - select country → cities → colleges → courses
- **Fallback Protection**: Graceful degradation to test data if external API is unavailable
- **Performance Optimized**: Efficient API calls only when needed

### 📋 Application Management
- Lead creation and tracking
- Application form with dynamic college/course selection
- Document upload and management
- Application status tracking
- Multi-course application support

### 🔄 Task Management Integration
- External task system integration
- Webhook support for status updates
- Task filtering and monitoring
- Real-time status synchronization

### 👥 User Management
- Role-based access control
- User approval workflow
- Admin hierarchy management
- Authentication with Sanctum

## College Filter API

The application integrates with a unified College Filter API that provides:

```json
POST https://tarundemo.innerxcrm.com/b2bapi/adform
Content-Type: application/json

// Get countries
{}

// Get cities
{"country": "Canada"}

// Get colleges  
{"country": "Canada", "city": "Toronto"}

// Get courses
{"country": "Canada", "city": "Toronto", "college": "Seneca College"}
```

### API Endpoints
- `GET /api/dropdown/countries` - List all countries
- `GET /api/dropdown/cities?country={country}` - Cities by country
- `GET /api/dropdown/colleges?country={country}&city={city}` - Colleges by location
- `GET /api/dropdown/courses?country={country}&city={city}&college={college}` - Courses by college
- `POST /api/dropdown/college-filter` - Direct unified API access

## Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```

3. Configure environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Set up database:
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. Configure external APIs in `.env`:
   ```bash
   EXTERNAL_API_ENABLED=true
   EXTERNAL_API_ENQUIRY_LEAD_URL=https://tarundemo.innerxcrm.com/b2bapi/enquirylead
   EXTERNAL_API_CHECKLIST_URL=https://tarundemo.innerxcrm.com/b2bapi/checklist
   EXTERNAL_API_COLLEGE_FILTER_URL=https://tarundemo.innerxcrm.com/b2bapi/adform
   ```

## Testing

### Test College Filter API Integration
```bash
# Laravel command
php artisan test:college-filter-api

# Direct PHP test
php test_college_filter_api.php

# Frontend test
http://localhost:8000/college-filter-test.html
```

### Run Application Tests
```bash
php artisan test
```

## API Documentation

- **College Filter API**: See `COLLEGE_FILTER_API_INTEGRATION.md`
- **Task Management**: See `TASK_MANAGEMENT_README.md`  
- **Lead Access Control**: See `LEAD_ACCESS_CONTROL_IMPLEMENTATION.md`

## Key Files

### College Filter Integration
- `app/Services/ExternalApiService.php` - API service layer
- `app/Http/Controllers/ApplicationController.php` - API endpoints
- `app/Console/Commands/TestCollegeFilterApi.php` - Test command
- `resources/views/applications/create.blade.php` - Application form with cascading dropdowns

### Configuration
- `.env` - Environment configuration
- `routes/api.php` - API routes
- `config/services.php` - External service configuration

## Contributing

Please follow Laravel coding standards and include tests for new features.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
