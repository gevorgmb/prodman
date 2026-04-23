<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApartmentRequest;
use App\Http\Requests\UpdateApartmentRequest;
use App\Http\Resources\ApartmentResource;
use App\Http\Resources\RelatedApartmentResource;
use App\Models\User;
use App\Services\Contracts\ApartmentServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApartmentController extends Controller
{
    public function __construct(
        private readonly ApartmentServiceInterface $apartmentService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        /** @var User|null $authUser */
        $authUser = $request->user();
        if ($authUser === null) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $apartments = $this->apartmentService->getRelatedApartments($authUser->id);

        return response()->json([
            'apartments' => RelatedApartmentResource::collection($apartments)->resolve(),
        ]);
    }

    public function store(StoreApartmentRequest $request): JsonResponse
    {
        /** @var User|null $authUser */
        $authUser = $request->user();
        if ($authUser === null) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $apartment = $this->apartmentService->createForOwner($authUser->id, $request->validated());

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

        $apartment = $this->apartmentService->findForOwner($id, $authUser->id);
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

        $apartment = $this->apartmentService->findForOwner($id, $authUser->id);
        if ($apartment === null) {
            return response()->json(['message' => 'Apartment not found.'], 404);
        }

        $apartment = $this->apartmentService->updateForOwner($apartment, $request->validated());

        return new ApartmentResource($apartment);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        /** @var User|null $authUser */
        $authUser = $request->user();
        if ($authUser === null) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $apartment = $this->apartmentService->findForOwner($id, $authUser->id);
        if ($apartment === null) {
            return response()->json(['message' => 'Apartment not found.'], 404);
        }

        $this->apartmentService->deleteForOwner($apartment);

        return response()->json([], 204);
    }
}
