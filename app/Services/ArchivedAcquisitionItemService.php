<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\ArchivedAcquisitionItem\ArchivedAcquisitionItemDto;
use App\Models\ArchivedAcquisitionItem;
use App\Repositories\Contracts\ArchivedAcquisitionItemRepositoryInterface;
use App\Services\Contracts\ArchivedAcquisitionItemServiceInterface;
use Illuminate\Support\Collection;

class ArchivedAcquisitionItemService implements ArchivedAcquisitionItemServiceInterface
{
    public function __construct(
        private readonly ArchivedAcquisitionItemRepositoryInterface $itemRepository,
    ) {
    }

    /**
     * @return Collection<int, ArchivedAcquisitionItemDto>
     */
    public function getAllByApartmentId(int $apartmentId): Collection
    {
        return $this->itemRepository->getAllByApartmentId($apartmentId)
            ->map(fn (ArchivedAcquisitionItem $item) => ArchivedAcquisitionItemDto::fromModel($item));
    }

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?ArchivedAcquisitionItemDto
    {
        $item = $this->itemRepository->findByIdAndApartmentId($id, $apartmentId);

        return $item === null ? null : ArchivedAcquisitionItemDto::fromModel($item);
    }

    public function create(array $data, int $apartmentId): ArchivedAcquisitionItemDto
    {
        $data['apartment_id'] = $apartmentId;

        if (isset($data['itemId'])) {
            $data['item_id'] = $data['itemId'];
        }

        $item = $this->itemRepository->create($data);

        return ArchivedAcquisitionItemDto::fromModel($item);
    }

    public function update(int $id, int $apartmentId, array $data): ArchivedAcquisitionItemDto
    {
        $item = $this->itemRepository->findByIdAndApartmentId($id, $apartmentId);

        if ($item === null) {
            throw new \RuntimeException('Archived Acquisition Item not found.');
        }

        if (isset($data['itemId'])) {
            $data['item_id'] = $data['itemId'];
        }

        $updatedItem = $this->itemRepository->update($item, $data);

        return ArchivedAcquisitionItemDto::fromModel($updatedItem);
    }

    public function delete(int $id, int $apartmentId): void
    {
        $item = $this->itemRepository->findByIdAndApartmentId($id, $apartmentId);

        if ($item !== null) {
            $this->itemRepository->delete($item);
        }
    }
}
