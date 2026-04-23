<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Dto\Apartment\ManagedApartmentDto;
use App\Exceptions\Apartment\ApartmentAccessDeniedException;
use App\Exceptions\Apartment\ApartmentHeaderMissingException;
use App\Exceptions\Apartment\ApartmentMembershipNotFoundException;
use App\Exceptions\Apartment\ApartmentNotFoundException;
use App\Exceptions\Apartment\ApartmentOwnerCannotBeRemovedException;
use App\Exceptions\Apartment\OwnerCannotDisconnectException;
use App\Exceptions\Apartment\UnauthenticatedException;
use App\Exceptions\Apartment\UserAlreadyInApartmentException;
use App\Exceptions\Apartment\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddApartmentUserRequest;
use App\Http\Requests\ApartmentUserFindByEmailRequest;
use App\Http\Requests\ApartmentUserFindByPhoneRequest;
use App\Http\Resources\ApartmentMembershipResource;
use App\Http\Resources\ApartmentUserLookupResource;
use App\Http\Resources\RelatedApartmentResource;
use App\Models\User;
use App\Services\Contracts\ApartmentServiceInterface;
use App\Services\Contracts\ApartmentUserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApartmentUserController extends Controller
{
    public function __construct(
        private readonly ApartmentServiceInterface $apartmentService,
        private readonly ApartmentUserServiceInterface $apartmentUserService,
    ) {
    }

    public function findByEmail(ApartmentUserFindByEmailRequest $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);

            $result = $this->apartmentUserService->findByEmail(
                $managedApartment->apartment,
                $request->validated('email')
            );

            return (new ApartmentUserLookupResource($result))->response();
        } catch (ApartmentHeaderMissingException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (ApartmentNotFoundException|ApartmentAccessDeniedException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (UnauthenticatedException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        } catch (UserNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function findByPhone(ApartmentUserFindByPhoneRequest $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);

            $result = $this->apartmentUserService->findByPhone(
                $managedApartment->apartment,
                $request->validated('phone')
            );

            return (new ApartmentUserLookupResource($result))->response();
        } catch (ApartmentHeaderMissingException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (ApartmentNotFoundException|ApartmentAccessDeniedException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (UnauthenticatedException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        } catch (UserNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function addUserToApartment(AddApartmentUserRequest $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);

            $result = $this->apartmentUserService->addUserToApartment(
                $managedApartment->apartment,
                (int) $request->validated('user_id'),
                $request->validated('role')
            );

            return (new ApartmentMembershipResource($result))
                ->response()
                ->setStatusCode(201);
        } catch (ApartmentHeaderMissingException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (ApartmentNotFoundException|ApartmentAccessDeniedException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (UnauthenticatedException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        } catch (UserAlreadyInApartmentException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function getUsers(Request $request): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);

            $result = $this->apartmentUserService->getUsers($managedApartment->apartment);

            return response()->json([
                'users' => $result,
            ]);
        } catch (ApartmentHeaderMissingException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (ApartmentNotFoundException|ApartmentAccessDeniedException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (UnauthenticatedException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function removeUserFromApartment(Request $request, int $userId): JsonResponse
    {
        try {
            $managedApartment = $this->resolveManagedApartment($request);

            $this->apartmentUserService->removeUserFromApartment($managedApartment->apartment, $userId);

            return response()->json([], 204);
        } catch (ApartmentHeaderMissingException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (ApartmentNotFoundException|ApartmentAccessDeniedException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (UnauthenticatedException $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        } catch (ApartmentOwnerCannotBeRemovedException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (ApartmentMembershipNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    public function disconnectFromApartment(Request $request, int $apartmentId): JsonResponse
    {
        /** @var User|null $authUser */
        $authUser = $request->user();
        if ($authUser === null) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        try {
            $this->apartmentUserService->disconnectFromApartment($authUser, $apartmentId);

            return response()->json([], 204);
        } catch (ApartmentNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        } catch (OwnerCannotDisconnectException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (ApartmentMembershipNotFoundException $e) {
            return response()->json(['message' => $e->getMessage()], 404);
        }
    }

    private function resolveManagedApartment(Request $request): ManagedApartmentDto
    {
        return $this->apartmentService->getManagedApartment($request);
    }
}
