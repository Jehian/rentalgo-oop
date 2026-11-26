<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use Carbon\CarbonInterface;
use Database\Factories\RentalFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

#[Fillable([
    'rental_code',
    'customer_id',
    'vehicle_id',
    'user_id',
    'start_date',
    'end_date',
    'duration_days',
    'daily_rate',
    'total_amount',
    'late_fee',
    'status',
    'returned_at',
    'notes',
])]
class Rental extends Model
{
    /** @use HasFactory<RentalFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'returned_at' => 'datetime',
            'duration_days' => 'integer',
            'daily_rate' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'late_fee' => 'decimal:2',
            'status' => RentalStatus::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('status', RentalStatus::Active->value);
    }

    public function scopeOverlapping(Builder $query, CarbonInterface|string $startDate, CarbonInterface|string $endDate): void
    {
        $start = Carbon::parse($startDate)->toDateString();
        $end = Carbon::parse($endDate)->toDateString();

        $query->whereDate('start_date', '<=', $end)
            ->whereDate('end_date', '>=', $start);
    }

    public function totalBill(): float
    {
        return (float) $this->total_amount + (float) $this->late_fee;
    }

    public function paidAmount(): float
    {
        return (float) $this->payments()
            ->where('status', PaymentStatus::Paid->value)
            ->sum('amount');
    }
}
