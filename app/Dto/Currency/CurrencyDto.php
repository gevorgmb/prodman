<?php

declare(strict_types=1);

namespace App\Dto\Currency;

use App\Models\Currency;

readonly class CurrencyDto
{
    public function __construct(
        public int $id,
        public string $code,
        public string $symbol,
    ) {
    }

    public static function fromModel(Currency $currency): self
    {
        return new self(
            id: $currency->id,
            code: $currency->code,
            symbol: $currency->symbol,
        );
    }
}
