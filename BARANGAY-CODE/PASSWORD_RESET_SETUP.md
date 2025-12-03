# Password Reset Implementation Guide

## Overview
This document explains how to set up and use the Laravel built-in password reset feature with Gmail SMTP in your Barangay System.

---

## What Was Implemented

### 1. **Routes** (4 routes added)
- `GET /forgot-password` → Show forgot password form
- `POST /forgot-password` → Send reset link to email
- `GET /reset-password/{token}` → Show reset password form
- `POST /reset-password` → Update password in database

### 2. **Controller Methods** (4 methods in AuthController)
- `showForgotPasswordForm()` - Returns forgot password view
- `sendPasswordResetLink()` - Validates email and sends reset link
- `showResetPasswordForm($token)` - Returns reset password form with token
- `resetPassword()` - Validates and updates password

### 3. **Views** (2 new views created)
- `resources/views/auth/forgot_password.blade.php` - Forgot password form
- `resources/views/auth/reset_password.blade.php` - Reset password form with password show/hide toggle

### 4. **Custom Notification**
- `app/Notifications/ResetPasswordNotification.php` - Custom email template
- `User::sendPasswordResetNotification()` - Method in User model to use custom notification

### 5. **Database**
- Uses existing `password_reset_tokens` table (Laravel default)
- No new migrations needed

---

## Step 1: Configure Gmail SMTP

### Get Gmail App Password

1. Go to **https://myaccount.google.com/security**
2. Enable **2-Step Verification** (if not already enabled)
3. Go to **App passwords** (appears after 2FA is enabled)
4. Select:
   - App: **Mail**
   - Device: **Windows Computer** (or your OS)
5. Google will generate a **16-character password**
6. Copy this password

### Update `.env` File

Edit your `.env` file and add/update these lines:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=your-16-char-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-gmail@gmail.com
MAIL_FROM_NAME="Barangay System"
```

**Replace:**
- `your-gmail@gmail.com` with your actual Gmail address
- `your-16-char-app-password` with the 16-character password from Google

### Example `.env` Configuration

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=barangay.system@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=barangay.system@gmail.com
MAIL_FROM_NAME="Barangay System"
```

---

## Step 2: How It Works

### User Flow

1. **User clicks "Forgot Password?"** on login page
2. **User enters email address** on `/forgot-password` form
3. **System sends reset link** to user's email
4. **User clicks link** in email (valid for 60 minutes)
5. **User enters new password** on reset form
6. **Password is updated** and user can log in

### Behind the Scenes

1. When user submits email:
   - Laravel checks if email exists in `users` table
   - Generates a cryptographically secure token
   - Stores token in `password_reset_tokens` table
   - Sends email with reset link containing token

2. When user clicks reset link:
   - Token is validated (not expired, exists)
   - User sees reset password form

3. When user submits new password:
   - Token is validated again
   - Password is hashed and updated
   - Token is deleted (single-use only)
   - User is redirected to login

---

## Step 3: Testing the Feature

### Test Locally (Without Sending Real Emails)

If you want to test without sending real emails, change `.env`:

```env
MAIL_MAILER=log
```

This logs emails to `storage/logs/laravel.log` instead of sending them.

### Test With Real Gmail

1. Make sure `.env` is configured with Gmail SMTP
2. Start your Laravel server: `php artisan serve`
3. Go to `http://localhost:8000/forgot-password`
4. Enter a resident's email address
5. Check the email inbox for the reset link
6. Click the link and reset the password
7. Log in with the new password

### Troubleshooting

**Email not sending?**
- Check `.env` configuration is correct
- Verify Gmail app password is correct (16 characters)
- Check that 2FA is enabled on Gmail account
- Look at `storage/logs/laravel.log` for errors

**Reset link expired?**
- Default expiry is 60 minutes (configurable in `config/auth.php`)
- User must request a new reset link

**Token invalid?**
- Token is single-use (deleted after successful reset)
- User must request a new reset link

---

## Step 4: Customization Options

### Change Token Expiry Time

Edit `config/auth.php`:

```php
'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
        'expire' => 60,  // Change this (in minutes)
        'throttle' => 60, // Cooldown between requests
    ],
],
```

### Change Email Template

Edit `app/Notifications/ResetPasswordNotification.php`:

