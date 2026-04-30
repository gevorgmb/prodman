<?php

namespace App\Http\Resources\StockProduct;

use App\Dto\StockProduct\StockProductDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property StockProductDto $resource
 */
class StockProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'apartmentId' => $this->resource->apartmentId,
            'itemId' => $this->resource->itemId,
            'productName' => $this->resource->productName,
            'quantity' => $this->resource->quantity,
            'quantityUsed' => $this->resource->quantityUsed,
            'expirationDate' => $this->resource->expirationDate,
            'createdAt' => $this->resource->createdAt,
            'updatedAt' => $this->resource->updatedAt,
        ];
    }
}
