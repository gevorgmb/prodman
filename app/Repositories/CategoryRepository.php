<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private readonly Category $categoryModel,
    ) {
        parent::__construct($categoryModel);
    }

    public function getAllByApartmentId(int $apartmentId): Collection
    {
        return $this->categoryModel->newQuery()
            ->where('apartment_id', $apartmentId)
            ->orderBy('name')
            ->get();
    }

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?Category
    {
        /** @var Category|null $category */
        $category = $this->categoryModel->newQuery()
            ->where('id', $id)
            ->where('apartment_id', $apartmentId)
            ->first();

        return $category;
    }

    public function findByNameAndApartmentId(string $name, int $apartmentId): ?Category
    {
        /** @var Category|null $category */
        $category = $this->categoryModel->newQuery()
            ->where('name', $name)
            ->where('apartment_id', $apartmentId)
            ->first();

        return $category;
    }

    public function create(array $data): Category
    {
        /** @var Category $category */
        $category = $this->categoryModel->newQuery()->create($data);

        return $category;
    }

    public function update(Category $category, array $data): Category
    {
        $category->fill($data);
        $category->save();

        return $category;
    }

    public function delete(Category $category): void
    {
        $category->delete();
    }
}
