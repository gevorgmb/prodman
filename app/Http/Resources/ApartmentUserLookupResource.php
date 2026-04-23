<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Dto\Apartment\ApartmentUserLookupDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ApartmentUserLookupDto
 */
class ApartmentUserLookupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'phone' => $this->user->phone,
                'emailVerifiedAt' => $this->user->email_verified_at,
                'phoneVerifiedAt' => $this->user->phone_verified_at,
            ],
            'alreadyInApartment' => $this->alreadyInApartment,
        ];
    }
}
