<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Department\DepartmentDto;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use App\Services\Contracts\DepartmentServiceInterface;
use Illuminate\Support\Collection;
use RuntimeException;

readonly class DepartmentService implements DepartmentServiceInterface
{
    public function __construct(
        private DepartmentRepositoryInterface $departmentRepository,
    ) {
    }

    public function getAllByApartmentId(int $apartmentId): Collection
    {
        return $this->departmentRepository
            ->getAllByApartmentId($apartmentId)
            ->map(fn($department) => DepartmentDto::fromModel($department))
            ->values();
    }

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?DepartmentDto
    {
        $department = $this->departmentRepository->findByIdAndApartmentId($id, $apartmentId);

        return $department === null ? null : DepartmentDto::fromModel($department);
    }

    public function create(array $data, int $apartmentId): DepartmentDto
    {
        $data['apartment_id'] = $apartmentId;

        return DepartmentDto::fromModel($this->departmentRepository->create($data));
    }

    public function update(int $id, array $data): DepartmentDto
    {
        $department = $this->departmentRepository->find($id);
        if ($department === null) {
            throw new RuntimeException('Department not found.');
        }

        return DepartmentDto::fromModel($this->departmentRepository->update($department, $data));
    }

    public function delete(int $id, int $apartmentId): void
    {
        $department = $this->departmentRepository->findByIdAndApartmentId($id, $apartmentId);
        if ($department === null) {
            throw new RuntimeException('Department not found.');
        }

        $this->departmentRepository->delete($department);
    }
}
