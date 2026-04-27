<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dto\Department\DepartmentDto;
use Illuminate\Support\Collection;

interface DepartmentServiceInterface
{
    public function getAllByApartmentId(int $apartmentId): Collection;

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?DepartmentDto;

    public function create(array $data, int $apartmentId): DepartmentDto;

    public function update(int $id, array $data): DepartmentDto;

    public function delete(int $id, int $apartmentId): void;
}
