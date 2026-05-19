<?php

declare(strict_types=1);

namespace App\Dto\Product;

use App\Models\Product;
use Illuminate\Support\Carbon;

readonly class ProductDto
{
    public int $id;
    public int $apartmentId;
    public string $name;
    public int $importance;
    public ?int $categoryId;
    public ?int $departmentId;
    public ?string $description;
    public float $min;
    public string $unit;
    public bool $mergeStock;
    public ?Carbon $createdAt;
    public ?Carbon $updatedAt;
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
    ) {
        $this->id = $id;
        $this->apartmentId = $apartmentId;
        $this->name = $name;
        $this->importance = $importance;
        $this->categoryId = $categoryId;
        $this->departmentId = $departmentId;
        $this->description = $description;
        $this->min = $min;
        $this->unit = $unit;
        $this->mergeStock = $mergeStock;
    }

    public static function fromModel(Product $product): self
    {
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
        );
    }
}
