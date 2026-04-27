<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Dto\Product\ProductDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductDto
 */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'apartmentId' => $this->apartmentId,
            'name' => $this->name,
            'importance' => $this->importance,
            'categoryId' => $this->categoryId,
            'departmentId' => $this->departmentId,
            'description' => $this->description,
        ];
    }
}
