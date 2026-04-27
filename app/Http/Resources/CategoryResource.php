<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Dto\Category\CategoryDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CategoryDto
 */
class CategoryResource extends JsonResource
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