```php
public function toMail($notifiable)
{
    $url = url(route('password.reset', [
        'token' => $this->token,
    ], false));

    return (new MailMessage)
        ->subject('Your Custom Subject')
        ->greeting('Hello ' . $notifiable->first_name . ',')
        ->line('Custom message here')
        ->action('Reset Password', $url)
        ->line('More custom text')
        ->salutation('Your custom signature');
}
```

### Change Reset Link URL

Edit `config/auth.php` or `AppServiceProvider.php`:

```php
use Illuminate\Auth\Notifications\ResetPassword;

ResetPassword::createUrlUsing(function ($user, $token) {
    return 'https://yourapp.com/reset-password?token='.$token;
});
```

---

## Step 5: Security Features

### Built-In Security

✅ **Cryptographically secure tokens** - Not guessable
✅ **Token expiry** - Default 60 minutes
✅ **Single-use tokens** - Deleted after use
✅ **Email verification** - Only user with email access can reset
✅ **Password hashing** - Bcrypt with 12 rounds
✅ **Rate limiting** - Can't spam reset requests (60-second throttle)

### Best Practices

1. **Always use HTTPS in production** - Tokens in URLs must be encrypted
2. **Monitor password reset logs** - Check for suspicious activity
3. **Educate users** - Tell them to never share reset links
4. **Use strong passwords** - Enforce minimum 8 characters (already done)

---

## Step 6: Database Details

### password_reset_tokens Table

```sql
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    PRIMARY KEY (email)
);
```

**Columns:**
- `email` - User's email address
- `token` - Secure reset token
- `created_at` - When token was created (used for expiry check)

**Note:** This table is auto-created by Laravel migrations. No action needed.

---

## Step 7: Files Created/Modified

### Files Created
- `resources/views/auth/forgot_password.blade.php`
- `resources/views/auth/reset_password.blade.php`
- `app/Notifications/ResetPasswordNotification.php`

### Files Modified
- `routes/web.php` - Added 4 password reset routes
- `app/Http/Controllers/AuthController.php` - Added 4 password reset methods
- `app/Models/User.php` - Added `sendPasswordResetNotification()` method
- `resources/views/auth/login.blade.php` - Added "Forgot Password?" link

---

## Step 8: Cleanup (Optional)

### Delete Expired Tokens Automatically

Add to your scheduler in `app/Console/Kernel.php`:

```php
use Illuminate\Support\Facades\Schedule;

protected function schedule(Schedule $schedule)
{
    $schedule->command('auth:clear-resets')->everyFifteenMinutes();
}
```

Or run manually:

```bash
php artisan auth:clear-resets
```

---

## Troubleshooting Checklist

- [ ] Gmail 2FA is enabled
- [ ] Gmail app password is 16 characters
- [ ] `.env` MAIL_MAILER is set to `smtp`
- [ ] `.env` MAIL_HOST is `smtp.gmail.com`
- [ ] `.env` MAIL_PORT is `587`
- [ ] `.env` MAIL_USERNAME is your Gmail address
- [ ] `.env` MAIL_PASSWORD is the app password (not regular password)
- [ ] `.env` MAIL_ENCRYPTION is `tls`
- [ ] `.env` MAIL_FROM_ADDRESS is your Gmail address
- [ ] Routes are registered in `routes/web.php`
- [ ] Controller methods exist in `AuthController.php`
- [ ] Views exist in `resources/views/auth/`
- [ ] Notification class exists in `app/Notifications/`
- [ ] User model has `sendPasswordResetNotification()` method

---

## Support

If you encounter issues:

1. Check `storage/logs/laravel.log` for error messages
2. Verify all `.env` settings are correct
3. Test with `MAIL_MAILER=log` to see email content
4. Ensure database migrations are run: `php artisan migrate`
5. Clear cache: `php artisan cache:clear`

---

## Summary

✅ **Implementation Complete!**

Your Barangay System now has a fully functional password reset feature using:
- Laravel's built-in password reset system
- Gmail SMTP for sending emails
- Custom email template with your branding
- Secure tokens and password hashing
- User-friendly views matching your design

Users can now:
1. Click "Forgot Password?" on login
2. Enter their email
3. Receive a reset link
4. Set a new password
5. Log in with the new password

All emails are sent through Gmail, which is **free** and **reliable** for small-to-medium applications.
