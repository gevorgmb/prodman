<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dto\Acquisition\AcquisitionDto;
use Illuminate\Support\Collection;

interface AcquisitionServiceInterface
{
    /**
     * @return Collection<int, AcquisitionDto>
     */
    public function getAllByApartmentId(int $apartmentId): Collection;

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?AcquisitionDto;

    public function create(array $data, int $apartmentId, int $userId): AcquisitionDto;

    public function update(int $id, int $apartmentId, array $data): AcquisitionDto;

    public function delete(int $id, int $apartmentId): void;
}
