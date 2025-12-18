<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'birth_date',
        'sex',
        'is_pwd',
        'is_admin',
        'account_status',
        'suspension_count',
        'is_suspended',
        'suspension_end_date',
        'is_archived',
        'archive_reason',
        'archived_at',
        'id_image_path',
        'rejection_reason',
        'approved_at',
        'rejected_at',
        'partially_rejected_at',
        'partially_rejected_reason',
        'resubmission_count',
         'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'is_pwd' => 'boolean',
            'is_suspended' => 'boolean',
            'is_archived' => 'boolean',
            'suspension_end_date' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'archived_at' => 'datetime',
            'partially_rejected_at' => 'datetime',
        ];
    }

    /**
     * Check if user account is pending approval
     */
    public function isPending(): bool
    {
        return $this->account_status === 'pending';
    }

    /**
     * Check if user account is approved
     */
    public function isApproved(): bool
    {
        return $this->account_status === 'approved';
    }

    /**
     * Check if user account is rejected
     */
    public function isRejected(): bool
    {
        return $this->account_status === 'rejected';
    }

    /**
     * Check if user account is partially rejected
     */
    public function isPartiallyRejected(): bool
    {
        return $this->account_status === 'partially_rejected';
    }

    /**
     * Check if user can resubmit (partially rejected and not exceeding limit)
     */
    public function canResubmit(): bool
    {
        return $this->isPartiallyRejected() && ($this->resubmission_count ?? 0) < 3;
    }

    /**
     * Get full name (alias for full_name)
     */
    public function getNameAttribute(): string
    {
        $firstName = $this->attributes['first_name'] ?? '';
        $lastName = $this->attributes['last_name'] ?? '';
        return trim(Str::title($firstName) . ' ' . Str::title($lastName));
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute(): string
    {
        $firstName = $this->attributes['first_name'] ?? '';
        $lastName = $this->attributes['last_name'] ?? '';
        return trim(Str::title($firstName) . ' ' . Str::title($lastName));
    }

    /**
     * Automatically format first_name to Proper Case when retrieved
     */
    public function getFirstNameAttribute($value): string
    {
        return $value ? Str::title(trim($value)) : '';
    }

    /**
     * Automatically format last_name to Proper Case when retrieved
     */
    public function getLastNameAttribute($value): string
    {
        return $value ? Str::title(trim($value)) : '';
    }

    /**
     * Automatically format first_name to Proper Case when saved
     */
    public function setFirstNameAttribute($value): void
    {
        $this->attributes['first_name'] = $value ? Str::title(trim($value)) : null;
    }

    /**
     * Automatically format last_name to Proper Case when saved
     */
    public function setLastNameAttribute($value): void
    {
        $this->attributes['last_name'] = $value ? Str::title(trim($value)) : null;
    }

    /**
     * Calculate age from birth_date (computed dynamically - 3NF compliant)
     * Age is not stored in database to avoid redundancy
     */
    public function getAgeAttribute(): ?int
    {
        if (!$this->birth_date) {
            return null;
        }

        // Calculate age in years from birth_date to today
        return $this->birth_date->diffInYears(now());
    }

    /**
     * Get formatted age string for display
     */
    public function getFormattedAgeAttribute(): ?string
    {
        $age = $this->age;
        
        if ($age === null) {
            return null;
        }

        return $age . ' years old';
    }

    /**
     * Check if user is currently suspended
     */
    public function isSuspended(): bool
    {
        // If not marked as suspended, return false
        if (!$this->is_suspended) {
            return false;
        }

        // If suspension has expired, update status and return false
        if ($this->suspension_end_date && $this->suspension_end_date->isPast()) {
            $this->update([
                'is_suspended' => false
            ]);
            return false;
        }

        // User is suspended and suspension period is still active
        return true;
    }

    /**
     * Get remaining suspension days
     */
    public function getSuspensionDaysRemainingAttribute(): ?int
    {
        if (!$this->is_suspended || !$this->suspension_end_date) {
            return null;
        }

        if ($this->suspension_end_date->isPast()) {
            return 0;
        }

        return now()->diffInDays($this->suspension_end_date, false);
    }

    /**
     * Apply suspension to user
     */
    public function applySuspension(int $days = 7): void
    {
        $this->increment('suspension_count');
        $this->update([
            'is_suspended' => true,
            'suspension_end_date' => now()->addDays($days)
        ]);
    }

    /**
     * Remove suspension from user
     */
    public function removeSuspension(): void
    {
        $this->update([
            'is_suspended' => false,
            'suspension_end_date' => null
        ]);
    }
        
    /**
     * Check if user is archived
     */
    public function isArchived(): bool
    {
        return $this->is_archived === true;
    }
    
    /**
     * Archive a user account
     */
    public function archive(string $reason): void
    {
        $this->update([
            'is_archived' => true,
            'archive_reason' => $reason,
            'archived_at' => now()
        ]);
    }
    
    /**
     * Unarchive a user account
     */
    public function unarchive(): void
    {
        $this->update([
            'is_archived' => false,
            'archive_reason' => null,
            'archived_at' => null
        ]);
    }

    /**
     * Get password history for this user
     */
    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
    }

    /**
     * Check if password has been used before
     */
    public function hasUsedPassword(string $plainPassword): bool
    {
        // Get all previous password hashes for this user
        $previousPasswords = $this->passwordHistories()
            ->orderBy('changed_at', 'desc')
            ->pluck('password_hash')
            ->toArray();

        // Check if the new password matches any previous password
        foreach ($previousPasswords as $hash) {
            if (\Illuminate\Support\Facades\Hash::check($plainPassword, $hash)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Record current password in history before updating
     */
    public function recordPasswordHistory(): void
    {
        // Only record if user already has a password (not on initial creation)
        if ($this->password) {
            PasswordHistory::create([
                'user_id' => $this->id,
                'password_hash' => $this->password,
                'changed_at' => now(),
            ]);
        }
    }

    /**
     * Send a password reset notification to the user.
     * This customizes the default Laravel password reset email.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
