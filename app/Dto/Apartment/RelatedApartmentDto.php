<?php

declare(strict_types=1);

namespace App\Dto\Apartment;

readonly class RelatedApartmentDto
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $description,
        public bool $isDefault,
        public int $ownerId,
        public bool $isOwner,
        public string $role,
    ) {
    }
}
