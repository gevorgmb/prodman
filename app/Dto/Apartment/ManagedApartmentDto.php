<?php

declare(strict_types=1);

namespace App\Dto\Apartment;

use App\Models\Apartment;

readonly class ManagedApartmentDto
{
    public function __construct(
        public Apartment $apartment,
        public bool $isOwner,
        public bool $isAdmin,
    ) {
    }
}
