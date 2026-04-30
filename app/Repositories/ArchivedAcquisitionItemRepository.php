<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ArchivedAcquisitionItem;
use App\Repositories\Contracts\ArchivedAcquisitionItemRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ArchivedAcquisitionItemRepository extends BaseRepository implements ArchivedAcquisitionItemRepositoryInterface
{
    public function __construct(
        private readonly ArchivedAcquisitionItem $itemModel,
    ) {
        parent::__construct($itemModel);
    }

    public function getAllByApartmentId(int $apartmentId): Collection
    {
        return $this->itemModel->newQuery()
            ->where('apartment_id', $apartmentId)
            ->get();
    }

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?ArchivedAcquisitionItem
    {
        /** @var ArchivedAcquisitionItem|null $item */
        $item = $this->itemModel->newQuery()
            ->where('id', $id)
            ->where('apartment_id', $apartmentId)
            ->first();

        return $item;
    }

    public function create(array $data): ArchivedAcquisitionItem
    {
        /** @var ArchivedAcquisitionItem $item */
        $item = $this->itemModel->newQuery()->create($data);

        return $item;
    }

    public function update(ArchivedAcquisitionItem $item, array $data): ArchivedAcquisitionItem
    {
        $item->fill($data);
        $item->save();

        return $item;
    }

    public function delete(ArchivedAcquisitionItem $item): void
    {
        $item->delete();
    }
}
