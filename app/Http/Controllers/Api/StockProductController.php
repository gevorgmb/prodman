<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractActiveApartmentController;
use App\Http\Requests\StoreStockProductRequest;
use App\Http\Requests\UpdateStockProductRequest;
use App\Http\Resources\StockProduct\StockProductResource;
use App\Services\Contracts\ApartmentServiceInterface;
use App\Services\Contracts\StockProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StockProductController extends AbstractActiveApartmentController
{
    public function __construct(
        private readonly StockProductServiceInterface $stockProductService,
        private readonly ApartmentServiceInterface $apartmentService,
    ) {
        parent::__construct($this->apartmentService);
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $products = $this->stockProductService->getAllByApartmentId($managedApartment->apartment->id);

        return response()->json([
            'stockProducts' => StockProductResource::collection($products),
        ]);
    }

    public function show(int $id, Request $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $product = $this->stockProductService->findByIdAndApartmentId($id, $managedApartment->apartment->id);

        if ($product === null) {
            return response()->json(['message' => 'Stock product not found.'], 404);
        }

        return response()->json([
            'stockProduct' => new StockProductResource($product),
        ]);
    }
}
