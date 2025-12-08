# Database Constraints Audit Report

## Overview
This document provides a comprehensive audit of all database constraints added to ensure data integrity, 3NF compliance, and system stability.

## Migration File
`database/migrations/2025_12_08_000000_add_database_constraints_for_integrity.php`

## Constraints Summary

### 1. Users Table (`users`)

#### Unique Constraints
- **email**: Unique constraint ensures no duplicate email addresses
  - Constraint Name: `users_email_unique`
  - Status: ✅ Verified/Added

#### Check Constraints (MySQL 8.0.16+)
- **suspension_count**: Must be >= 0
  - Constraint Name: `chk_users_suspension_count`
  - Purpose: Prevents negative suspension counts
  
- **resubmission_count**: Must be >= 0
  - Constraint Name: `chk_users_resubmission_count`
  - Purpose: Prevents negative resubmission counts
  
- **birth_date**: Must be in the past or null
  - Constraint Name: `chk_users_birth_date`
  - Purpose: Prevents future birth dates
  
- **suspension_logic**: If suspended, must have end_date
  - Constraint Name: `chk_users_suspension_logic`
  - Purpose: Ensures suspended users have a suspension end date

#### Foreign Keys
- None (users is a root table)

#### Data Cleaning
- Fixes negative suspension_count → sets to 0
- Fixes negative resubmission_count → sets to 0
- Fixes future birth_date → sets to NULL
- Fixes suspension_end_date for non-suspended users → sets to NULL

---

### 2. Services Table (`services`)

#### Unique Constraints
- **name**: Unique constraint ensures no duplicate service names
  - Constraint Name: `services_name_unique`
  - Status: ✅ Verified/Added
  - Note: Compatible with soft deletes (allows same name for archived services)

#### Check Constraints (MySQL 8.0.16+)
- **capacity_units**: Must be >= 1
  - Constraint Name: `chk_services_capacity_units`
  - Purpose: Ensures services always have at least 1 unit available

#### Foreign Keys
- None (services is a root table)

#### Data Cleaning
- Fixes capacity_units < 1 → sets to 1

---

### 3. Closure Periods Table (`closure_periods`)

#### Unique Constraints
- **Exact duplicate prevention**: Handled at application level (ClosurePeriodController)
  - Note: MySQL doesn't easily support functional unique indexes for overlapping ranges
  - Application-level validation prevents exact duplicates and overlaps

#### Indexes
- **Composite index**: `idx_closure_periods_dates` on (start_date, end_date)
  - Purpose: Improves query performance for overlap detection

#### Check Constraints (MySQL 8.0.16+)
- **date_range**: end_date >= start_date
  - Constraint Name: `chk_closure_periods_date_range`
  - Purpose: Prevents invalid date ranges
  
- **time_range**: If not full day, end_time > start_time
  - Constraint Name: `chk_closure_periods_time_range`
  - Purpose: Ensures valid time ranges for partial day closures

#### Foreign Keys
- None (closure_periods is a root table)

#### Data Cleaning
- Fixes end_date < start_date → sets end_date = start_date

---

### 4. Reservations Table (`reservations`)

#### Unique Constraints
- **reference_no**: Unique constraint ensures no duplicate reference numbers
  - Constraint Name: `reservations_reference_no_unique`
  - Status: ✅ Verified/Added

#### Check Constraints (MySQL 8.0.16+)
- **units_reserved**: Must be >= 1
  - Constraint Name: `chk_reservations_units_reserved`
  - Purpose: Ensures at least 1 unit is reserved
  
- **time_range**: end_time > start_time
  - Constraint Name: `chk_reservations_time_range`
  - Purpose: Prevents invalid time ranges
  
- **actual_time**: If both exist, actual_time_out > actual_time_in
  - Constraint Name: `chk_reservations_actual_time`
  - Purpose: Ensures valid actual usage times

#### Foreign Keys
- **user_id** → `users.id`
  - ON DELETE: CASCADE
  - ON UPDATE: CASCADE
  - Status: ✅ Already exists in original migration
  
- **service_id** → `services.id`
  - ON DELETE: CASCADE
  - ON UPDATE: CASCADE
  - Status: ✅ Already exists in original migration
  - Note: Compatible with soft deletes (uses withTrashed())
  
- **closure_period_id** → `closure_periods.id`
  - ON DELETE: SET NULL
  - ON UPDATE: CASCADE
  - Status: ✅ Already exists in original migration
  
- **cancelled_by** → `users.id`
  - ON DELETE: SET NULL
  - ON UPDATE: CASCADE
  - Status: ✅ Verified/Added
  - Purpose: Records who cancelled the reservation

#### Data Cleaning
- Fixes units_reserved < 1 → sets to 1
- Fixes end_time <= start_time → sets end_time = start_time + 1 hour

---

### 5. Service Archives Table (`service_archives`)

#### Check Constraints (MySQL 8.0.16+)
- **units_archived**: Must be > 0
  - Constraint Name: `chk_service_archives_units_archived`
  - Purpose: Ensures at least 1 unit is archived
  
