<?php

namespace App\Enums;

enum AcquisitionStatusEnum: string
{
    case DRAFT = 'draft';
    case COMPLETE = 'complete';
    case CANCELLED = 'cancelled';

    public static function fromString(?string $status): self
    {
        return match ($status) {
            self::COMPLETE->value => self::COMPLETE,
            self::CANCELLED->value => self::CANCELLED,
            default => self::DRAFT,
        };
    }
}
