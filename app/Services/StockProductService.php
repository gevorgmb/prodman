<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\StockProduct\StockProductDto;
use App\Models\StockProduct;
use App\Repositories\Contracts\StockProductRepositoryInterface;
use App\Services\Contracts\StockProductServiceInterface;
use Illuminate\Support\Collection;

class StockProductService implements StockProductServiceInterface
{
    public function __construct(
        private readonly StockProductRepositoryInterface $productRepository,
    ) {
    }

    /**
     * @return Collection<int, StockProductDto>
     */
    public function getAllByApartmentId(int $apartmentId): Collection
    {
        return $this->productRepository->getAllByApartmentId($apartmentId)
            ->map(fn (StockProduct $product) => StockProductDto::fromModel($product));
    }

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?StockProductDto
    {
        $product = $this->productRepository->findByIdAndApartmentId($id, $apartmentId);

        return $product === null ? null : StockProductDto::fromModel($product);
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

        if (isset($data['quantityUsed'])) {
            $data['quantity_used'] = $data['quantityUsed'];
        }

        if (isset($data['expirationDate'])) {
            $data['expiration_date'] = $data['expirationDate'];
        }

        $product = $this->productRepository->create($data);

        return StockProductDto::fromModel($product);
    }

    public function update(int $id, int $apartmentId, array $data): StockProductDto
    {
        $product = $this->productRepository->findByIdAndApartmentId($id, $apartmentId);

        if ($product === null) {
            throw new \RuntimeException('Stock Product not found.');
        }

        if (isset($data['itemId'])) {
            $data['item_id'] = $data['itemId'];
        }

        if (isset($data['productName'])) {
            $data['product_name'] = $data['productName'];
        }

        if (isset($data['quantityUsed'])) {
            $data['quantity_used'] = $data['quantityUsed'];
        }

        if (isset($data['expirationDate'])) {
            $data['expiration_date'] = $data['expirationDate'];
        }

        $updatedProduct = $this->productRepository->update($product, $data);

        return StockProductDto::fromModel($updatedProduct);
    }

    public function delete(int $id, int $apartmentId): void
    {
        $product = $this->productRepository->findByIdAndApartmentId($id, $apartmentId);

        if ($product !== null) {
            $this->productRepository->delete($product);
        }
    }
}
