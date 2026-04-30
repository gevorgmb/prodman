<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\AcquisitionItem;
use App\Repositories\Contracts\AcquisitionItemRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AcquisitionItemRepository extends BaseRepository implements AcquisitionItemRepositoryInterface
{
    public function __construct(
        private readonly AcquisitionItem $itemModel,
    ) {
        parent::__construct($itemModel);
    }

    public function getAllByAcquisitionId(int $acquisitionId): Collection
    {
        return $this->itemModel->newQuery()
            ->where('acquisition_id', $acquisitionId)
            ->get();
    }

    public function findByIdAndAcquisitionId(int $id, int $acquisitionId): ?AcquisitionItem
    {
        /** @var AcquisitionItem|null $item */
        $item = $this->itemModel->newQuery()
            ->where('id', $id)
            ->where('acquisition_id', $acquisitionId)
            ->first();

        return $item;
    }

    public function create(array $data): AcquisitionItem
    {
        /** @var AcquisitionItem $item */
        $item = $this->itemModel->newQuery()->create($data);

        return $item;
    }

    public function update(AcquisitionItem $item, array $data): AcquisitionItem
    {
        $item->fill($data);
        $item->save();

        return $item;
    }

    public function delete(AcquisitionItem $item): void
    {
        $item->delete();
    }
}
