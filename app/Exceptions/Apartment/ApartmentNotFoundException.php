<?php

declare(strict_types=1);

namespace App\Exceptions\Apartment;

use RuntimeException;

class ApartmentNotFoundException extends RuntimeException
{
    public function __construct(string $message = 'Apartment not found.')
    {
        parent::__construct($message);
    }
}
