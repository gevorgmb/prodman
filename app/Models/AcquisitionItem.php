<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

#[Fillable(['acquisition_id', 'product_id', 'product_name', 'description', 'expiration_date', 'quantity', 'price', 'total', 'unit'])]
/**
 * @property int $id
 * @property int $acquisition_id
 * @property int|null $product_id
 * @property string $product_name
 * @property string|null $description
 * @property Carbon|null $expiration_date
 * @property float $quantity
 * @property float $price
 * @property float $total
 * @property string $unit
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class AcquisitionItem extends Model
{
    protected function casts(): array
    {
        return [
            'quantity' => 'float',
            'price' => 'float',
            'total' => 'float',
            'expiration_date' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function fill(array $attributes): self
    {
        if (isset($attributes['acquisitionId'])) {
            $attributes['acquisition_id'] = (int) $attributes['acquisitionId'];
        }
        if (isset($attributes['productId'])) {
            $attributes['product_id'] = $attributes['productId'] ? (int) $attributes['productId'] : null;
        }
        if (isset($attributes['productName'])) {
            $attributes['product_name'] = $attributes['productName'];
        }
        if (isset($attributes['expirationDate'])) {
            $attributes['expiration_date'] = $attributes['expirationDate'];
        }

        return parent::fill($attributes);
    }

    public function acquisition(): BelongsTo
    {
        return $this->belongsTo(Acquisition::class, 'acquisition_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
