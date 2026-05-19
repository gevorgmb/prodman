<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dto\StockProduct\StockProductDto;
use Illuminate\Support\Collection;

interface StockProductServiceInterface
{
    /**
     * @return Collection<int, StockProductDto>
     */
    public function getAllByApartmentId(int $apartmentId): Collection;

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?StockProductDto;

    public function findById(int $id): ?StockProductDto;

    public function mergeByProductId(int $productId): ?StockProductDto;

    public function create(array $data, int $apartmentId): StockProductDto;

    public function update(int $id, int $apartmentId, array $data): StockProductDto;

    public function delete(int $id, int $apartmentId): void;
}
