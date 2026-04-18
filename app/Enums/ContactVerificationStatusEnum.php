<?php

namespace App\Enums;

enum ContactVerificationStatusEnum: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case CANCELLED = 'cancelled';
    case LOCKED = 'locked';
}
