<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly User $userModel,
    ) {
        parent::__construct($userModel);
    }

    public function findByEmail(string $email): ?User
    {
        /** @var User|null $user */
        $user = $this->userModel->newQuery()
            ->where('email', $email)
            ->first();

        return $user;
    }

    public function findByPhone(string $phone): ?User
    {
        /** @var User|null $user */
        $user = $this->userModel->newQuery()
            ->where('phone', $phone)
            ->first();

        return $user;
    }

    public function getUserById(int $id): ?User
    {
        /** @var User|null $user */
        $user = $this->userModel->newQuery()
            ->with('phoneVerificationLocked')
            ->with('phoneVerification')
            ->with('emailVerification')
            ->with('emailVerificationLocked')
            ->find($id);

        return $user;
    }
}
