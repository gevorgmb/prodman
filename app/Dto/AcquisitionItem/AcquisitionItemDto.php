<?php

declare(strict_types=1);

namespace App\Dto\AcquisitionItem;

use App\Models\AcquisitionItem;
use Illuminate\Support\Carbon;

readonly class AcquisitionItemDto
{
    public function __construct(
        public int $id,
        public int $acquisitionId,
        public ?int $productId,
        public string $productName,
        public ?string $description,
        public ?Carbon $expirationDate,
        public float $quantity,
        public float $price,
        public float $total,
        public ?Carbon $createdAt,
        public ?Carbon $updatedAt,
    ) {
    }

    public static function fromModel(AcquisitionItem $item): self
    {
        return new self(
            id: $item->id,
            acquisitionId: $item->acquisition_id,
            productId: $item->product_id,
            productName: $item->product_name,
            description: $item->description,
            expirationDate: $item->expiration_date,
            quantity: $item->quantity,
            price: $item->price,
            total: $item->total,
            createdAt: $item->created_at,
            updatedAt: $item->updated_at,
        );
    }
}
