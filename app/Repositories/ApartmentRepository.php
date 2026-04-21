<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Apartment;
use App\Repositories\Contracts\ApartmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ApartmentRepository extends BaseRepository implements ApartmentRepositoryInterface
{
    public function __construct(
        private readonly Apartment $apartmentModel,
    ) {
        parent::__construct($apartmentModel);
    }

    public function getAllByOwnerId(int $ownerId): Collection
    {
        return $this->apartmentModel->newQuery()
            ->where('owner_id', $ownerId)
            ->orderByDesc('id')
            ->get();
    }

    public function findByIdAndOwnerId(int $id, int $ownerId): ?Apartment
    {
        /** @var Apartment|null $apartment */
        $apartment = $this->apartmentModel->newQuery()
            ->where('id', $id)
            ->where('owner_id', $ownerId)
            ->first();

        return $apartment;
    }

    public function createForOwner(int $ownerId, array $data): Apartment
    {
        /** @var Apartment $apartment */
        $apartment = $this->apartmentModel->newQuery()->create([
            'owner_id' => $ownerId,
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        return $apartment;
    }

    public function updateForOwner(Apartment $apartment, array $data): Apartment
    {
        $apartment->fill($data);
        $apartment->save();

        return $apartment;
    }

    public function deleteForOwner(Apartment $apartment): void
    {
        $apartment->delete();
    }
}
