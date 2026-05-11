<?php

declare(strict_types=1);

namespace App\Models;

use App\Dto\Product\ProductDto;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

#[Fillable(['apartment_id', 'item_id', 'product_id', 'product_name', 'quantity_available', 'expiration_date', 'min', 'unit'])]
/**
 * @property int $id
 * @property int $apartment_id
 * @property int $item_id
 * @property int $product_id
 * @property string $product_name
 * @property float $quantity_available
 * @property Carbon $expiration_date
 * @property ?Product $product
 * @property float $min
 * @property string $unit
 * @property ?AcquisitionItem $acquisitionItem
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class StockProduct extends Model
{
    protected function casts(): array
    {
        return [
            'quantity_available' => 'float',
            'expiration_date' => 'datetime',
            'min' => 'float',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class, 'apartment_id');
    }

    public function acquisitionItem(): BelongsTo
    {
        return $this->belongsTo(AcquisitionItem::class, 'item_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
