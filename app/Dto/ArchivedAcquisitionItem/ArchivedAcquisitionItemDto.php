<?php

declare(strict_types=1);

namespace App\Dto\ArchivedAcquisitionItem;

use App\Models\ArchivedAcquisitionItem;
use Illuminate\Support\Carbon;

readonly class ArchivedAcquisitionItemDto
{
    public function __construct(
        public int $id,
        public int $apartmentId,
        public int $itemId,
        public string $productName,
        public float $quantity,
        public float $quantityUsed,
        public Carbon $expirationDate,
        public Carbon $archiveDate,
        public ?Carbon $createdAt,
        public ?Carbon $updatedAt,
    ) {
    }

    public static function fromModel(ArchivedAcquisitionItem $item): self
    {
        return new self(
            id: $item->id,
            apartmentId: $item->apartment_id,
            itemId: $item->item_id,
            productName: $item->product_name,
            quantity: $item->quantity,
            quantityUsed: $item->quantity_used,
            expirationDate: $item->expiration_date,
            archiveDate: $item->archive_date,
            createdAt: $item->created_at,
            updatedAt: $item->updated_at,
        );
    }
}
