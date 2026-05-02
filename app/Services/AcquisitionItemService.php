<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\AcquisitionItem\AcquisitionItemDto;
use App\Models\AcquisitionItem;
use App\Repositories\Contracts\AcquisitionItemRepositoryInterface;
use App\Services\Contracts\AcquisitionItemServiceInterface;
use Illuminate\Support\Collection;

readonly class AcquisitionItemService implements AcquisitionItemServiceInterface
{
    public function __construct(
        private AcquisitionItemRepositoryInterface $itemRepository,
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
        $data = $this->buildCreateData($data, $acquisitionId);

        $item = $this->itemRepository->create($data);

        return AcquisitionItemDto::fromModel($item);
    }

    public function bulkCreate(array $data, int $acquisitionId): void
    {
        foreach ($data as $key => $itemData) {
            $data[$key] = $this->buildCreateData($itemData, $acquisitionId);
        }
        $this->itemRepository->insert($data);
    }

    public function bulkUpdate(array $data, int $acquisitionId): void
    {
        $collectedData = [];
        foreach ($data as $key => $itemData) {
            if (($itemData['action'] ?? 'create') === 'create') {
                $collectedData['create'][] = $this->buildCreateData($itemData, $acquisitionId);
            } elseif ($itemData['action'] === 'update') {
                $updateData = $this->buildUpdateData($itemData, $acquisitionId);
                $this->update((int)$itemData['itemId'], $acquisitionId, $updateData);
            } else {
                $collectedData['delete'][$itemData['itemId']] = $itemData['itemId'];
            }
        }
        if (! empty($collectedData['create'])) {
            $this->itemRepository->insert($collectedData['create']);
        }
        if (! empty($collectedData['delete'])) {
            $this->itemRepository->bulkDelete($collectedData['delete']);
        }
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

    private function buildCreateData(array $data, int $acquisitionId): array
    {
        return [
            'acquisition_id' => $acquisitionId,
            'product_id' => $data['productId'] ?? null,
            'quantity' => $data['quantity'] ?? 0,
            'price' => $data['price'] ?? 0,
            'total' => $data['quantity'] * $data['price'],
            'description' => $data['description'] ?? null,
            'product_name' => $data['productName'],
            'expiration_date' => $data['expirationDate'] ?? null,
        ];
    }

    private function buildUpdateData(array $data, int $acquisitionId): array
    {
        $result = [];
        if (isset($data['productId'])) {
            $result['product_id'] = $data['productId'];
        }
        if (isset($data['productName'])) {
            $result['product_name'] = $data['productName'];
        }
        if (isset($data['expirationDate'])) {
            $result['expiration_date'] = $data['expirationDate'];
        }
        if (isset($data['quantity'])) {
            $result['quantity'] = $data['quantity'];
        }
        if (isset($data['price'])) {
            $result['price'] = $data['price'];
        }
        if (isset($data['description'])) {
            $result['description'] = $data['description'];
        }
        return $result;
    }
}
