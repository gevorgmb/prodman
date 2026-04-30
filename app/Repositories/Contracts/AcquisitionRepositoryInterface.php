<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Dto\Acquisition\AcquisitionDto;
use App\Models\Acquisition;
use Illuminate\Database\Eloquent\Collection;

interface AcquisitionRepositoryInterface extends EloquentRepositoryInterface
{
    public function getAllByApartmentId(int $apartmentId): Collection;

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?Acquisition;

    public function create(AcquisitionDto $data): Acquisition;

    public function update(Acquisition $acquisition, array $data): Acquisition;

    public function delete(Acquisition $acquisition): void;
}
