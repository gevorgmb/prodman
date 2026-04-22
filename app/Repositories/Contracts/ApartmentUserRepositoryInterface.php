<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Enums\ApartmentUserRoleEnum;
use App\Models\ApartmentUser;
use Illuminate\Database\Eloquent\Collection;

interface ApartmentUserRepositoryInterface extends EloquentRepositoryInterface
{
    public function findByApartmentIdAndUserId(int $apartmentId, int $userId): ?ApartmentUser;

    public function createMembership(int $apartmentId, int $userId, ?ApartmentUserRoleEnum $role): ApartmentUser;

    public function getUsersByApartmentId(int $apartmentId): Collection;

    public function getMembershipsByUserId(int $userId): Collection;
}
