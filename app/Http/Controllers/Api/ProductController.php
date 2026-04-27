<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractActiveApartmentController;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ApartmentService;
use App\Services\Contracts\ProductServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends AbstractActiveApartmentController
{
    public function __construct(
        private readonly ProductServiceInterface $productService,
        private readonly ApartmentService $apartmentService,
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

        return response()->json([
            'products' => ProductResource::collection(
                $this->productService->getAllByApartmentId($managedApartment->apartment->id)
            )->resolve(),
        ]);
    }

    public function show(int $id, Request $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $product = $this->productService->findByIdAndApartmentId($id, $managedApartment->apartment->id);

        if ($product === null) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        return response()->json([
            'product' => new ProductResource($product),
        ]);
    }

    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $product = $this->productService->create(
            $request->validated(),
            $managedApartment->apartment->id,
        );

        return response()->json([
            'product' => new ProductResource($product),
        ], 201);
    }

    public function update(int $id, UpdateProductRequest $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $product = $this->productService->findByIdAndApartmentId($id, $managedApartment->apartment->id);

        if ($product === null) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $updatedProduct = $this->productService->update($id, $request->validated());

        return response()->json([
            'product' => new ProductResource($updatedProduct),
        ]);
    }

    public function destroy(int $id, Request $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $product = $this->productService->findByIdAndApartmentId($id, $managedApartment->apartment->id);

        if ($product === null) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $this->productService->delete($id, $managedApartment->apartment->id);

        return response()->json(null, 204);
    }
}
