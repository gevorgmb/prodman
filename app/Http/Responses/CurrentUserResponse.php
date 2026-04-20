<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Models\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

readonly class CurrentUserResponse implements Responsable
{
    public function __construct(
        private User $user,
    ) {
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'emailVerifiedAt' => $this->user->email_verified_at,
            'phoneVerifiedAt' => $this->user->phone_verified_at,
            'emailVerification' => $this->user->emailVerification !== null,
            'emailVerificationLocked' => $this->user->emailVerificationLocked !== null,
            'phoneVerification' => $this->user->phoneVerification !== null,
            'phoneVerificationLocked' => $this->user->phoneVerificationLocked !== null,
        ]);
    }
}
