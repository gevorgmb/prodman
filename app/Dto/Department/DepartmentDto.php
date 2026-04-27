<?php

declare(strict_types=1);

namespace App\Dto\Department;

use App\Models\Department;
use Illuminate\Support\Carbon;

readonly class DepartmentDto
{
    public function __construct(
        public int $id,
        public int $apartmentId,
        public string $name,
        public ?Carbon $createdAt,
        public ?Carbon $updatedAt,
    ) {
    }

    public static function fromModel(Department $department): self
    {
        return new self(
            id: $department->id,
            apartmentId: $department->apartment_id,
            name: $department->name,
            createdAt: $department->created_at,
            updatedAt: $department->updated_at,
        );
    }
}
