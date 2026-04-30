<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\Acquisition\AcquisitionDto;
use App\Models\Acquisition;
use App\Repositories\Contracts\AcquisitionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AcquisitionRepository extends BaseRepository implements AcquisitionRepositoryInterface
{
    public function __construct(
        private readonly Acquisition $acquisitionModel,
    ) {
        parent::__construct($acquisitionModel);
    }

    public function getAllByApartmentId(int $apartmentId): Collection
    {
        return $this->acquisitionModel->newQuery()
            ->where('apartment_id', $apartmentId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?Acquisition
    {
        /** @var Acquisition|null $acquisition */
        $acquisition = $this->acquisitionModel->newQuery()
            ->where('id', $id)
            ->where('apartment_id', $apartmentId)
            ->first();

        return $acquisition;
    }

    public function create(AcquisitionDto $data): Acquisition
    {
        /** @var Acquisition $acquisition */
        $acquisition = $this->acquisitionModel->newQuery()->create($data->toDBData());

        return $acquisition;
    }

    public function update(Acquisition $acquisition, array $data): Acquisition
    {
        $data['updated_at'] = now();
        $acquisition->fill($data);
        $acquisition->save();

        return $acquisition;
    }

    public function delete(Acquisition $acquisition): void
    {
        $acquisition->delete();
    }
}
