<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Apartment\ManagedApartmentDto;
use App\Dto\Apartment\RelatedApartmentDto;
use App\Enums\ApartmentUserRoleEnum;
use App\Exceptions\Apartment\ApartmentAccessDeniedException;
use App\Exceptions\Apartment\ApartmentHeaderMissingException;
use App\Exceptions\Apartment\ApartmentNotFoundException;
use App\Exceptions\Apartment\UnauthenticatedException;
use App\Models\Apartment;
use App\Repositories\Contracts\ApartmentRepositoryInterface;
use App\Repositories\Contracts\ApartmentUserRepositoryInterface;
use App\Services\Contracts\ApartmentServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

readonly class ApartmentService implements ApartmentServiceInterface
{
    public function __construct(
        private ApartmentRepositoryInterface $apartmentRepository,
        private ApartmentUserRepositoryInterface $apartmentUserRepository,
    ) {
    }

    public function getAll(int $ownerId): Collection
    {
        return $this->apartmentRepository->getAllByOwnerId($ownerId);
    }

    public function createForOwner(int $ownerId, array $data): Apartment
    {
        return $this->apartmentRepository->createForOwner($ownerId, $data);
    }

    public function findForOwner(int $id, int $ownerId): ?Apartment
    {
        return $this->apartmentRepository->findByIdAndOwnerId($id, $ownerId);
    }

    public function updateForOwner(Apartment $apartment, array $data): Apartment
    {
        return $this->apartmentRepository->updateForOwner($apartment, $data);
    }

    public function deleteForOwner(Apartment $apartment): void
    {
        $this->apartmentRepository->deleteForOwner($apartment);
    }

    public function getManagedApartment(Request $request): ManagedApartmentDto
    {
        /** @var \App\Models\User|null $authUser */
        $authUser = $request->user();
        if ($authUser === null) {
            throw new UnauthenticatedException();
        }

        $apartmentHeader = $request->header('apartment');
        if ($apartmentHeader === null || ! is_numeric($apartmentHeader)) {
            throw new ApartmentHeaderMissingException();
        }

        $apartmentId = (int) $apartmentHeader;

        /** @var Apartment|null $apartment */
        $apartment = $this->apartmentRepository->find($apartmentId);
        if ($apartment === null) {
            throw new ApartmentNotFoundException();
        }

        if ($apartment->owner_id === $authUser->id) {
            return new ManagedApartmentDto(
                apartment: $apartment,
                isOwner: true,
                isAdmin: true,
            );
        }

        $membership = $this->apartmentUserRepository->findByApartmentIdAndUserId($apartmentId, $authUser->id);
        if ($membership !== null && $membership->role === ApartmentUserRoleEnum::ADMIN->value) {
            return new ManagedApartmentDto(
                apartment: $apartment,
                isOwner: false,
                isAdmin: true,
            );
        }

        throw new ApartmentAccessDeniedException();
    }

    public function getRelatedApartments(int $userId): Collection
    {
        $ownedApartments = $this->apartmentRepository->getAllByOwnerId($userId);
        $memberships = $this->apartmentUserRepository->getMembershipsByUserId($userId);

        $relatedById = [];

        foreach ($ownedApartments as $apartment) {
            $relatedById[$apartment->id] = new RelatedApartmentDto(
                id: $apartment->id,
                name: $apartment->name,
                description: $apartment->description,
                isDefault: (bool) $apartment->is_default,
                ownerId: $apartment->owner_id,
                isOwner: true,
                role: ApartmentUserRoleEnum::ADMIN->value,
            );
        }

        foreach ($memberships as $membership) {
            $apartment = $membership->apartment;
            if ($apartment === null) {
                continue;
            }

            if (isset($relatedById[$apartment->id])) {
                continue;
            }

            $relatedById[$apartment->id] = new RelatedApartmentDto(
                id: $apartment->id,
                name: $apartment->name,
                description: $apartment->description,
                isDefault: (bool) $apartment->is_default,
                ownerId: $apartment->owner_id,
                isOwner: false,
                role: $membership->role,
            );
        }

        return collect(array_values($relatedById));
    }
}
