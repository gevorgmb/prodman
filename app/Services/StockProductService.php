<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\StockProduct\StockProductDto;
use App\Events\ArchiveStockItemEvent;
use App\Listeners\ArchiveStockItemListener;
use App\Models\StockProduct;
use App\Repositories\Contracts\StockProductRepositoryInterface;
use App\Services\Contracts\StockProductServiceInterface;
use Illuminate\Support\Collection;

readonly class StockProductService implements StockProductServiceInterface
{
    public function __construct(
        private StockProductRepositoryInterface $stockProductRepository,
        private ArchivedAcquisitionItemService $archivedAcquisitionItemService,
    ) {
    }

    /**
     * @return Collection<int, StockProductDto>
     */
    public function getAllByApartmentId(int $apartmentId): Collection
    {
        /** @var StockProduct $product */
        return $this->stockProductRepository->getAllByApartmentId($apartmentId)
            ->map(fn(StockProduct $product) => StockProductDto::fromModel($product));
    }

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?StockProductDto
    {
        $product = $this->stockProductRepository->findByIdAndApartmentId($id, $apartmentId);

        return $product === null ? null : StockProductDto::fromModel($product);
    }

    public function findById(int $id): ?StockProductDto
    {
        /** @var StockProduct $stockProduct */
        $stockProduct = $this->stockProductRepository->find($id);

        return $stockProduct === null ? null : StockProductDto::fromModel($stockProduct);
    }

    public function mergeByProductId(int $productId): ?StockProductDto
    {
        $stockItems = $this->stockProductRepository->findByProductId($productId);
        if ($stockItems->isEmpty()) {
            return null;
        }
        $count = $stockItems->count();
        if ($count === 1) {
            /** @var StockProduct $stockProduct */
            $stockProduct = $stockItems->first();
            return StockProductDto::fromModel($stockProduct);
        }
        $i = 0;
        $totalAvailable = 0;
        $archivableStockItems = [];
        $deleteStockItems = [];
        $stockProductDto = null;
        foreach ($stockItems as $stockProduct) {
            ++$i;
            if ($i === $count) {
                $stockProduct->increment('quantity_available', $totalAvailable);
                $stockProductDto = StockProductDto::fromModel($stockProduct);
            } else {
                $totalAvailable += $stockProduct->quantity_available;
                $archivableStockItems[] = $this->buildCreateArchiveData($stockProduct);
                $deleteStockItems[] = $stockProduct->id;
            }
        }
        $this->stockProductRepository->bulkDelete($deleteStockItems);
        $this->archivedAcquisitionItemService->bulkInsert($archivableStockItems);
        return $stockProductDto;
    }

    public function create(array $data, int $apartmentId): StockProductDto
    {
        $data['apartment_id'] = $apartmentId;

        if (isset($data['itemId'])) {
            $data['item_id'] = $data['itemId'];
        }

        if (isset($data['productName'])) {
            $data['product_name'] = $data['productName'];
        }

        if (isset($data['quantityAvailable'])) {
            $data['quantity_available'] = $data['quantityAvailable'];
        }

        if (isset($data['expirationDate'])) {
            $data['expiration_date'] = $data['expirationDate'];
        }

        $product = $this->stockProductRepository->create($data);

        return StockProductDto::fromModel($product);
    }

    public function update(int $id, int $apartmentId, array $data): StockProductDto
    {
        $product = $this->stockProductRepository->findByIdAndApartmentId($id, $apartmentId);

        if ($product === null) {
            throw new \RuntimeException('Stock Product not found.');
        }

        $archive = $data['archive'] ?? false;
        $reason = $data['reason'] ?? null;
        unset($data['archive']);
        unset($data['reason']);
        $updatedProduct = $this->stockProductRepository->update($product, $data);
        if ($data['quantityAvailable'] == 0 || $archive) {
            event(new ArchiveStockItemEvent($id, $reason));
            \Log::info('Archiving stock item, event dispatched.');
        }

        return StockProductDto::fromModel($updatedProduct);
    }

    public function delete(int $id, int $apartmentId): void
    {
        $product = $this->stockProductRepository->findByIdAndApartmentId($id, $apartmentId);

        if ($product !== null) {
            $this->stockProductRepository->delete($product);
        }
    }

    private function buildCreateArchiveData(StockProduct $stockProduct): array
    {
        return [
            'apartment_id' => $stockProduct->apartment_id,
            'item_id' => $stockProduct->item_id,
            'product_name' => $stockProduct->product_name,
            'quantity_available' => $stockProduct->quantity_available,
            'expiration_date' => $stockProduct->expiration_date,
            'archive_date' => now(),
            'quantity' => $stockProduct->acquisitionItem->quantity,
        ];
    }
}
