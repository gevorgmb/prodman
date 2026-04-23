<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Apartment\ApartmentMembershipDto;
use App\Dto\Apartment\ApartmentUserLookupDto;
use App\Enums\ApartmentUserRoleEnum;
use App\Exceptions\Apartment\ApartmentMembershipNotFoundException;
use App\Exceptions\Apartment\ApartmentNotFoundException;
use App\Exceptions\Apartment\ApartmentOwnerCannotBeRemovedException;
use App\Exceptions\Apartment\OwnerCannotDisconnectException;
use App\Exceptions\Apartment\UserAlreadyInApartmentException;
use App\Exceptions\Apartment\UserNotFoundException;
use App\Models\Apartment;
use App\Models\User;
use App\Repositories\Contracts\ApartmentRepositoryInterface;
use App\Repositories\Contracts\ApartmentUserRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\ApartmentUserServiceInterface;
use Illuminate\Support\Collection;

readonly class ApartmentUserService implements ApartmentUserServiceInterface
{
    public function __construct(
        private ApartmentRepositoryInterface     $apartmentRepository,
        private ApartmentUserRepositoryInterface $apartmentUserRepository,
        private UserRepositoryInterface          $userRepository,
    ) {
    }

    public function findByEmail(Apartment $apartment, string $email): ApartmentUserLookupDto
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user === null) {
            throw new UserNotFoundException();
        }

        $membership = $this->apartmentUserRepository->findByApartmentIdAndUserId($apartment->id, $user->id);

        return new ApartmentUserLookupDto(
            user: $user,
            alreadyInApartment: $membership !== null,
        );
    }

    public function findByPhone(Apartment $apartment, string $phone): ApartmentUserLookupDto
    {
        $user = $this->userRepository->findByPhone($phone);
        if ($user === null) {
            throw new UserNotFoundException();
        }

        $membership = $this->apartmentUserRepository->findByApartmentIdAndUserId($apartment->id, $user->id);

        return new ApartmentUserLookupDto(
            user: $user,
            alreadyInApartment: $membership !== null,
        );
    }

    public function addUserToApartment(Apartment $apartment, int $targetUserId, ?string $role): ApartmentMembershipDto
    {
        $resolvedRole = ApartmentUserRoleEnum::roleByValue((string) $role) ?? ApartmentUserRoleEnum::MEMBER;

        $existing = $this->apartmentUserRepository->findByApartmentIdAndUserId($apartment->id, $targetUserId);
        if ($existing !== null) {
            throw new UserAlreadyInApartmentException();
        }

        $membership = $this->apartmentUserRepository->createMembership($apartment->id, $targetUserId, $resolvedRole);

        return new ApartmentMembershipDto(
            id: $membership->id,
            apartmentId: $membership->apartment_id,
            userId: $membership->user_id,
            role: $membership->role,
        );
    }

    public function getUsers(Apartment $apartment): Collection
    {
        $members = $this->apartmentUserRepository->getUsersByApartmentId($apartment->id);

        return $members->map(function ($member) {
            return [
                'membershipId' => $member->id,
                'role' => $member->role,
                'user' => [
                    'id' => $member->user?->id,
                    'name' => $member->user?->name,
                    'email' => $member->user?->email,
                    'phone' => $member->user?->phone,
                    'emailVerifiedAt' => $member->user?->email_verified_at,
                    'phoneVerifiedAt' => $member->user?->phone_verified_at,
                ],
            ];
        })->values();
    }

    public function removeUserFromApartment(Apartment $apartment, int $userId): void
    {
        if ($userId === $apartment->owner_id) {
            throw new ApartmentOwnerCannotBeRemovedException();
        }

        $membership = $this->apartmentUserRepository->findByApartmentIdAndUserId($apartment->id, $userId);
        if ($membership === null) {
            throw new ApartmentMembershipNotFoundException();
        }

        $membership->delete();
    }

    public function disconnectFromApartment(User $authUser, int $apartmentId): void
    {
        /** @var Apartment|null $apartment */
        $apartment = $this->apartmentRepository->find($apartmentId);
        if ($apartment === null) {
            throw new ApartmentNotFoundException();
        }

        if ($apartment->owner_id === $authUser->id) {
            throw new OwnerCannotDisconnectException();
        }

        $membership = $this->apartmentUserRepository->findByApartmentIdAndUserId($apartmentId, $authUser->id);
        if ($membership === null) {
            throw new ApartmentMembershipNotFoundException('You are not a member of this apartment.');
        }

        $membership->delete();
    }
}
