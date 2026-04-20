<?php

declare(strict_types=1);

namespace App\Enums;

enum ContactTypeEnum: string
{
    case EMAIL = 'email';
    case PHONE = 'phone';
}
