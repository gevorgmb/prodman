<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AbstractActiveApartmentController;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Models\User;
use App\Services\ApartmentService;
use App\Services\Contracts\DepartmentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends AbstractActiveApartmentController
{
    public function __construct(
        private readonly DepartmentServiceInterface $departmentService,
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
            'departments' => DepartmentResource::collection(
                $this->departmentService->getAllByApartmentId($managedApartment->apartment->id)
            )->resolve(),
        ]);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
        $department = $this->departmentService->findByIdAndApartmentId(
            $id,
            $managedApartment->apartment->id,
        );

        if ($department === null) {
            return response()->json(['message' => 'Department not found.'], 404);
        }

        return response()->json([
            'department' => new DepartmentResource($department),
        ]);
    }

    public function store(StoreDepartmentRequest $request): DepartmentResource|JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $department = $this->departmentService->create(
            $request->validated(),
            $managedApartment->apartment->id,
        );

        return new DepartmentResource($department)
            ->response()
            ->setStatusCode(201);
    }

    public function update(int $id, UpdateDepartmentRequest $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $department = $this->departmentService->findByIdAndApartmentId(
            $id,
            $managedApartment->apartment->id,
        );

        if ($department === null) {
            return response()->json(['message' => 'Department not found.'], 404);
        }

        $updatedDepartment = $this->departmentService->update(
            $id,
            $request->validated(),
        );

        return response()->json([
            'department' => new DepartmentResource($updatedDepartment),
        ]);
    }

    public function destroy(int $id, Request $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $department = $this->departmentService->findByIdAndApartmentId(
            $id,
            $managedApartment->apartment->id,
        );

        if ($department === null) {
            return response()->json(['message' => 'Department not found.'], 404);
        }

        $this->departmentService->delete($id, $managedApartment->apartment->id);

        return response()->json(null, 204);
    }
}
