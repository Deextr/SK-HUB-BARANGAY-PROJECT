<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

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
        'is_admin',
        'account_status',
        'id_image_path',
        'rejection_reason',
        'approved_at',
        'rejected_at',
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
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
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
}
