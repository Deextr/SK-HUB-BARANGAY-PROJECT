# Database Seeders Documentation

## Overview
This project includes comprehensive database seeders to populate the system with test data for development and testing purposes.

## Available Seeders

### 1. ServiceSeeder
Creates 3 services with the following capacities:
- **PC**: 8 units - Personal Computer workstations with internet access
- **Study Area**: 8 units - Quiet study spaces with desk and chair
- **TV**: 1 unit - Television viewing area

### 2. ResidentAccountsSeeder
Creates **100 resident accounts** with the following distribution:
- **30 accounts** with status: `pending` (awaiting approval)
- **40 accounts** with status: `approved` (active users)
- **30 accounts** with status: `rejected` (rejected applications)

**Account Details:**
- All accounts use password: `123123123`
- Filipino names (realistic first and last names)
- Random ages between 12-24 years old (students)
- Random sex (Male/Female)
- 10% chance of being PWD (Person with Disability)
- Realistic email addresses based on names

Creates **1000 reservations** for the year **2025** with the following distribution:
- **600 completed** reservations (past dates: Jan 1 - Dec 14)
- **250 cancelled** reservations (past dates: Jan 1 - Dec 14)
- **150 pending** reservations (future dates: Dec 15 - Dec 31)

**Reservation Details:**
- Distributed across all 3 services (PC, Study Area, TV)
- Random dates throughout 2025 (January to December)
- Time slots from 8:00 AM to 5:00 PM
- Realistic reservation reasons: **Surfing**, **Reading**, **Making Activity**, **Others** (with custom text)
- Cancelled reservations include cancellation reasons
- 10% of cancelled reservations have suspension applied

## How to Run Seeders

### Option 1: Run All Seeders
To run all seeders at once (recommended for fresh database):

```bash
php artisan db:seed
```

This will run seeders in the following order:
1. AdminSeeder (creates admin account)
2. ServiceSeeder (creates services)
3. ResidentAccountsSeeder (creates 100 resident accounts)
4. ReservationSeeder (creates 1000 reservations)

### Option 2: Run Individual Seeders
To run specific seeders individually:

```bash
# Seed services only
php artisan db:seed --class=ServiceSeeder

# Seed resident accounts only
php artisan db:seed --class=ResidentAccountsSeeder

# Seed reservations only (requires services and users to exist)
php artisan db:seed --class=ReservationSeeder
```

### Option 3: Fresh Migration with Seeding
To reset the database and run all migrations with seeders:

```bash
php artisan migrate:fresh --seed
```

⚠️ **Warning**: This will drop all tables and recreate them. Use only in development!

## Seeder Dependencies

The seeders must be run in the correct order due to foreign key relationships:

1. **ServiceSeeder** - Must run before ReservationSeeder
2. **ResidentAccountsSeeder** - Must run before ReservationSeeder
3. **ReservationSeeder** - Requires both services and approved users to exist

## Test Credentials

### Admin Account
- Email: `admin@example.com`
- Password: (check AdminSeeder.php)

### Resident Accounts
- Email: Various (e.g., `juan.santos123@example.com`)
- Password: `123123123` (all accounts)

## Data Statistics

After running all seeders, your database will contain:
- **1 Admin account**
- **3 Services** (PC: 8 units, Study Area: 8 units, TV: 1 unit)
- **100 Resident accounts** (30 pending, 40 approved, 30 rejected)
- **1000 Reservations** (600 completed, 250 cancelled, 150 pending)

## Notes

- All seeded data uses realistic Filipino names and information
- Dates are randomized to provide varied test data
- The reservation seeder creates data specifically for 2025
- Cancelled reservations have realistic cancellation reasons
- Some users may have suspension counts if their cancelled reservations applied suspensions

## Troubleshooting

### Error: "No approved users found!"
Run `ResidentAccountsSeeder` before `ReservationSeeder`:
```bash
php artisan db:seed --class=ResidentAccountsSeeder
php artisan db:seed --class=ReservationSeeder
```

### Error: "No services found!"
Run `ServiceSeeder` before `ReservationSeeder`:
```bash
php artisan db:seed --class=ServiceSeeder
php artisan db:seed --class=ReservationSeeder
```

### Memory Issues
If you encounter memory issues with large seeders, try increasing PHP memory limit:
```bash
php -d memory_limit=512M artisan db:seed
```
