<?php

declare(strict_types=1);

namespace App\Exceptions\Apartment;

use RuntimeException;

class UserAlreadyInApartmentException extends RuntimeException
{
    public function __construct(string $message = 'User is already added to apartment.')
    {
        parent::__construct($message);
    }
}
