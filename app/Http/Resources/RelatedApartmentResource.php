<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Dto\Apartment\RelatedApartmentDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin RelatedApartmentDto
 */
class RelatedApartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'isDefault' => $this->isDefault,
            'ownerId' => $this->ownerId,
            'isOwner' => $this->isOwner,
            'role' => $this->role,
        ];
    }
}
