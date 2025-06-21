# Simplified Admin Hierarchy System Documentation

## Overview

The user management system now supports **one super admin and multiple regular admins with isolated member groups**. Each admin can only see and manage their own members, while the super admin remains hidden from the dashboard but can see everything.

## Key Features

### 🏗️ Admin Hierarchy Structure
- **Super Admin**: Hidden from dashboard, can see and manage ALL users and admins
- **Regular Admins**: Can only see and manage their own assigned members (not other admins)
- **Members**: Belong to specific admin groups and can only see users within their group

### 🔒 Data Isolation
- Super admin is completely hidden from all dashboards
- Regular admins cannot see other admins or their members
- Members cannot see users from other admin groups
- Complete separation of user data between different admin hierarchies

## Admin Creation Process

### Role System Structure
The system uses **only two roles**:
- **Administrator**: Full system admin with hierarchy management capabilities
- **Member**: Regular user assigned to admin groups with limited permissions

### Creating Regular Admin Accounts

**Method 1: Public Registration (Recommended)**
- Admin candidates register through the public registration form
- Complete company information and document uploads required
- Approval process follows standard workflow
- First registered user automatically becomes super admin

**Method 2: Super Admin Creation (Administrative)**
- Only super admin can access admin creation form
- Navigate to User Management → "Create Admin" button
- Simplified form with basic information required
- Admin accounts are automatically approved and activated

### User Management Interface Restrictions

**From User Management Dashboard:**
- ✅ **Super Admin**: Can create both members and admins
- ✅ **Regular Admin**: Can create members only (auto-assigned to their group)
- ❌ **Members**: Cannot create any accounts
- ❌ **Admin Role Assignment**: Never available through user management forms
- ❌ **Admin Account Modification**: Regular admins cannot edit/delete admin accounts

**Role Assignment Logic:**
- User management forms show **only Member role** for selection
- All new users created through dashboard get "Member" role by default
- Admin roles can only be assigned during registration or by super admin through special forms
- **No other roles exist** - system simplified to Administrator + Member only

## Database Schema Changes

### New Columns in `users` table:
- `admin_id` (Foreign Key): References the admin who manages this user
- `admin_group_name` (String): Custom name for the admin group
- Indexes added for performance optimization

## Admin Management Commands

### Create New Admin
```bash
php artisan admin:hierarchy create-admin --admin-email="newadmin@example.com"
```

### Assign Member to Admin
```bash
php artisan admin:hierarchy assign --admin-email="admin@example.com" --member-email="user@example.com" --group-name="Custom Group Name"
```

### Remove Member from Admin
```bash
php artisan admin:hierarchy remove --member-email="user@example.com"
```

### View Hierarchy Overview
```bash
php artisan admin:hierarchy list
```

## API Changes

### User Approval API
All user approval endpoints now respect admin hierarchy:

- `GET /api/user-approval/users` - Returns only users accessible to the current admin
- `GET /api/user-approval/users/pending` - Shows pending users in admin's group only
- Other approval endpoints filter by admin hierarchy automatically

### Authentication Required
All API endpoints require authentication and will filter results based on the authenticated user's admin level and group membership.

## Web Interface Changes

### User Management Dashboard
- **Admin Group Column**: Shows admin hierarchy information **only to super admin**
- **Regular Admin View**: Simplified table without admin group column
- **Member Count**: Displays how many members each admin manages (super admin view only)
- **Filtered Views**: Users only see members within their accessible scope

### Visual Indicators
- 🟣 **Purple badges** for admins with member counts (super admin view)
- 🔵 **Green badges** for group members 
- ⚪ **Gray indicators** for independent/orphaned users (super admin view)
- **Simplified Interface**: Regular admins see clean, minimal user table

## Access Control Rules

### Super Admin
- ✅ Can access ALL users and admins
- ✅ Can create/edit/delete any user
- ✅ Can assign/reassign admin hierarchies
- ✅ **HIDDEN from dashboard** - completely invisible to other users
- ✅ Cannot be deleted or demoted
- ✅ **Can create new admin accounts** through special admin registration form
- ✅ Only role that can manage admin accounts

### Regular Admins
- ✅ Can access their assigned members ONLY
- ✅ Can create new **member users only** (automatically assigned to their group)
- ✅ Can edit/delete their member users only
- ❌ Cannot see other admins or their members
- ❌ Cannot see the super admin
- ❌ **Cannot create admin accounts** - must use public registration
- ❌ **Cannot edit or delete admin accounts** (including themselves)
- ❌ Cannot assign admin roles through user management interface

### Members
- ✅ Can access themselves
- ✅ Can view other members in their group
- ❌ Cannot see their admin (admin is not visible to members)
- ❌ Cannot access users from other groups
- ❌ Cannot perform admin functions
- ❌ **Cannot create any user accounts**

## Security Features

### Middleware Protection
- `AdminHierarchyMiddleware` enforces access control on all user management routes
- Automatic redirection with error messages for unauthorized access attempts
- Route-level parameter binding checks for user access rights

### Controller-Level Filtering
- All user queries automatically filtered by admin hierarchy
- Database-level access control using Eloquent scopes
- Consistent filtering across all user management features

## Registration Flow Updates

### New User Registration
1. User registers through public form
2. System checks for admin invitation tokens
3. User automatically assigned to appropriate admin group
4. Admin receives notification of new member (if applicable)
5. Standard approval process follows with hierarchy respect

### Admin Invitation System
- URL parameters can specify which admin is inviting
- Fallback to first available admin if no specific admin
- Automatic group naming based on admin preferences

## Testing the System

### Verify Hierarchy Setup
```bash
# View current hierarchy
php artisan admin:hierarchy list

# Create test admin
php artisan admin:hierarchy create-admin --admin-email="testadmin@example.com"

# Assign test member
php artisan admin:hierarchy assign --admin-email="testadmin@example.com" --member-email="testuser@example.com"
```

### Test Access Control
1. Login as different admin users
2. Navigate to user management
3. Verify you can only see your own members
4. Attempt to access other admin's users (should be blocked)

## Migration Commands

### Apply Database Changes
```bash
php artisan migrate
```

### Seed Required Roles
```bash
php artisan db:seed --class=RoleSeeder
```

## Troubleshooting

### Common Issues

**Issue**: "Admin role not found"
**Solution**: Run `php artisan db:seed --class=RoleSeeder`

**Issue**: Users not showing in admin dashboard
**Solution**: Check admin hierarchy assignments with `php artisan admin:hierarchy list`

**Issue**: Access denied errors
**Solution**: Verify user has proper admin role and hierarchy assignments

### Debug Commands
```bash
# Check user roles and assignments
php artisan tinker
>>> App\Models\User::with(['roles', 'admin', 'members'])->get()

# Verify middleware is working
>>> Route::getRoutes()->match(request())
```

## Performance Considerations

### Database Optimization
- Indexes added on `admin_id` and `approval_status` columns
- Eager loading of relationships in controllers
- Efficient query scoping to minimize database hits

### Caching Recommendations
- Consider caching admin hierarchy structure for high-traffic applications
- Cache user role assignments to reduce database queries
- Implement Redis caching for user session data

## Conclusion

The admin hierarchy system provides complete data isolation between different admin groups while maintaining the flexibility of role-based permissions. This ensures that each admin operates in their own secure environment without compromising the overall system security or functionality.

## Next Steps

Consider implementing:
1. **Email notifications** for admin-member assignments
2. **Bulk user import** with automatic admin assignment
3. **Admin delegation** features for temporary access
4. **Audit logging** for hierarchy changes
5. **Custom group permissions** beyond standard roles
