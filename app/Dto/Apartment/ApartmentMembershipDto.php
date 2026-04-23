<?php

declare(strict_types=1);

namespace App\Dto\Apartment;

readonly class ApartmentMembershipDto
{
    public function __construct(
        public int $id,
        public int $apartmentId,
        public int $userId,
        public string $role,
    ) {
    }
}
