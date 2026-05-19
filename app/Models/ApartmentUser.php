<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $apartment_id
 * @property int $user_id
 * @property string $role
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 */
#[Fillable(['apartment_id', 'user_id', 'role', 'updated_at'])]
class ApartmentUser extends Model
{
    use SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function fill(array $attributes): self
    {
        if (isset($attributes['apartmentId'])) {
            $attributes['apartment_id'] = (int) $attributes['apartmentId'];
        }
        if (isset($attributes['userId'])) {
            $attributes['user_id'] = (int) $attributes['userId'];
        }

        return parent::fill($attributes);
    }

    public function apartment(): BelongsTo
    {
        return $this->belongsTo(Apartment::class, 'apartment_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
