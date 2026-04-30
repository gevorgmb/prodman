<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\StockProduct;
use App\Repositories\Contracts\StockProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class StockProductRepository extends BaseRepository implements StockProductRepositoryInterface
{
    public function __construct(
        private readonly StockProduct $productModel,
    ) {
        parent::__construct($productModel);
    }

    public function getAllByApartmentId(int $apartmentId): Collection
    {
        return $this->productModel->newQuery()
            ->where('apartment_id', $apartmentId)
            ->get();
    }

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?StockProduct
    {
        /** @var StockProduct|null $product */
        $product = $this->productModel->newQuery()
            ->where('id', $id)
            ->where('apartment_id', $apartmentId)
            ->first();

        return $product;
    }

    public function create(array $data): StockProduct
    {
        /** @var StockProduct $product */
        $product = $this->productModel->newQuery()->create($data);

        return $product;
    }

    public function update(StockProduct $product, array $data): StockProduct
    {
        $product->fill($data);
        $product->save();

        return $product;
    }

    public function delete(StockProduct $product): void
    {
        $product->delete();
    }
}
