<?php

declare(strict_types=1);

namespace App\Exceptions\Apartment;

use RuntimeException;

class ApartmentOwnerCannotBeRemovedException extends RuntimeException
{
    public function __construct(string $message = 'Apartment owner cannot be removed.')
    {
        parent::__construct($message);
    }
}
