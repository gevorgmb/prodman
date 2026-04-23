<?php

declare(strict_types=1);

namespace App\Exceptions\Apartment;

use RuntimeException;

class ApartmentHeaderMissingException extends RuntimeException
{
    public function __construct(string $message = 'Invalid or missing apartment header.')
    {
        parent::__construct($message);
    }
}
