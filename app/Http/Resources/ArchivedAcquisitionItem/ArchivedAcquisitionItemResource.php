<?php

namespace App\Http\Resources\ArchivedAcquisitionItem;

use App\Dto\ArchivedAcquisitionItem\ArchivedAcquisitionItemDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property ArchivedAcquisitionItemDto $resource
 */
class ArchivedAcquisitionItemResource extends JsonResource
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
            'archiveDate' => $this->resource->archiveDate,
            'createdAt' => $this->resource->createdAt,
            'updatedAt' => $this->resource->updatedAt,
        ];
    }
}
