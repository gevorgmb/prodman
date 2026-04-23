<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dto\Apartment\ManagedApartmentDto;
use App\Dto\Apartment\RelatedApartmentDto;
use App\Models\Apartment;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ApartmentServiceInterface
{
    public function getAll(int $ownerId): Collection;

    public function createForOwner(int $ownerId, array $data): Apartment;

    public function findForOwner(int $id, int $ownerId): ?Apartment;

    public function updateForOwner(Apartment $apartment, array $data): Apartment;

    public function deleteForOwner(Apartment $apartment): void;

    public function getManagedApartment(Request $request): ManagedApartmentDto;

    /**
     * @return Collection<int, RelatedApartmentDto>
     */
    public function getRelatedApartments(int $userId): Collection;
}
