# User Profiling Subsystem Implementation

## Overview
This document outlines the implementation of the User Profiling subsystem with account verification functionality for the Barangay Management System. The system follows HCI design principles and maintains 3NF database normalization.

## Features Implemented

### 1. Enhanced User Registration
- **First Name & Last Name**: Separate fields for better data organization
- **Username**: Unique identifier for users
- **Email & Gmail**: Dual email system for verification
- **Password**: Secure password with confirmation
- **ID Image Upload**: Required image upload for identity verification
- **Form Validation**: Comprehensive client and server-side validation

### 2. Account Status Management
- **Pending**: Default status for new registrations
- **Approved**: Admin-approved accounts that can access the system
- **Rejected**: Rejected accounts with reason tracking

### 3. Admin Account Management
- **Account Review**: View all user accounts with filtering options
- **ID Verification**: View uploaded ID images for verification
- **Approval/Rejection**: One-click approval or rejection with reason
- **Account Details**: Comprehensive view of user information
- **Account Editing**: Update user information as needed

### 4. Authentication Flow
- **Registration**: Users register with ID upload and are set to pending
- **Pending Page**: Users see a waiting page after registration
- **Login Validation**: System checks account status before allowing login
- **Rejected Access**: Rejected users see rejection reason and cannot access system
- **Approved Access**: Approved users can access the resident dashboard

## Database Schema (3NF Compliant)

### Users Table Additions
```sql
- first_name (VARCHAR)
- last_name (VARCHAR) 
- username (VARCHAR, UNIQUE)
- gmail (VARCHAR, UNIQUE)
- account_status (ENUM: pending, approved, rejected)
- id_image_path (VARCHAR)
- rejection_reason (TEXT)
- approved_at (TIMESTAMP)
- rejected_at (TIMESTAMP)
```

## File Structure

### Controllers
- `AuthController.php` - Enhanced with new registration and login logic
- `UserAccountController.php` - New controller for admin account management

### Models
- `User.php` - Enhanced with new fields and helper methods

### Views
- `auth/register.blade.php` - Updated registration form
- `auth/pending.blade.php` - New pending account page
- `admin/users.blade.php` - Updated admin user management
- `admin/user_account_show.blade.php` - Detailed user view
- `admin/user_account_edit.blade.php` - User editing form

### Migrations
- `2025_01_20_000000_add_user_profiling_fields.php` - Database schema update

## Routes Added

### Guest Routes
- `GET /account/pending` - Pending account page

### Admin Routes
- `GET /admin/user-accounts` - List all user accounts
- `GET /admin/user-accounts/{user}` - View user details
- `GET /admin/user-accounts/{user}/edit` - Edit user form
- `PUT /admin/user-accounts/{user}` - Update user
- `DELETE /admin/user-accounts/{user}` - Delete user
- `POST /admin/user-accounts/{user}/approve` - Approve account
- `POST /admin/user-accounts/{user}/reject` - Reject account
- `GET /admin/user-accounts/filter/pending` - Filter pending accounts
- `GET /admin/user-accounts/filter/approved` - Filter approved accounts
- `GET /admin/user-accounts/filter/rejected` - Filter rejected accounts

## HCI Design Principles Applied

### 1. User-Centered Design
- Clear form labels and instructions
- Intuitive navigation flow
- Consistent visual design language

### 2. Accessibility
- Proper form labels and ARIA attributes
- Keyboard navigation support
- Clear error messages and feedback

### 3. Usability
- Step-by-step registration process
- Clear status indicators
- Confirmation dialogs for critical actions

### 4. Visual Design
- Consistent color scheme (blue for primary actions, red for danger, green for success)
- Proper spacing and typography
- Responsive design for different screen sizes

## Testing Instructions

### 1. Registration Flow
1. Navigate to `/register`
2. Fill in all required fields (first name, last name, username, email, gmail, password)
3. Upload an ID image (JPEG, PNG, JPG, GIF, max 2MB)
4. Submit the form
5. Verify you're redirected to the pending page

### 2. Admin Account Management
1. Login as admin
2. Navigate to `/admin/user-accounts`
3. View the list of user accounts
4. Click "View" to see account details
5. Click "Approve" or "Reject" to change account status
6. Test filtering by status (pending, approved, rejected)

### 3. Login Validation
1. Try to login with a pending account - should show pending message
2. Try to login with a rejected account - should show rejection reason
3. Login with an approved account - should access resident dashboard

### 4. File Upload
1. Verify ID images are stored in `storage/app/public/id_images/`
2. Check that images are accessible via the storage link
3. Test image viewing in admin panel

## Security Features

### 1. File Upload Security
- File type validation (images only)
- File size limits (2MB max)
- Secure file storage outside web root

### 2. Data Validation
- Server-side validation for all inputs
- Unique constraints on username, email, and gmail
- SQL injection protection through Eloquent ORM

### 3. Access Control
- Admin-only access to account management
- Status-based login restrictions
- CSRF protection on all forms

## Future Enhancements

### 1. Email Notifications
- Send approval/rejection emails to users
- Email notifications for status changes

### 2. Advanced ID Verification
- OCR text extraction from ID images
- Automated ID validation checks

### 3. Audit Trail
- Log all account status changes
- Track admin actions for accountability

### 4. Bulk Operations
- Bulk approve/reject multiple accounts
- Export user data to CSV/Excel

## Maintenance Notes

### 1. File Cleanup
- Implement periodic cleanup of rejected account files
- Monitor storage usage for ID images

### 2. Database Maintenance
- Regular backup of user data
- Index optimization for large user tables

### 3. Security Updates
- Regular security patches
- Monitor for new vulnerabilities

## Conclusion

The User Profiling subsystem has been successfully implemented with all requested features:
- ✅ Resident account creation with ID verification
- ✅ Admin approval/rejection workflow
- ✅ Three status system (pending, approved, rejected)
- ✅ Proper authentication flow based on account status
- ✅ HCI design principles applied
- ✅ 3NF database normalization maintained

The system is ready for production use and provides a secure, user-friendly account management experience for both residents and administrators.
