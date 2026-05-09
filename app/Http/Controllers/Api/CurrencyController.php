<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use App\Services\Contracts\CurrencyServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct(
        private readonly CurrencyServiceInterface $currencyService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'currencies' => CurrencyResource::collection(
                $this->currencyService->getAll()
            ),
        ]);
    }
}
