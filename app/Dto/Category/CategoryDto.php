<?php

declare(strict_types=1);

namespace App\Dto\Category;

use App\Models\Category;
use Illuminate\Support\Carbon;

readonly class CategoryDto
{
    public function __construct(
        public int $id,
        public int $apartmentId,
        public string $name,
        public ?Carbon $createdAt,
        public ?Carbon $updatedAt,
    ) {
    }

    public static function fromModel(Category $category): self
    {
        return new self(
            id: $category->id,
            apartmentId: $category->apartment_id,
            name: $category->name,
            createdAt: $category->created_at,
            updatedAt: $category->updated_at,
        );
    }
}
