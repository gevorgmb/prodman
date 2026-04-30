<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\AcquisitionItem;
use Illuminate\Database\Eloquent\Collection;

interface AcquisitionItemRepositoryInterface extends EloquentRepositoryInterface
{
    public function getAllByAcquisitionId(int $acquisitionId): Collection;

    public function findByIdAndAcquisitionId(int $id, int $acquisitionId): ?AcquisitionItem;

    public function create(array $data): AcquisitionItem;

    public function update(AcquisitionItem $item, array $data): AcquisitionItem;

    public function delete(AcquisitionItem $item): void;
}
