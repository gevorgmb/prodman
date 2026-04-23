<?php

declare(strict_types=1);

namespace App\Dto\Apartment;

use App\Models\User;

readonly class ApartmentUserLookupDto
{
    public function __construct(
        public User $user,
        public bool $alreadyInApartment,
    ) {
    }
}
