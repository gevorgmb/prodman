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
        if (empty($data['is_default'])) {
            $default = $this->findBy([
                'owner_id' => $ownerId,
                'is_default' => true,
            ]);
            if (empty($default)) {
                $data['is_default'] = true;
            }
        }
        /** @var Apartment $apartment */
        $apartment = $this->apartmentModel->newQuery()->create([
            'owner_id' => $ownerId,
            'name' => $data['name'],
            'description' => $data['description'],
            'is_default' => $data['is_default'] ?? false,
        ]);

        return $apartment;
    }

    public function updateForOwner(Apartment $apartment, array $data): Apartment
    {
        if (! empty($data['is_default']) && ! $apartment->is_default) {
            $this->apartmentModel->newQuery()
                ->where('owner_id', $apartment->owner_id)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
        $apartment->fill($data);
        $apartment->save();

        return $apartment;
    }

    public function deleteForOwner(Apartment $apartment): void
    {
        $isDefault = $apartment->is_default;
        $apartment->delete();
        if ($isDefault) {
            $firstApartment = $this->apartmentModel->newQuery()
                ->where('owner_id', $apartment->owner_id)
                ->first();
            if ($firstApartment) {
                $firstApartment->is_default = true;
                $firstApartment->save();
            }
        }
    }
}
