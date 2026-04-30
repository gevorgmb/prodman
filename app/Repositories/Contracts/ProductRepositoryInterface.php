<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface extends EloquentRepositoryInterface
{
    public function getAllByApartmentId(int $apartmentId): Collection;

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?Product;

    public function findByNameAndApartmentId(string $name, int $apartmentId): ?Product;

    public function create(array $data): Product;

    public function update(Product $product, array $data): Product;

    public function delete(Product $product): void;
}
