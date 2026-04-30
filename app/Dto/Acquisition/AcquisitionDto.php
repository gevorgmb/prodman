<?php

declare(strict_types=1);

namespace App\Dto\Acquisition;

use App\Enums\AcquisitionStatusEnum;
use App\Models\Acquisition;
use Illuminate\Support\Carbon;

readonly class AcquisitionDto
{
    public function __construct(
        public ?int $id,
        public ?int $apartmentId,
        public ?string $storeName,
        public ?string $description,
        public ?AcquisitionStatusEnum $status,
        public ?int $userId,
        public ?Carbon $createdAt,
        public ?Carbon $updatedAt,
    ) {
    }

    public static function fromModel(Acquisition $acquisition): self
    {
        return new self(
            id: $acquisition->id,
            apartmentId: $acquisition->apartment_id,
            storeName: $acquisition->store_name,
            description: $acquisition->description,
            status: AcquisitionStatusEnum::fromString($acquisition->status),
            userId: $acquisition->user_id,
            createdAt: $acquisition->created_at,
            updatedAt: $acquisition->updated_at,
        );
    }

    public static function fromRequest(array $acquisition): self
    {
        return new self(
            id: $acquisition['id'] ?? null,
            apartmentId: $acquisition['apartmentId'] ?? null,
            storeName: $acquisition['storeName'] ?? null,
            description: $acquisition['description'] ?? null,
            status: AcquisitionStatusEnum::fromString($acquisition['status'] ?? null),
            userId: $acquisition['userId'] ?? null,
            createdAt: $acquisition['createdAt'] ?? now(),
            updatedAt: $acquisition['updatedAt'] ?? now(),
        );
    }

    public function toDBData(): array
    {
        return [
            'apartment_id' => $this->apartmentId,
            'store_name' => $this->storeName,
            'description' => $this->description,
            'status' => $this->status->value,
            'user_id' => $this->userId,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
