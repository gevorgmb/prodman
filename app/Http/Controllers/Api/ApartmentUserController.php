<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\ApartmentUserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddApartmentUserRequest;
use App\Http\Requests\ApartmentUserFindByEmailRequest;
use App\Http\Requests\ApartmentUserFindByPhoneRequest;
use App\Models\Apartment;
use App\Models\User;
use App\Repositories\Contracts\ApartmentRepositoryInterface;
use App\Repositories\Contracts\ApartmentUserRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApartmentUserController extends Controller
{
    public function __construct(
        private readonly ApartmentRepositoryInterface $apartmentRepository,
        private readonly ApartmentUserRepositoryInterface $apartmentUserRepository,
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function findByEmail(ApartmentUserFindByEmailRequest $request): JsonResponse
    {
        $apartmentResult = $this->getManagedApartment($request);
        if ($apartmentResult instanceof JsonResponse) {
            return $apartmentResult;
        }

        $user = $this->userRepository->findByEmail($request->validated('email'));
        if ($user === null) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $membership = $this->apartmentUserRepository->findByApartmentIdAndUserId($apartmentResult->id, $user->id);

        return response()->json([
            'user' => $this->buildUserPayload($user),
            'alreadyInApartment' => $membership !== null,
        ]);
    }

    public function findByPhone(ApartmentUserFindByPhoneRequest $request): JsonResponse
    {
        $apartmentResult = $this->getManagedApartment($request);
        if ($apartmentResult instanceof JsonResponse) {
            return $apartmentResult;
        }

        $user = $this->userRepository->findByPhone($request->validated('phone'));
        if ($user === null) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $membership = $this->apartmentUserRepository->findByApartmentIdAndUserId($apartmentResult->id, $user->id);

        return response()->json([
            'user' => $this->buildUserPayload($user),
            'alreadyInApartment' => $membership !== null,
        ]);
    }

    public function addUserToApartment(AddApartmentUserRequest $request): JsonResponse
    {
        $apartmentResult = $this->getManagedApartment($request);
        if ($apartmentResult instanceof JsonResponse) {
            return $apartmentResult;
        }

        $targetUserId = (int) $request->validated('user_id');
        $role = ApartmentUserRoleEnum::roleByValue((string) ($request->validated('role')))
            ?? ApartmentUserRoleEnum::MEMBER;

        $existing = $this->apartmentUserRepository->findByApartmentIdAndUserId($apartmentResult->id, $targetUserId);
        if ($existing !== null) {
            return response()->json(['message' => 'User is already added to apartment.'], 422);
        }

        $membership = $this->apartmentUserRepository->createMembership($apartmentResult->id, $targetUserId, $role);

        return response()->json([
            'id' => $membership->id,
            'apartmentId' => $membership->apartment_id,
            'userId' => $membership->user_id,
            'role' => $membership->role,
        ], 201);
    }

    public function getUsers(Request $request): JsonResponse
    {
        $apartmentResult = $this->getManagedApartment($request);
        if ($apartmentResult instanceof JsonResponse) {
            return $apartmentResult;
        }

        $members = $this->apartmentUserRepository->getUsersByApartmentId($apartmentResult->id);

        return response()->json([
            'users' => $members->map(function ($member) {
                /** @var \App\Models\ApartmentUser $member */
                return [
                    'membershipId' => $member->id,
                    'role' => $member->role,
                    'user' => $this->buildUserPayload($member->user),
                ];
            })->values(),
        ]);
    }

    public function removeUserFromApartment(Request $request, int $userId): JsonResponse
    {
        $apartmentResult = $this->getManagedApartment($request);
        if ($apartmentResult instanceof JsonResponse) {
            return $apartmentResult;
        }

        if ($userId === $apartmentResult->owner_id) {
            return response()->json(['message' => 'Apartment owner cannot be removed.'], 422);
        }

        $membership = $this->apartmentUserRepository->findByApartmentIdAndUserId($apartmentResult->id, $userId);
        if ($membership === null) {
            return response()->json(['message' => 'User is not in apartment.'], 404);
        }

        $membership->delete();

        return response()->json([], 204);
    }

    public function disconnectFromApartment(Request $request, int $apartmentId): JsonResponse
    {
        /** @var User|null $authUser */
        $authUser = $request->user();
        if ($authUser === null) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        /** @var Apartment|null $apartment */
        $apartment = $this->apartmentRepository->find($apartmentId);
        if ($apartment === null) {
            return response()->json(['message' => 'Apartment not found.'], 404);
        }
        if ($apartment->owner_id === $authUser->id) {
            return response()->json(['message' => 'Owner cannot disconnect from own apartment.'], 422);
        }

        $membership = $this->apartmentUserRepository->findByApartmentIdAndUserId($apartmentId, $authUser->id);
        if ($membership === null) {
            return response()->json(['message' => 'You are not a member of this apartment.'], 404);
        }

        $membership->delete();

        return response()->json([], 204);
    }

    public function getRelatedApartments(Request $request): JsonResponse
    {
        /** @var User|null $authUser */
        $authUser = $request->user();
        if ($authUser === null) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $ownedApartments = $this->apartmentRepository->getAllByOwnerId($authUser->id);
        $memberships = $this->apartmentUserRepository->getMembershipsByUserId($authUser->id);

        $relatedById = [];

        foreach ($ownedApartments as $apartment) {
            /** @var Apartment $apartment */
            $relatedById[$apartment->id] = [
                'id' => $apartment->id,
                'name' => $apartment->name,
                'description' => $apartment->description,
                'isDefault' => (bool) $apartment->is_default,
                'ownerId' => $apartment->owner_id,
                'isOwner' => true,
                'role' => ApartmentUserRoleEnum::ADMIN->value,
            ];
        }

        foreach ($memberships as $membership) {
            /** @var \App\Models\ApartmentUser $membership */
            $apartment = $membership->apartment;
            if ($apartment === null) {
                continue;
            }
            if (isset($relatedById[$apartment->id])) {
                continue;
            }
            $relatedById[$apartment->id] = [
                'id' => $apartment->id,
                'name' => $apartment->name,
                'description' => $apartment->description,
                'isDefault' => (bool) $apartment->is_default,
                'ownerId' => $apartment->owner_id,
                'isOwner' => false,
                'role' => $membership->role,
            ];
        }

        return response()->json([
            'apartments' => array_values($relatedById),
        ]);
    }

    private function getManagedApartment(Request $request): Apartment|JsonResponse
    {
        /** @var User|null $authUser */
        $authUser = $request->user();
        if ($authUser === null) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $apartmentHeader = $request->header('apartment');
        if ($apartmentHeader === null || ! is_numeric($apartmentHeader)) {
            return response()->json(['message' => 'Invalid or missing apartment header.'], 422);
        }

        $apartmentId = (int) $apartmentHeader;
        /** @var Apartment|null $apartment */
        $apartment = $this->apartmentRepository->find($apartmentId);
        if ($apartment === null) {
            return response()->json(['message' => 'Apartment not found.'], 404);
        }

        if ($apartment->owner_id === $authUser->id) {
            return $apartment;
        }

        $membership = $this->apartmentUserRepository->findByApartmentIdAndUserId($apartmentId, $authUser->id);
        if ($membership !== null && $membership->role === ApartmentUserRoleEnum::ADMIN->value) {
            return $apartment;
        }

        return response()->json(['message' => 'Apartment not found or access denied.'], 404);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildUserPayload(?User $user): array
    {
        if ($user === null) {
            return [];
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'emailVerifiedAt' => $user->email_verified_at,
            'phoneVerifiedAt' => $user->phone_verified_at,
        ];
    }
}
