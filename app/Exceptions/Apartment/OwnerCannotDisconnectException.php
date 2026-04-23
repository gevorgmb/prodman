<?php

declare(strict_types=1);

namespace App\Exceptions\Apartment;

use RuntimeException;

class OwnerCannotDisconnectException extends RuntimeException
{
    public function __construct(string $message = 'Owner cannot disconnect from own apartment.')
    {
        parent::__construct($message);
    }
}
