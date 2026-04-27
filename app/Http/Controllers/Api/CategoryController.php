<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractActiveApartmentController;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\ApartmentService;
use App\Services\Contracts\CategoryServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends AbstractActiveApartmentController
{
    public function __construct(
        private readonly CategoryServiceInterface $categoryService,
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
            'categories' => CategoryResource::collection(
                $this->categoryService->getAllByApartmentId($managedApartment->apartment->id)
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
        $category = $this->categoryService->findByIdAndApartmentId(
            $id,
            $managedApartment->apartment->id,
        );

        if ($category === null) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        return response()->json([
            'category' => new CategoryResource($category),
        ]);
    }

    public function store(StoreCategoryRequest $request): CategoryResource|JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $category = $this->categoryService->create(
            $request->validated(),
            $managedApartment->apartment->id,
        );

        return new CategoryResource($category)
            ->response()
            ->setStatusCode(201);
    }

    public function update(int $id, UpdateCategoryRequest $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $category = $this->categoryService->findByIdAndApartmentId(
            $id,
            $managedApartment->apartment->id,
        );

        if ($category === null) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        $updatedCategory = $this->categoryService->update($id, $request->validated());

        return response()->json([
            'category' => new CategoryResource($updatedCategory),
        ]);
    }

    public function destroy(int $id, Request $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $category = $this->categoryService->findByIdAndApartmentId(
            $id,
            $managedApartment->apartment->id,
        );

        if ($category === null) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        $this->categoryService->delete($id, $managedApartment->apartment->id);

        return response()->json(null, 204);
    }
}
