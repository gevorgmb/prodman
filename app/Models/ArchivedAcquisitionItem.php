<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

#[Fillable([
    'apartment_id',
    'item_id',
    'product_name',
    'quantity',
    'quantity_available',
    'expiration_date',
    'archive_date',
    'reason',
])]
/**
 * @property int $id
 * @property int $apartment_id
 * @property int $item_id
 * @property string $product_name
 * @property float $quantity
 * @property float $quantity_available
 * @property Carbon $expiration_date
 * @property Carbon $archive_date
 * @property string $reason
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ArchivedAcquisitionItem extends Model
{
    protected function casts(): array
    {
        return [
            'quantity' => 'float',
            'quantity_available' => 'float',
            'expiration_date' => 'datetime',
            'archive_date' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function fill(array $attributes): self
    {
        if (isset($attributes['apartmentId'])) {
            $attributes['apartment_id'] = (int) $attributes['apartmentId'];
        }
        if (isset($attributes['itemId'])) {
            $attributes['item_id'] = (int) $attributes['itemId'];
        }
        if (isset($attributes['productName'])) {
            $attributes['product_name'] = $attributes['productName'];
        }
        if (isset($attributes['quantityAvailable'])) {
            $attributes['quantity_available'] = (float) $attributes['quantityAvailable'];
        }
        if (isset($attributes['expirationDate'])) {
            $attributes['expiration_date'] = $attributes['expirationDate'];
        }
        if (isset($attributes['archiveDate'])) {
            $attributes['archive_date'] = $attributes['archiveDate'];
        }

        return parent::fill($attributes);
    }

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class, 'apartment_id');
    }

    public function acquisitionItem(): BelongsTo
    {
        return $this->belongsTo(AcquisitionItem::class, 'item_id');
    }
}
