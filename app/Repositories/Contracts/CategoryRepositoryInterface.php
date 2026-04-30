<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface extends EloquentRepositoryInterface
{
    public function getAllByApartmentId(int $apartmentId): Collection;

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?Category;

    public function findByNameAndApartmentId(string $name, int $apartmentId): ?Category;

    public function create(array $data): Category;

    public function update(Category $category, array $data): Category;

    public function delete(Category $category): void;
}
