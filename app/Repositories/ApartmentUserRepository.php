<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\ApartmentUserRoleEnum;
use App\Models\ApartmentUser;
use App\Repositories\Contracts\ApartmentUserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ApartmentUserRepository extends BaseRepository implements ApartmentUserRepositoryInterface
{
    public function __construct(
        private readonly ApartmentUser $apartmentUserModel,
    ) {
        parent::__construct($apartmentUserModel);
    }

    public function findByApartmentIdAndUserId(int $apartmentId, int $userId): ?ApartmentUser
    {
        /** @var ApartmentUser|null $membership */
        $membership = $this->apartmentUserModel->newQuery()
            ->where('apartment_id', $apartmentId)
            ->where('user_id', $userId)
            ->first();

        return $membership;
    }

    public function createMembership(int $apartmentId, int $userId, ?ApartmentUserRoleEnum $role): ApartmentUser
    {
        /** @var ApartmentUser $membership */
        $membership = $this->apartmentUserModel->newQuery()->create([
            'apartment_id' => $apartmentId,
            'user_id' => $userId,
            'role' => $role ? $role->value : ApartmentUserRoleEnum::MEMBER->value,
        ]);

        return $membership;
    }

    public function getUsersByApartmentId(int $apartmentId): Collection
    {
        return $this->apartmentUserModel->newQuery()
            ->with('user')
            ->where('apartment_id', $apartmentId)
            ->orderByDesc('id')
            ->get();
    }

    public function getMembershipsByUserId(int $userId): Collection
    {
        return $this->apartmentUserModel->newQuery()
            ->with('apartment')
            ->where('user_id', $userId)
            ->orderByDesc('id')
            ->get();
    }
}
