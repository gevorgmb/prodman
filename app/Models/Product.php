<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

#[Fillable(['apartment_id', 'name', 'importance', 'category_id', 'department_id', 'description'])]
/**
 * @property int $id
 * @property int $apartment_id
 * @property string $name
 * @property int $importance
 * @property int|null $category_id
 * @property int|null $department_id
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'importance' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
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
}
