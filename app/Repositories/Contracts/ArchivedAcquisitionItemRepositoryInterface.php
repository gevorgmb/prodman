<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\ArchivedAcquisitionItem;
use Illuminate\Database\Eloquent\Collection;

interface ArchivedAcquisitionItemRepositoryInterface extends EloquentRepositoryInterface
{
    public function getAllByApartmentId(int $apartmentId): Collection;

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?ArchivedAcquisitionItem;

    public function create(array $data): ArchivedAcquisitionItem;

    public function update(ArchivedAcquisitionItem $item, array $data): ArchivedAcquisitionItem;

    public function delete(ArchivedAcquisitionItem $item): void;
}
