<?php

namespace App\Services;

use App\Domain\Vehicles\Vehicle;
use App\Domain\Vehicles\VehicleFactory;
use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Enums\VehicleStatus;
use App\Exceptions\VehicleNotAvailableException;
use App\Models\Customer;
use App\Models\Rental;
use App\Models\User;
use App\Models\Vehicle as VehicleModel;
use App\Support\DocumentCodeGenerator;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class RentalService
{
    public function createRental(
        Customer $customer,
        VehicleModel $vehicle,
        CarbonInterface|string $startDate,
        CarbonInterface|string $endDate,
        User $user,
    ): Rental {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();

        $this->ensureValidDateRange($start, $end);
        $this->ensureVehicleAvailable($vehicle, $start, $end);

        $durationDays = $this->durationInDays($start, $end);
        $domainVehicle = VehicleFactory::fromModel($vehicle);
        $dailyRate = (float) $vehicle->daily_rate;
        $totalAmount = $domainVehicle->calculateRentalCost($durationDays);

        return DB::transaction(function () use (
            $customer,
            $vehicle,
            $user,
            $start,
            $end,
            $durationDays,
            $dailyRate,
            $totalAmount,
        ): Rental {
            $rental = Rental::create([
                'rental_code' => DocumentCodeGenerator::rentalCode(),
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'user_id' => $user->id,
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'duration_days' => $durationDays,
                'daily_rate' => $dailyRate,
                'total_amount' => $totalAmount,
                'late_fee' => 0,
                'status' => RentalStatus::Active,
            ]);

            $vehicle->update(['status' => VehicleStatus::Rented]);

            return $rental;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateRental(Rental $rental, array $data): Rental
    {
        $this->ensureRentalActive($rental);

        $start = Carbon::parse($data['start_date'] ?? $rental->start_date)->startOfDay();
        $end = Carbon::parse($data['end_date'] ?? $rental->end_date)->startOfDay();

        $this->ensureValidDateRange($start, $end);
        $this->ensureNoOverlap($rental->vehicle_id, $start, $end, $rental->id);

        $durationDays = $this->durationInDays($start, $end);
        $totalAmount = $this->domainVehicleFromRental($rental)->calculateRentalCost($durationDays);

        $rental->update([
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'duration_days' => $durationDays,
            'total_amount' => $totalAmount,
            'notes' => $data['notes'] ?? $rental->notes,
        ]);

        return $rental->refresh();
    }

    public function completeRental(Rental $rental, CarbonInterface|string $returnedAt): Rental
    {
        $this->ensureRentalActive($rental);

        $returned = Carbon::parse($returnedAt);
        $lateFee = $this->calculateLateFee($rental, $returned);

        return DB::transaction(function () use ($rental, $returned, $lateFee): Rental {
            $rental->update([
                'status' => RentalStatus::Completed,
                'returned_at' => $returned,
                'late_fee' => $lateFee,
            ]);

            $rental->vehicle->update(['status' => VehicleStatus::Available]);

            return $rental->refresh();
        });
    }

    public function cancelRental(Rental $rental): Rental
    {
        $this->ensureRentalActive($rental);

        return DB::transaction(function () use ($rental): Rental {
            $rental->update(['status' => RentalStatus::Cancelled]);
            $rental->vehicle->update(['status' => VehicleStatus::Available]);

            $rental->payments()
                ->where('status', PaymentStatus::Pending->value)
                ->update(['status' => PaymentStatus::Cancelled->value]);

            return $rental->refresh();
        });
    }

    public function calculateLateFee(Rental $rental, CarbonInterface|string $returnedAt): float
    {
        $endDate = Carbon::parse($rental->end_date)->startOfDay();
        $returned = Carbon::parse($returnedAt)->startOfDay();
        $lateDays = $returned->greaterThan($endDate)
            ? (int) $endDate->diffInDays($returned)
            : 0;

        return round(
            (float) $rental->daily_rate * (float) config('rental.late_fee_rate_per_day', 0.50) * $lateDays,
            2,
        );
    }

    private function ensureValidDateRange(CarbonInterface $start, CarbonInterface $end): void
    {
        if ($end->lessThan($start)) {
            throw new InvalidArgumentException('Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
        }
    }

    private function ensureVehicleAvailable(VehicleModel $vehicle, CarbonInterface $start, CarbonInterface $end): void
    {
        if ($vehicle->status !== VehicleStatus::Available) {
            throw VehicleNotAvailableException::forVehicle($vehicle->plate_number);
        }

        $this->ensureNoOverlap($vehicle->id, $start, $end);
    }

    private function ensureNoOverlap(int $vehicleId, CarbonInterface $start, CarbonInterface $end, ?int $exceptRentalId = null): void
    {
        $query = Rental::query()
            ->where('vehicle_id', $vehicleId)
            ->active()
            ->overlapping($start, $end);

        if ($exceptRentalId !== null) {
            $query->whereKeyNot($exceptRentalId);
        }

        if ($query->exists()) {
            $vehicle = VehicleModel::find($vehicleId);
            throw VehicleNotAvailableException::forVehicle($vehicle?->plate_number ?? "ID {$vehicleId}");
        }
    }

    private function durationInDays(CarbonInterface $start, CarbonInterface $end): int
    {
        return (int) $start->diffInDays($end) + 1;
    }

    private function ensureRentalActive(Rental $rental): void
    {
        if ($rental->status !== RentalStatus::Active) {
            throw new InvalidArgumentException('Hanya rental aktif yang dapat diubah.');
        }
    }

    private function domainVehicleFromRental(Rental $rental): Vehicle
    {
        $vehicle = $rental->vehicle;

        return VehicleFactory::make($vehicle->type, [
            'plate_number' => $vehicle->plate_number,
            'brand' => $vehicle->brand,
            'model' => $vehicle->model,
            'daily_rate' => (float) $rental->daily_rate,
            'status' => $vehicle->status,
            'seat_count' => $vehicle->seat_count,
            'transmission' => $vehicle->transmission,
            'engine_cc' => $vehicle->engine_cc,
        ]);
    }
}
