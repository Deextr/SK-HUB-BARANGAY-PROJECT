<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sex = fake()->randomElement(['Male', 'Female']);
        $createdAt = Carbon::now()->subDays(fake()->numberBetween(1, 60));
        
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'birth_date' => fake()->date('Y-m-d', '-8 years'),
            'sex' => $sex,
            'is_pwd' => fake()->boolean(10), // 10% chance of being PWD
            'email' => fake()->unique()->userName() . '@gmail.com',
            'password' => static::$password ??= Hash::make('123123123'),
            'is_admin' => 0,
            'account_status' => 'pending',

            //arhive
            'is_archived' => 0,
            'archived_at' => null,
            'archive_reason' => null,
            
            
            'id_image_path' => $sex === 'Female' ? 'images/id/image1.jpg' : 'images/id/image2.jpg',
        
            'rejection_reason' => null,
            'rejected_at' => null,

            'partially_rejected_reason' => null,
            'partially_rejected_at' => null,
            
            'approved_at' => null,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }

    /**
     * Indicate that the user account is approved.
     */
    public function approved(): static
    {
        return $this->state(function (array $attributes) {
            $createdAt = $attributes['created_at'] ?? Carbon::now();
            $approvedAt = Carbon::parse($createdAt)->addHours(fake()->numberBetween(2, 24));
            
            return [
                'account_status' => 'approved',
                'approved_at' => $approvedAt,
                'updated_at' => $createdAt,
            ];
        });
    }

    /**
     * Indicate that the user account is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_status' => 'pending',
            'approved_at' => null,
            'rejection_reason' => null,
        ]);
    }

    /**
     * Indicate that the user account is rejected.
     */
    public function rejected(): static
    {
        return $this->state(function (array $attributes) {
            $createdAt = $attributes['created_at'] ?? Carbon::now();
            $rejectedAt = Carbon::parse($createdAt)->addHours(fake()->numberBetween(12, 48));
            
            return [
                'account_status' => 'rejected',
                'approved_at' => null,
                'rejection_reason' => fake()->randomElement([
                    'You are not a resident of Barangay 22-C',
                    'Your provided information does not match our records',
                    'Your details are incomplete or missing required fields',
                    'You did not meet the SK Hub eligibility criteria for students',
                    'You submitted incorrect or falsified information',
                    'You are outside the eligible age bracket for SK Hub users',
                    'You attempted to register multiple accounts',
                ]),
                'rejected_at' => $rejectedAt,
                'updated_at' => $rejectedAt,
            ];
        });
    }

    /**
     * Indicate that the user account is partially rejected.
     */
    public function partiallyRejected(): static
    {
        return $this->state(function (array $attributes) {
            $createdAt = $attributes['created_at'] ?? Carbon::now();
            $partiallyRejectedAt = Carbon::parse($createdAt)->addHours(fake()->numberBetween(12, 48));
            
            return [
                'account_status' => 'partially_rejected',
                'partially_rejected_reason' => fake()->randomElement([
                    'Incomplete documents',
                    'Invalid ID provided',
                    'Information mismatch',
                    'Use of expired ID',
                    'Unclear photo',
                ]),
                'partially_rejected_at' => $partiallyRejectedAt,
                'approved_at' => null,
                'updated_at' => $partiallyRejectedAt,
            ];
        });
    }
    
    /**
     * Indicate that the user account is archived.
     */
    public function archived(): static
    {
        return $this->state(function (array $attributes) {
            $createdAt = $attributes['created_at'] ?? Carbon::now();
            $archivedAt = Carbon::parse($createdAt)->addDays(fake()->numberBetween(30, 90));
            
            return [
                'is_archived' => 1,
                'archived_at' => $archivedAt,
                'archive_reason' => fake()->randomElement([
                    'Account inactive for extended period',
                    'User requested account deletion',
                    'Violated community guidelines',
                    'Duplicate account',
                    'Fraudulent activity detected',
                ]),
                'updated_at' => $archivedAt,
            ];
        });
    }

    /**
     * Indicate that the user is a PWD.
     */
    public function pwd(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_pwd' => 1,
        ]);
    }

    /**
     * Set a specific sex for the user.
     */
    public function male(): static
    {
        return $this->state(fn (array $attributes) => [
            'sex' => 'Male',
            'id_image_path' => 'images/id/image2.jpg',
        ]);
    }

    /**
     * Set female sex for the user.
     */
    public function female(): static
    {
        return $this->state(fn (array $attributes) => [
            'sex' => 'Female',
            'id_image_path' => 'images/id/image1.jpg',
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
