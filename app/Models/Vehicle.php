<?php

namespace App\Models;

use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use Database\Factories\VehicleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'plate_number',
    'brand',
    'model',
    'type',
    'year',
    'daily_rate',
    'status',
    'seat_count',
    'transmission',
    'engine_cc',
    'image_path',
    'description',
])]
class Vehicle extends Model
{
    /** @use HasFactory<VehicleFactory> */
    use HasFactory, SoftDeletes;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => VehicleType::class,
            'status' => VehicleStatus::class,
            'year' => 'integer',
            'daily_rate' => 'decimal:2',
            'seat_count' => 'integer',
            'engine_cc' => 'integer',
        ];
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function scopeAvailable(Builder $query): void
    {
        $query->where('status', VehicleStatus::Available->value);
    }

    public function scopeOfType(Builder $query, VehicleType|string $type): void
    {
        $query->where('type', $type instanceof VehicleType ? $type->value : $type);
    }

    public function isAvailable(): bool
    {
        return $this->status === VehicleStatus::Available;
    }
}
