<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

#[Fillable(['apartment_id', 'name'])]
/**
 * @property int $id
 * @property int $apartment_id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Department extends Model
{
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class, 'apartment_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'department_id');
    }
}
