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
        ?User $user = null,
        ?string $notes = null,
    ): Rental {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();

        $this->ensureValidDateRange($start, $end);

        return DB::transaction(function () use (
            $customer,
            $vehicle,
            $user,
            $start,
            $end,
            $notes,
        ): Rental {
            $lockedVehicle = VehicleModel::query()->lockForUpdate()->findOrFail($vehicle->id);

            $this->ensureVehicleAvailable($lockedVehicle, $start, $end);

            $durationDays = $this->durationInDays($start, $end);
            $domainVehicle = VehicleFactory::fromModel($lockedVehicle);
            $dailyRate = (float) $lockedVehicle->daily_rate;
            $totalAmount = $domainVehicle->calculateRentalCost($durationDays);

            $rental = Rental::create([
                'rental_code' => DocumentCodeGenerator::rentalCode(),
                'customer_id' => $customer->id,
                'vehicle_id' => $lockedVehicle->id,
                'user_id' => $user?->id,
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'duration_days' => $durationDays,
                'daily_rate' => $dailyRate,
                'total_amount' => $totalAmount,
                'late_fee' => 0,
                'status' => RentalStatus::Active,
                'notes' => $notes,
            ]);

            $lockedVehicle->update(['status' => VehicleStatus::Rented]);

            return $rental;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function updateRental(Rental $rental, array $data): Rental
    {
        return DB::transaction(function () use ($rental, $data): Rental {
            $freshRental = Rental::query()->lockForUpdate()->findOrFail($rental->id);
            $this->ensureRentalActive($freshRental);

            $lockedVehicle = VehicleModel::query()->lockForUpdate()->findOrFail($freshRental->vehicle_id);
            $freshRental->setRelation('vehicle', $lockedVehicle);

            $start = Carbon::parse($data['start_date'] ?? $freshRental->start_date)->startOfDay();
            $end = Carbon::parse($data['end_date'] ?? $freshRental->end_date)->startOfDay();

            $this->ensureValidDateRange($start, $end);
            $this->ensureNoOverlap($freshRental->vehicle_id, $start, $end, $freshRental->id);

            $durationDays = $this->durationInDays($start, $end);
            $totalAmount = $this->domainVehicleFromRental($freshRental)->calculateRentalCost($durationDays);

            $freshRental->update([
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'duration_days' => $durationDays,
                'total_amount' => $totalAmount,
                'notes' => array_key_exists('notes', $data) ? $data['notes'] : $freshRental->notes,
            ]);

            if (round($freshRental->totalBill(), 2) < round($freshRental->paidAmount(), 2)) {
                throw new InvalidArgumentException('Total biaya baru tidak boleh lebih kecil dari pembayaran yang sudah lunas.');
            }

            return $freshRental->refresh();
        });
    }

    public function completeRental(Rental $rental, CarbonInterface|string $returnedAt): Rental
    {
        $returned = Carbon::parse($returnedAt);

        return DB::transaction(function () use ($rental, $returned): Rental {
            $freshRental = Rental::query()->lockForUpdate()->findOrFail($rental->id);
            $this->ensureRentalActive($freshRental);

            $lateFee = $this->calculateLateFee($freshRental, $returned);

            $freshRental->update([
                'status' => RentalStatus::Completed,
                'returned_at' => $returned,
                'late_fee' => $lateFee,
            ]);

            $freshRental->vehicle->update(['status' => VehicleStatus::Available]);

            return $freshRental->refresh();
        });
    }

    public function cancelRental(Rental $rental): Rental
    {
        return DB::transaction(function () use ($rental): Rental {
            $freshRental = Rental::query()->lockForUpdate()->findOrFail($rental->id);
            $this->ensureRentalActive($freshRental);

            $freshRental->update(['status' => RentalStatus::Cancelled]);
            $freshRental->vehicle->update(['status' => VehicleStatus::Available]);

            $freshRental->payments()
                ->where('status', PaymentStatus::Pending->value)
                ->update(['status' => PaymentStatus::Cancelled->value]);

            return $freshRental->refresh();
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
