<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractActiveApartmentController;
use App\Http\Requests\StoreAcquisitionRequest;
use App\Http\Requests\UpdateAcquisitionRequest;
use App\Http\Resources\Acquisition\AcquisitionResource;
use App\Services\Contracts\AcquisitionServiceInterface;
use App\Services\Contracts\ApartmentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcquisitionController extends AbstractActiveApartmentController
{
    public function __construct(
        private readonly AcquisitionServiceInterface $acquisitionService,
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

        $acquisitions = $this->acquisitionService->getAllByApartmentId($managedApartment->apartment->id);

        return response()->json([
            'acquisitions' => AcquisitionResource::collection($acquisitions),
        ]);
    }

    public function show(int $id, Request $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $acquisition = $this->acquisitionService->findByIdAndApartmentId($id, $managedApartment->apartment->id);

        if ($acquisition === null) {
            return response()->json(['message' => 'Acquisition not found.'], 404);
        }

        return response()->json([
            'acquisition' => new AcquisitionResource($acquisition),
        ]);
    }

    public function store(StoreAcquisitionRequest $request): AcquisitionResource|JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $acquisition = $this->acquisitionService->create(
            $request->validated(),
            $managedApartment->apartment->id,
            $request->user()->id
        );

        return new AcquisitionResource($acquisition)
            ->response()
            ->setStatusCode(201);
    }

    public function update(int $id, UpdateAcquisitionRequest $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $acquisition = $this->acquisitionService->findByIdAndApartmentId(
            $id,
            $managedApartment->apartment->id
        );

        if ($acquisition === null) {
            return response()->json(['message' => 'Acquisition not found.'], 404);
        }

        $updatedAcquisition = $this->acquisitionService->update(
            $id,
            $managedApartment->apartment->id,
            $request->validated(),
        );

        return response()->json([
            'acquisition' => new AcquisitionResource($updatedAcquisition),
        ]);
    }

    public function destroy(int $id, Request $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $acquisition = $this->acquisitionService->findByIdAndApartmentId($id, $managedApartment->apartment->id);

        if ($acquisition === null) {
            return response()->json(['message' => 'Acquisition not found.'], 404);
        }

        $this->acquisitionService->delete($id, $managedApartment->apartment->id);

        return response()->json(null, 204);
    }
}
