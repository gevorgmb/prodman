<?php

declare(strict_types=1);

namespace app\Repositories\Contracts;

use app\Enums\ContactTypeEnum;

interface ContactVerificationRepositoryInterface
{
    public function verifyCode(int $userId, string $code, ContactTypeEnum $contactType): bool;
}
