<?php

declare(strict_types=1);

namespace App\Exceptions\Apartment;

use RuntimeException;

class ApartmentMembershipNotFoundException extends RuntimeException
{
    public function __construct(string $message = 'User is not in apartment.')
    {
        parent::__construct($message);
    }
}
