<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

#[Fillable(['apartment_id', 'name', 'importance', 'category_id', 'department_id', 'description', 'min', 'unit', 'merge_stock'])]
/**
 * @property int $id
 * @property int $apartment_id
 * @property string $name
 * @property int $importance
 * @property int|null $category_id
 * @property int|null $department_id
 * @property string|null $description
 * @property float $min
 * @property string $unit
 * @property bool $merge_stock
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Collection<int, StockProduct>|null $stockProducts
 * @property Apartment|null $apartment
 * @property Category|null $category
 * @property Department|null $department
 */
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'importance' => 'integer',
            'min' => 'float',
            'merge_stock' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function fill(array $attributes): void
    {
        if (isset($attributes['mergeStock'])) {
            $attributes['merge_stock'] = (bool) $attributes['mergeStock'];
        }
        if (isset($attributes['categoryId'])) {
            $attributes['category_id'] = (int) $attributes['categoryId'];
        }
        if (isset($attributes['departmentId'])) {
            $attributes['department_id'] = (int) $attributes['departmentId'];
        }
        parent::fill($attributes);
    }

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class, 'apartment_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function stockProducts(): HasMany
    {
        return $this->hasMany(StockProduct::class, 'product_id');
    }
}
