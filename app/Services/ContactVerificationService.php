<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ContactTypeEnum;
use App\Models\User;
use App\Repositories\Contracts\ContactVerificationRepositoryInterface;

readonly class ContactVerificationService
{
    public function __construct(
        private ContactVerificationRepositoryInterface $contactVerificationRepository,
        private EmailService                           $emailService,
    )
    {
    }

    public function sendEmailVerificationCode(User $user): void
    {
        $code = $this->contactVerificationRepository->createVerification($user->id, ContactTypeEnum::EMAIL);

        $this->emailService->sendVerificationEmail($user, $code);
    }

    public function verifyEmailCode(User $user, string $code): bool
    {
        $verified = $this->contactVerificationRepository->verifyCode($user->id, $code, ContactTypeEnum::EMAIL);

        if ($verified) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }

        return $verified;
    }
}
