<?php

namespace App\Http\Resources\Acquisition;

use App\Dto\Acquisition\AcquisitionDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property AcquisitionDto $resource
 */
class AcquisitionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'apartmentId' => $this->resource->apartmentId,
            'storeName' => $this->resource->storeName,
            'description' => $this->resource->description,
            'status' => $this->resource->status->value,
            'userId' => $this->resource->userId,
            'createdAt' => $this->resource->createdAt,
            'updatedAt' => $this->resource->updatedAt,
        ];
    }
}
