<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly Product $productModel,
    ) {
        parent::__construct($productModel);
    }

    public function getAllByApartmentId(int $apartmentId): Collection
    {
        return $this->productModel->newQuery()
            ->where('apartment_id', $apartmentId)
            ->orderByDesc('importance')
            ->orderBy('name')
            ->get();
    }

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?Product
    {
        /** @var Product|null $product */
        $product = $this->productModel->newQuery()
            ->where('id', $id)
            ->where('apartment_id', $apartmentId)
            ->first();

        return $product;
    }

    public function findByNameAndApartmentId(string $name, int $apartmentId): ?Product
    {
        /** @var Product|null $product */
        $product = $this->productModel->newQuery()
            ->where('name', $name)
            ->where('apartment_id', $apartmentId)
            ->first();

        return $product;
    }

    public function create(array $data): Product
    {
        /** @var Product $product */
        $product = $this->productModel->newQuery()->create($data);

        return $product;
    }

    public function update(Product $product, array $data): Product
    {
        $product->fill($data);
        $product->save();

        return $product;
    }

    public function delete(Product $product): void
    {
        $product->delete();
    }
}
