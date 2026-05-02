<?php

declare(strict_types=1);

namespace App\Services\Contracts;

use App\Dto\AcquisitionItem\AcquisitionItemDto;
use Illuminate\Support\Collection;

interface AcquisitionItemServiceInterface
{
    /**
     * @return Collection<int, AcquisitionItemDto>
     */
    public function getAllByAcquisitionId(int $acquisitionId): Collection;

    public function findByIdAndAcquisitionId(int $id, int $acquisitionId): ?AcquisitionItemDto;

    public function create(array $data, int $acquisitionId): AcquisitionItemDto;

    public function bulkCreate(array $data, int $acquisitionId): void;

    public function bulkUpdate(array $data, int $acquisitionId): void;

    public function update(int $id, int $acquisitionId, array $data): AcquisitionItemDto;

    public function delete(int $id, int $acquisitionId): void;
}
