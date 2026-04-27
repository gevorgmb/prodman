<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

#[Fillable(['owner_id', 'name', 'description', 'is_default'])]
/**
 * @property int $id
 * @property int $owner_id
 * @property string $name
 * @property string $description
 * @property boolean $is_default
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
class Apartment extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class, 'apartment_id');
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class, 'apartment_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'apartment_id');
    }
}
