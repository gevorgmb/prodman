<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Acquisition\AcquisitionDto;
use App\Enums\AcquisitionStatusEnum;
use App\Events\AcquisitionCompleteEvent;
use App\Models\Acquisition;
use App\Repositories\Contracts\AcquisitionRepositoryInterface;
use App\Services\Contracts\AcquisitionItemServiceInterface;
use App\Services\Contracts\AcquisitionServiceInterface;
use Illuminate\Support\Collection;
use RuntimeException;

readonly class AcquisitionService implements AcquisitionServiceInterface
{
    public function __construct(
        private AcquisitionRepositoryInterface  $acquisitionRepository,
        private AcquisitionItemServiceInterface $acquisitionItemService,
    ) {
    }

    /**
     * @return Collection<int, AcquisitionDto>
     */
    public function getAllByApartmentId(int $apartmentId): Collection
    {
        return $this->acquisitionRepository->getAllByApartmentId($apartmentId)
            ->map(fn (Acquisition $acquisition) => AcquisitionDto::fromModel($acquisition));
    }

    public function findByIdAndApartmentId(int $id, int $apartmentId): ?AcquisitionDto
    {
        $acquisition = $this->acquisitionRepository->findByIdAndApartmentId($id, $apartmentId);

        return $acquisition === null ? null : AcquisitionDto::fromModel($acquisition);
    }

    public function create(array $data, int $apartmentId, int $userId): AcquisitionDto
    {
        $data['apartmentId'] = $apartmentId;
        $data['userId'] = $userId;

        $acquisition = $this->acquisitionRepository->create(
            AcquisitionDto::fromRequest($data)
        );
        if (! empty($data['items'])) {
            $this->acquisitionItemService->bulkCreate($data['items'], $acquisition->id);
        }

        return AcquisitionDto::fromModel($acquisition);
    }

    public function update(int $id, int $apartmentId, array $data): AcquisitionDto
    {
        $acquisition = $this->acquisitionRepository->findByIdAndApartmentId($id, $apartmentId);

        if ($acquisition === null) {
            throw new RuntimeException('Acquisition not found.');
        }

        $oldStatus = $acquisition->status;

        if ($oldStatus === AcquisitionStatusEnum::CANCELLED->value) {
            throw new RuntimeException('Cannot update a cancelled acquisition.');
        }

        if ($oldStatus === AcquisitionStatusEnum::COMPLETE->value) {
            if (!isset($data['status']) || $data['status'] !== AcquisitionStatusEnum::CANCELLED->value) {
                throw new RuntimeException('Completed acquisition can only be cancelled.');
            }
            // If we are here, we are changing from COMPLETE to CANCELLED, which is allowed.
        }

        $updatedAcquisition = $this->acquisitionRepository->update($acquisition, $data);

        if (!empty($data['items'])) {
            $this->acquisitionItemService->bulkUpdate($data['items'], $updatedAcquisition->id);
        }

        if (
            $oldStatus !== AcquisitionStatusEnum::COMPLETE->value
            && $updatedAcquisition->status === AcquisitionStatusEnum::COMPLETE->value
        ) {
            AcquisitionCompleteEvent::dispatch($updatedAcquisition->id);
        }

        return AcquisitionDto::fromModel($updatedAcquisition);
    }

    public function delete(int $id, int $apartmentId): void
    {
        $acquisition = $this->acquisitionRepository->findByIdAndApartmentId($id, $apartmentId);

        if ($acquisition !== null) {
            $this->acquisitionRepository->delete($acquisition);
        }
    }
}
