<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractActiveApartmentController;
use App\Http\Requests\StoreArchivedAcquisitionItemRequest;
use App\Http\Requests\UpdateArchivedAcquisitionItemRequest;
use App\Http\Resources\ArchivedAcquisitionItem\ArchivedAcquisitionItemResource;
use App\Services\Contracts\ArchivedAcquisitionItemServiceInterface;
use App\Services\Contracts\ApartmentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArchivedAcquisitionItemController extends AbstractActiveApartmentController
{
    public function __construct(
        private readonly ArchivedAcquisitionItemServiceInterface $archivedItemService,
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

        $items = $this->archivedItemService->getAllByApartmentId($managedApartment->apartment->id);

        return response()->json([
            'archivedItems' => ArchivedAcquisitionItemResource::collection($items),
        ]);
    }

    public function show(int $id, Request $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $item = $this->archivedItemService->findByIdAndApartmentId($id, $managedApartment->apartment->id);

        if ($item === null) {
            return response()->json(['message' => 'Archived item not found.'], 404);
        }

        return response()->json([
            'archivedItem' => new ArchivedAcquisitionItemResource($item),
        ]);
    }
}
