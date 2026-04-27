<?php

declare(strict_types=1);

namespace App\Dto\Product;

use App\Models\Product;
use Illuminate\Support\Carbon;

readonly class ProductDto
{
    public function __construct(
        public int $id,
        public int $apartmentId,
        public string $name,
        public int $importance,
        public ?int $categoryId,
        public ?int $departmentId,
        public ?string $description,
        public ?Carbon $createdAt,
        public ?Carbon $updatedAt,
    ) {
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
            createdAt: $product->created_at,
            updatedAt: $product->updated_at,
        );
    }
}
