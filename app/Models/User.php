<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\ContactTypeEnum;
use App\Enums\ContactVerificationStatusEnum;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string|null $phone
 * @property Carbon|null $phone_verified_at
 */
#[Fillable(['name', 'email', 'password', 'phone'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

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
        ];
    }

    public function emailVerification(): HasOne
    {
        return $this->hasOne(ContactVerification::class)
            ->where([
                'contact_type' => ContactTypeEnum::EMAIL->value,
                'status' => ContactVerificationStatusEnum::PENDING->value,
            ]);
    }

    public function phoneVerification(): HasOne
    {
        return $this->hasOne(ContactVerification::class)
            ->where([
                'contact_type' => ContactTypeEnum::PHONE->value,
                'status' => ContactVerificationStatusEnum::PENDING->value,
            ]);
    }

    public function emailVerificationLocked(): HasOne
    {
        return $this->hasOne(ContactVerification::class)
            ->where([
                'contact_type' => ContactTypeEnum::EMAIL->value,
                'status' => ContactVerificationStatusEnum::LOCKED->value,
            ])
            ->where(
                'updated_at',
                '>',
                now()->subHours((int) config('settings.verification_lock_hours'))
            );
    }

    public function phoneVerificationLocked(): HasOne
    {
        return $this->hasOne(ContactVerification::class)
            ->where([
                'contact_type' => ContactTypeEnum::PHONE->value,
                'status' => ContactVerificationStatusEnum::LOCKED->value,
            ])
            ->where(
                'updated_at',
                '>',
                now()->subHours((int) config('settings.verification_lock_hours'))
            );
    }
}
