<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dto\ArchivedAcquisitionItem\ArchivedAcquisitionItemDto;
use Illuminate\Support\Collection;

interface ArchivedAcquisitionItemServiceInterface
{
    /**
     * @return Collection<int, ArchivedAcquisitionItemDto>
     */
    public function getAllByApartmentId(int $apartmentId): Collection;

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?ArchivedAcquisitionItemDto;

    public function create(array $data, int $apartmentId): ArchivedAcquisitionItemDto;

    public function update(int $id, int $apartmentId, array $data): ArchivedAcquisitionItemDto;

    public function delete(int $id, int $apartmentId): void;
}
