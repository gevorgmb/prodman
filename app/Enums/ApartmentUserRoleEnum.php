<?php

declare(strict_types=1);

namespace App\Enums;

enum ApartmentUserRoleEnum: string
{
    case ADMIN = 'admin';
    case MEMBER = 'member';

    public static function roleByValue(?string $value): self
    {
        return match ($value) {
            'admin' => self::ADMIN,
            'member' => self::MEMBER,
            default => null,
        };
    }
}
