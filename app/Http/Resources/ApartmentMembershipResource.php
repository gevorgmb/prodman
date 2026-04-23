<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Dto\Apartment\ApartmentMembershipDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ApartmentMembershipDto
 */
class ApartmentMembershipResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'apartmentId' => $this->apartmentId,
            'userId' => $this->userId,
            'role' => $this->role,
        ];
    }
}
