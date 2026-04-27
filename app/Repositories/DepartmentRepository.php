<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Department;
use App\Repositories\Contracts\DepartmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DepartmentRepository extends BaseRepository implements DepartmentRepositoryInterface
{
    public function __construct(
        private readonly Department $departmentModel,
    ) {
        parent::__construct($departmentModel);
    }

    public function getAllByApartmentId(int $apartmentId): Collection
    {
        return $this->departmentModel->newQuery()
            ->where('apartment_id', $apartmentId)
            ->orderBy('name')
            ->get();
    }

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?Department
    {
        /** @var Department|null $department */
        $department = $this->departmentModel->newQuery()
            ->where('id', $id)
            ->where('apartment_id', $apartmentId)
            ->first();

        return $department;
    }

    public function findByNameAndApartmentId(string $name, int $apartmentId): ?Department
    {
        /** @var Department|null $department */
        $department = $this->departmentModel->newQuery()
            ->where('name', $name)
            ->where('apartment_id', $apartmentId)
            ->first();

        return $department;
    }

    public function create(array $data): Department
    {
        /** @var Department $department */
        $department = $this->departmentModel->newQuery()->create($data);

        return $department;
    }

    public function update(Department $department, array $data): Department
    {
        $department->fill($data);
        $department->save();

        return $department;
    }

    public function delete(Department $department): void
    {
        $department->delete();
    }
}
