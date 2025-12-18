<x-mail::message>
# Hello {{ $user->first_name }},

@if ($status === 'approved')
## Your Account has been Approved!

Congratulations! Your account has been successfully approved. You can now log in and access all the features of our platform.

<x-mail::button :url="route('login')">
Login to Your Account
</x-mail::button>

@elseif ($status === 'partially_rejected')
## Action Required: Your Account Needs Corrections

Your registration has been reviewed, but we need some corrections before we can approve it.

**Reason:**
{{ $reason }}

Please log in to your account to review the feedback and resubmit your information.

<x-mail::button :url="route('login')">
Resubmit Information
</x-mail::button>

@elseif ($status === 'rejected')
## Update on Your Registration

We regret to inform you that your registration has been rejected.

**Reason:**
{{ $reason }}

If you believe this is a mistake, please contact our support team.
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
