<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractActiveApartmentController;
use App\Http\Requests\StoreAcquisitionItemRequest;
use App\Http\Requests\UpdateAcquisitionItemRequest;
use App\Http\Resources\AcquisitionItem\AcquisitionItemResource;
use App\Services\Contracts\AcquisitionItemServiceInterface;
use App\Services\Contracts\AcquisitionServiceInterface;
use App\Services\Contracts\ApartmentServiceInterface;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcquisitionItemController extends AbstractActiveApartmentController
{
    public function __construct(
        private readonly AcquisitionItemServiceInterface $itemService,
        private readonly AcquisitionServiceInterface $acquisitionService,
        private readonly ApartmentServiceInterface $apartmentService,
    ) {
        parent::__construct($this->apartmentService);
    }

    private function validateAcquisition(int $acquisitionId, Request $request): ?JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $acquisition = $this->acquisitionService->findByIdAndApartmentId(
            $acquisitionId,
            $managedApartment->apartment->id
        );

        if ($acquisition === null) {
            return response()->json(['message' => 'Acquisition not found.'], 404);
        }

        return null; // Valid
    }

    public function index(int $acquisitionId, Request $request): JsonResponse
    {
        if ($error = $this->validateAcquisition($acquisitionId, $request)) {
            return $error;
        }

        $items = $this->itemService->getAllByAcquisitionId($acquisitionId);

        return response()->json([
            'items' => AcquisitionItemResource::collection($items),
        ]);
    }

    public function show(int $acquisitionId, int $id, Request $request): JsonResponse
    {
        if ($error = $this->validateAcquisition($acquisitionId, $request)) {
            return $error;
        }

        $item = $this->itemService->findByIdAndAcquisitionId($id, $acquisitionId);

        if ($item === null) {
            return response()->json(['message' => 'Acquisition item not found.'], 404);
        }

        return response()->json([
            'item' => new AcquisitionItemResource($item),
        ]);
    }

    public function store(int $acquisitionId, StoreAcquisitionItemRequest $request): AcquisitionItemResource|JsonResponse
    {
        if ($error = $this->validateAcquisition($acquisitionId, $request)) {
            return $error;
        }

        $item = $this->itemService->create(
            $request->validated(),
            $acquisitionId
        );

        return new AcquisitionItemResource($item)
            ->response()
            ->setStatusCode(201);
    }

    public function update(int $acquisitionId, int $id, UpdateAcquisitionItemRequest $request): JsonResponse
    {
        if ($error = $this->validateAcquisition($acquisitionId, $request)) {
            return $error;
        }

        $item = $this->itemService->findByIdAndAcquisitionId($id, $acquisitionId);

        if ($item === null) {
            return response()->json(['message' => 'Acquisition item not found.'], 404);
        }

        $updatedItem = $this->itemService->update(
            $id,
            $acquisitionId,
            $request->validated(),
        );

        return response()->json([
            'item' => new AcquisitionItemResource($updatedItem),
        ]);
    }

    public function destroy(int $acquisitionId, int $id, Request $request): JsonResponse
    {
        if ($error = $this->validateAcquisition($acquisitionId, $request)) {
            return $error;
        }

        $item = $this->itemService->findByIdAndAcquisitionId($id, $acquisitionId);

        if ($item === null) {
            return response()->json(['message' => 'Acquisition item not found.'], 404);
        }

        $this->itemService->delete($id, $acquisitionId);

        return response()->json(null, 204);
    }
}
