<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Apartment;
use Illuminate\Database\Eloquent\Collection;

interface ApartmentRepositoryInterface extends EloquentRepositoryInterface
{
    public function getAllByOwnerId(int $ownerId): Collection;

    public function findByIdAndOwnerId(int $id, int $ownerId): ?Apartment;

    public function createForOwner(int $ownerId, array $data): Apartment;

    public function updateForOwner(Apartment $apartment, array $data): Apartment;

    public function deleteForOwner(Apartment $apartment): void;
}
