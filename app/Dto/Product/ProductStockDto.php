<?php

declare(strict_types=1);

namespace App\Dto\Product;

use App\Dto\Category\CategoryDto;
use App\Dto\Department\DepartmentDto;
use App\Dto\StockProduct\StockProductDto;
use App\Models\Product;
use Illuminate\Support\Carbon;

readonly class ProductStockDto extends ProductDto
{

    public ?array $stockProducts;
    public ?CategoryDto $category;
    public ?DepartmentDto $department;
    public ?float $totalCount;
    public function __construct(
        int $id,
        int $apartmentId,
        string $name,
        int $importance,
        ?int $categoryId,
        ?int $departmentId,
        ?string $description,
        float $min,
        string $unit,
        bool $mergeStock,
        ?Carbon $createdAt,
        ?Carbon $updatedAt,
        ?array $stockProducts = null,
        ?CategoryDto $category = null,
        ?DepartmentDto $department = null,
        ?float $totalCount = null,
    ) {
        $this->stockProducts = $stockProducts;
        $this->category = $category;
        $this->department = $department;
        $this->totalCount = $totalCount;
        parent::__construct(
            id: $id,
            apartmentId: $apartmentId,
            name: $name,
            importance: $importance,
            categoryId: $categoryId,
            departmentId: $departmentId,
            description: $description,
            min: $min,
            unit: $unit,
            mergeStock: $mergeStock,
            createdAt: $createdAt,
            updatedAt: $updatedAt,
        );
    }

    public static function fromModel(Product $product): self
    {
        $stockProducts = null;
        $totalCount = 0;
        if (! empty($product->stockProducts)) {
            $stockProducts = [];
            foreach ($product->stockProducts as $stockProduct) {
                $stockProducts[] = StockProductDto::fromModel($stockProduct);
                $totalCount += $stockProduct->quantity_available;
            }
        }
        $category = $department = null;
        if (! empty($product->category)) {
            $category = CategoryDto::fromModel($product->category);
        }
        if (! empty($product->department)) {
            $department = DepartmentDto::fromModel($product->department);
        }
        return new self(
            id: $product->id,
            apartmentId: $product->apartment_id,
            name: $product->name,
            importance: $product->importance,
            categoryId: $product->category_id,
            departmentId: $product->department_id,
            description: $product->description,
            min: $product->min,
            unit: $product->unit,
            mergeStock: $product->merge_stock ?? false,
            createdAt: $product->created_at,
            updatedAt: $product->updated_at,
            stockProducts: $stockProducts,
            category: $category,
            department: $department,
            totalCount: $totalCount,
        );
    }
}
