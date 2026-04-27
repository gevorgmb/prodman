<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dto\Category\CategoryDto;
use Illuminate\Support\Collection;

interface CategoryServiceInterface
{
    public function getAllByApartmentId(int $apartmentId): Collection;

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?CategoryDto;

    public function create(array $data, int $apartmentId): CategoryDto;

    public function update(int $id, array $data): CategoryDto;

    public function delete(int $id, int $apartmentId): void;
}
