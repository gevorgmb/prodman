<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Enums\ContactTypeEnum;

interface ContactVerificationRepositoryInterface
{
    public function verifyCode(int $userId, string $code, ContactTypeEnum $contactType): bool;

    public function createVerification(int $userId, ContactTypeEnum $contactType): string;
}
