<?php

declare(strict_types=1);

namespace App\Dto\StockProduct;

use App\Dto\Product\ProductDto;
use App\Models\StockProduct;
use Illuminate\Support\Carbon;

readonly class StockProductDto
{
    public function __construct(
        public int         $id,
        public int         $apartmentId,
        public int         $itemId,
        public ?int        $productId,
        public string      $productName,
        public float       $quantity,
        public float       $quantityUsed,
        public float       $min,
        public string      $unit,
        public ?ProductDto $product,
        public ?Carbon     $expirationDate,
        public ?Carbon     $createdAt,
        public ?Carbon     $updatedAt,
    ) {
    }

    public static function fromModel(StockProduct $stockProduct): self
    {
        return new self(
            id: $stockProduct->id,
            apartmentId: $stockProduct->apartment_id,
            itemId: $stockProduct->item_id,
            productId: $stockProduct->product_id,
            productName: $stockProduct->product_name,
            quantity: $stockProduct->quantity,
            quantityUsed: $stockProduct->quantity_used,
            min: $stockProduct->min,
            unit: $stockProduct->unit,
            product: $stockProduct->product ? ProductDto::fromModel($stockProduct->product) : null,
            expirationDate: $stockProduct->expiration_date,
            createdAt: $stockProduct->created_at,
            updatedAt: $stockProduct->updated_at,
        );
    }
}