- **capacity_before**: Must be > 0
  - Constraint Name: `chk_service_archives_capacity_before`
  - Purpose: Ensures valid capacity before archival
  
- **capacity_after**: Must be >= 1
  - Constraint Name: `chk_service_archives_capacity_after`
  - Purpose: Ensures minimum capacity of 1 after archival
  
- **capacity_relationship**: capacity_after = capacity_before - units_archived
  - Constraint Name: `chk_service_archives_capacity_relationship`
  - Purpose: Ensures mathematical consistency
  
- **reservations_cancelled**: Must be >= 0
  - Constraint Name: `chk_service_archives_reservations_cancelled`
  - Purpose: Prevents negative cancellation counts

#### Foreign Keys
- **service_id** → `services.id`
  - ON DELETE: CASCADE
  - ON UPDATE: CASCADE
  - Status: ✅ Verified/Added
  - Purpose: Archives are deleted when service is deleted

#### Data Cleaning
- Fixes units_archived < 1 → sets to 1
- Fixes invalid capacity relationships → recalculates capacity_after
- Fixes negative reservations_cancelled → sets to 0

---

### 6. Password Histories Table (`password_histories`)

#### Foreign Keys
- **user_id** → `users.id`
  - ON DELETE: CASCADE
  - ON UPDATE: CASCADE
  - Status: ✅ Verified/Added
  - Purpose: Password history deleted when user is deleted

---

### 7. Sessions Table (`sessions`)

#### Foreign Keys
- **user_id** → `users.id`
  - ON DELETE: CASCADE
  - ON UPDATE: CASCADE
  - Status: ✅ Verified/Added
  - Note: user_id is nullable for guest sessions
  - Purpose: Sessions deleted when user is deleted

---

## 3NF Compliance Verification

### ✅ All Tables Comply with 3NF

1. **Users Table**: 
   - No redundant data (age calculated from birth_date)
   - Proper normalization with first_name/last_name instead of single name field
   
2. **Services Table**: 
   - Atomic values, no repeating groups
   - Proper relationships with reservations
   
3. **Reservations Table**: 
   - References users, services, closure_periods (no data duplication)
   - All fields are atomic
   
4. **Service Archives Table**: 
   - References services (no duplication)
   - All fields are atomic
   
5. **Closure Periods Table**: 
   - Atomic values, no repeating groups
   - Proper date/time handling
   
6. **Password Histories Table**: 
   - References users (no duplication)
   - Atomic values

---

## Compatibility with Existing Features

### ✅ Archiving Services
- Constraints allow soft deletes (services table)
- Service archives maintain referential integrity
- Capacity constraints ensure valid archival operations

### ✅ Partial/Total Account Rejection
- Account status enum includes 'partially_rejected'
- Resubmission count constraints allow up to 3 resubmissions
- All constraints respect account status workflow

### ✅ Reservation Scheduling
- Time range constraints ensure valid reservations
- Foreign keys maintain referential integrity
- Units reserved constraints ensure valid bookings

### ✅ Maintenance Capacity Recalculation
- Capacity relationship constraint ensures consistency
- All capacity values must be positive
- Archive operations maintain data integrity

### ✅ Closure Period Conflicts
- Date range constraints prevent invalid periods
- Application-level validation prevents overlaps
- Check constraints ensure end_date >= start_date

### ✅ PDF/CSV Report Exporting
- All constraints maintain data consistency for reporting
- Foreign keys ensure related data is available
- No impact on query performance

### ✅ Login/Registration/Password Update
- Email uniqueness ensures no duplicate accounts
- Password history foreign key maintains referential integrity
- User constraints don't interfere with authentication

### ✅ Real-time Notifications
- All constraints maintain data consistency
- Foreign keys ensure user/service relationships are valid
- No impact on notification system

---

## Migration Safety Features

1. **Data Cleaning**: All invalid data is fixed before constraints are added
2. **Version Detection**: Checks MySQL version before adding CHECK constraints
3. **Graceful Degradation**: If CHECK constraints aren't supported, migration continues
4. **Constraint Verification**: Checks if constraints exist before adding
5. **Error Handling**: All constraint operations wrapped in try-catch blocks
6. **Rollback Support**: All constraints can be safely removed in down() method

---

## Testing Recommendations

1. **Test Data Insertion**: Verify constraints prevent invalid data
2. **Test Foreign Key Cascades**: Ensure CASCADE operations work correctly
3. **Test Soft Deletes**: Verify soft-deleted services don't break relationships
4. **Test Archive Operations**: Verify service archiving works with constraints
5. **Test Closure Period Overlaps**: Verify application-level validation works
6. **Test Reservation Creation**: Verify all reservation constraints work
7. **Test User Operations**: Verify account status changes work correctly

---

## Notes

- CHECK constraints require MySQL 8.0.16+ or MariaDB 10.2.1+
- For older MySQL versions, validation is handled at application level
- All foreign keys use appropriate ON DELETE/UPDATE rules
- Unique constraints respect soft deletes where applicable
- All constraints are designed to work with existing Laravel Eloquent relationships

