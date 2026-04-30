<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

#[Fillable(['apartment_id', 'item_id', 'product_name', 'quantity', 'quantity_used', 'expiration_date', 'archive_date'])]
/**
 * @property int $id
 * @property int $apartment_id
 * @property int $item_id
 * @property string $product_name
 * @property float $quantity
 * @property float $quantity_used
 * @property Carbon $expiration_date
 * @property Carbon $archive_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class ArchivedAcquisitionItem extends Model
{
    protected function casts(): array
    {
        return [
            'quantity' => 'float',
            'quantity_used' => 'float',
            'expiration_date' => 'datetime',
            'archive_date' => 'datetime',
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
}
