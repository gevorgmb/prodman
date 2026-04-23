<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dto\Apartment\ApartmentMembershipDto;
use App\Dto\Apartment\ApartmentUserLookupDto;
use App\Models\Apartment;
use App\Models\User;
use Illuminate\Support\Collection;

interface ApartmentUserServiceInterface
{
    public function findByEmail(Apartment $apartment, string $email): ApartmentUserLookupDto;

    public function findByPhone(Apartment $apartment, string $phone): ApartmentUserLookupDto;

    public function addUserToApartment(Apartment $apartment, int $targetUserId, ?string $role): ApartmentMembershipDto;

    /**
     * @return Collection<int, array{membershipId:int, role:string, user:array<string, mixed>}>
     */
    public function getUsers(Apartment $apartment): Collection;

    public function removeUserFromApartment(Apartment $apartment, int $userId): void;

    public function disconnectFromApartment(User $authUser, int $apartmentId): void;
}
