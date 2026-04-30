<?php

namespace App\Http\Resources\AcquisitionItem;

use App\Dto\AcquisitionItem\AcquisitionItemDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property AcquisitionItemDto $resource
 */
class AcquisitionItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'acquisitionId' => $this->resource->acquisitionId,
            'productId' => $this->resource->productId,
            'productName' => $this->resource->productName,
            'description' => $this->resource->description,
            'expirationDate' => $this->resource->expirationDate,
            'quantity' => $this->resource->quantity,
            'price' => $this->resource->price,
            'total' => $this->resource->total,
            'createdAt' => $this->resource->createdAt,
            'updatedAt' => $this->resource->updatedAt,
        ];
    }
}
