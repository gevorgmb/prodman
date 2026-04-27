<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Dto\Department\DepartmentDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin DepartmentDto
 */
class DepartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'apartmentId' => $this->apartmentId,
            'name' => $this->name,
        ];
    }
}
