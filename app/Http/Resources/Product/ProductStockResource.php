<?php

declare(strict_types=1);

namespace App\Http\Resources\Product;

use app\Dto\Product\ProductStockDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ProductStockDto
 */
class ProductStockResource extends JsonResource
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
            'min' => $this->min,
            'unit' => $this->unit,
            'mergeStock' => $this->mergeStock,
            'category' => $this->category,
            'department' => $this->department,
            'stockProducts' => $this->stockProducts,
            'totalCount' => $this->totalCount,
        ];
    }
}
