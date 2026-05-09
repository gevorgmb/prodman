<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Dto\Currency\CurrencyDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin CurrencyDto
 */
class CurrencyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'symbol' => $this->symbol,
        ];
    }
}
