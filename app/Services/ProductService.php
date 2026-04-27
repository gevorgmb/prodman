<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Product\ProductDto;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Support\Collection;

readonly class ProductService implements ProductServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {
    }

    public function getAllByApartmentId(int $apartmentId): Collection
    {
        return $this->productRepository->getAllByApartmentId($apartmentId);
    }

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?ProductDto
    {
        $product = $this->productRepository->findByIdAndApartmentId($id, $apartmentId);

        return $product === null ? null : ProductDto::fromModel($product);
    }

    public function create(array $data, int $apartmentId): ProductDto
    {
        $data['apartment_id'] = $apartmentId;
        $data['category_id'] = empty($data['categoryId']) ? null : (int) $data['categoryId'];
        $data['department_id'] = empty($data['departmentId']) ? null : (int) $data['departmentId'];

        return ProductDto::fromModel($this->productRepository->create($data));
    }

    public function update(int $id, array $data): ProductDto
    {
        /** @var Product $product */
        $product = $this->productRepository->find($id);
        if ($product === null) {
            throw new \RuntimeException('Product not found.');
        }
        $data['category_id'] = empty($data['categoryId']) ? $product->category_id : (int) $data['categoryId'];
        $data['department_id'] = empty($data['departmentId']) ? $product->department_id : (int) $data['departmentId'];

        return ProductDto::fromModel($this->productRepository->update($product, $data));
    }

    public function delete(int $id, int $apartmentId): void
    {
        /** @var Product $product */
        $product = $this->productRepository->findByIdAndApartmentId($id, $apartmentId);
        if ($product === null) {
            throw new \RuntimeException('Product not found.');
        }

        $this->productRepository->delete($product);
    }
}
