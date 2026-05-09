<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Currency\CurrencyDto;
use App\Repositories\Contracts\CurrencyRepositoryInterface;
use App\Services\Contracts\CurrencyServiceInterface;
use Illuminate\Support\Collection;

readonly class CurrencyService implements CurrencyServiceInterface
{
    public function __construct(
        private CurrencyRepositoryInterface $currencyRepository,
    ) {
    }

    public function getAll(): Collection
    {
        return $this->currencyRepository->getAll()
            ->map(fn ($currency) => CurrencyDto::fromModel($currency));
    }
}
