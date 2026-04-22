<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface extends EloquentRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function findByPhone(string $phone): ?User;

    public function getUserById(int $id): ?User;
}
