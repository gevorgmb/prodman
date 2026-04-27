<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dto\Product\ProductDto;
use Illuminate\Support\Collection;

interface ProductServiceInterface
{
    public function getAllByApartmentId(int $apartmentId): Collection;

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?ProductDto;

    public function create(array $data, int $apartmentId): ProductDto;

    public function update(int $id, array $data): ProductDto;

    public function delete(int $id, int $apartmentId): void;
}
