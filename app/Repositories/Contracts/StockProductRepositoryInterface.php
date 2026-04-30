<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\StockProduct;
use Illuminate\Database\Eloquent\Collection;

interface StockProductRepositoryInterface extends EloquentRepositoryInterface
{
    public function getAllByApartmentId(int $apartmentId): Collection;

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?StockProduct;

    public function create(array $data): StockProduct;

    public function update(StockProduct $product, array $data): StockProduct;

    public function delete(StockProduct $product): void;
}
