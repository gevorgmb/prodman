<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use App\Http\Resources\ApartmentResource;
use App\Models\User;
use App\Repositories\Contracts\ApartmentRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApartmentController extends Controller
{
    public function __construct(
        private readonly ApartmentRepositoryInterface $apartmentRepository,
    ) {
    }

    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        /** @var User|null $authUser */
        $authUser = $request->user();
        if ($authUser === null) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $apartments = $this->apartmentRepository->getAllByOwnerId($authUser->id);

        return ApartmentResource::collection($apartments);
    }

    public function store(StoreApartmentRequest $request): JsonResponse
    {
        /** @var User|null $authUser */
        $authUser = $request->user();
        if ($authUser === null) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $apartment = $this->apartmentRepository->createForOwner($authUser->id, $request->validated());

        return (new ApartmentResource($apartment))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, int $id): ApartmentResource|JsonResponse
    {
        /** @var User|null $authUser */
        $authUser = $request->user();
        if ($authUser === null) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $apartment = $this->apartmentRepository->findByIdAndOwnerId($id, $authUser->id);
        if ($apartment === null) {
            return response()->json(['message' => 'Apartment not found.'], 404);
        }

        return new ApartmentResource($apartment);
    }

    public function update(UpdateApartmentRequest $request, int $id): ApartmentResource|JsonResponse
    {
        /** @var User|null $authUser */
        $authUser = $request->user();
        if ($authUser === null) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $apartment = $this->apartmentRepository->findByIdAndOwnerId($id, $authUser->id);
        if ($apartment === null) {
            return response()->json(['message' => 'Apartment not found.'], 404);
        }

        $apartment = $this->apartmentRepository->updateForOwner($apartment, $request->validated());

        return new ApartmentResource($apartment);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        /** @var User|null $authUser */
        $authUser = $request->user();
        if ($authUser === null) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $apartment = $this->apartmentRepository->findByIdAndOwnerId($id, $authUser->id);
        if ($apartment === null) {
            return response()->json(['message' => 'Apartment not found.'], 404);
        }

        $this->apartmentRepository->deleteForOwner($apartment);

        return response()->json([], 204);
    }
}
