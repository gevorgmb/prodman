<?php

declare(strict_types=1);

namespace App\Exceptions\Apartment;

use RuntimeException;

class ApartmentAccessDeniedException extends RuntimeException
{
    public function __construct(string $message = 'Apartment not found or access denied.')
    {
        parent::__construct($message);
    }
}
