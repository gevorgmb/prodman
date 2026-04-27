<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Category\CategoryDto;
use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Services\Contracts\CategoryServiceInterface;
use Illuminate\Support\Collection;

readonly class CategoryService implements CategoryServiceInterface
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    ) {
    }

    public function getAllByApartmentId(int $apartmentId): Collection
    {
        return $this->categoryRepository->getAllByApartmentId($apartmentId);
    }

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?CategoryDto
    {
        $category = $this->categoryRepository->findByIdAndApartmentId($id, $apartmentId);

        return $category === null ? null : CategoryDto::fromModel($category);
    }

    public function create(array $data, int $apartmentId): CategoryDto
    {
        $data['apartment_id'] = $apartmentId;
        return CategoryDto::fromModel(
            $this->categoryRepository->create($data)
        );
    }

    public function update(int $id, array $data): CategoryDto
    {
        /** @var Category $category */
        $category = $this->categoryRepository->find($id);
        if ($category === null) {
            throw new \RuntimeException('Category not found.');
        }

        return CategoryDto::fromModel($this->categoryRepository->update($category, $data));
    }

    public function delete(int $id, int $apartmentId): void
    {
        /** @var Category $category */
        $category = $this->categoryRepository->findByIdAndApartmentId($id, $apartmentId);
        if ($category === null) {
            throw new \RuntimeException('Category not found.');
        }

        $this->categoryRepository->delete($category);
    }
}
