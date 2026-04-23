<?php

declare(strict_types=1);

namespace App\Exceptions\Apartment;

use RuntimeException;

class UnauthenticatedException extends RuntimeException
{
    public function __construct(string $message = 'Unauthenticated.')
    {
        parent::__construct($message);
    }
}
