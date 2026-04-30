<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\AcquisitionItem\AcquisitionItemDto;
use App\Models\AcquisitionItem;
use App\Repositories\Contracts\AcquisitionItemRepositoryInterface;
use App\Services\Contracts\AcquisitionItemServiceInterface;
use Illuminate\Support\Collection;

class AcquisitionItemService implements AcquisitionItemServiceInterface
{
    public function __construct(
        private readonly AcquisitionItemRepositoryInterface $itemRepository,
    ) {
    }

    /**
     * @return Collection<int, AcquisitionItemDto>
     */
    public function getAllByAcquisitionId(int $acquisitionId): Collection
    {
        return $this->itemRepository->getAllByAcquisitionId($acquisitionId)
            ->map(fn (AcquisitionItem $item) => AcquisitionItemDto::fromModel($item));
    }

    public function findByIdAndAcquisitionId(int $id, int $acquisitionId): ?AcquisitionItemDto
    {
        $item = $this->itemRepository->findByIdAndAcquisitionId($id, $acquisitionId);

        return $item === null ? null : AcquisitionItemDto::fromModel($item);
    }

    public function create(array $data, int $acquisitionId): AcquisitionItemDto
    {
        $data['acquisition_id'] = $acquisitionId;

        if (isset($data['productId'])) {
            $data['product_id'] = $data['productId'];
        }

        if (isset($data['quantity']) && isset($data['price'])) {
            $data['total'] = (float)$data['quantity'] * (float)$data['price'];
        } else {
            $data['total'] = 0;
        }
        $data['product_name'] = $data['productName'];
        $data['expiration_date'] = $data['expirationDate'] ?? null;

        $item = $this->itemRepository->create($data);

        return AcquisitionItemDto::fromModel($item);
    }

    public function update(int $id, int $acquisitionId, array $data): AcquisitionItemDto
    {
        $item = $this->itemRepository->findByIdAndAcquisitionId($id, $acquisitionId);

        if ($item === null) {
            throw new \RuntimeException('Acquisition Item not found.');
        }

        if (isset($data['productId'])) {
            $data['product_id'] = $data['productId'];
        }

        if (isset($data['productName'])) {
            $data['product_name'] = $data['productName'];
        }

        if (array_key_exists('expirationDate', $data)) {
            $data['expiration_date'] = $data['expirationDate'];
        }

        $quantity = isset($data['quantity']) ? (float)$data['quantity'] : $item->quantity;
        $price = isset($data['price']) ? (float)$data['price'] : $item->price;
        $data['total'] = $quantity * $price;

        $updatedItem = $this->itemRepository->update($item, $data);

        return AcquisitionItemDto::fromModel($updatedItem);
    }

    public function delete(int $id, int $acquisitionId): void
    {
        $item = $this->itemRepository->findByIdAndAcquisitionId($id, $acquisitionId);

        if ($item !== null) {
            $this->itemRepository->delete($item);
        }
    }
}
