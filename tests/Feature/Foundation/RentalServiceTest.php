<?php

namespace Tests\Feature\Foundation;

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use App\Exceptions\VehicleNotAvailableException;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\RentalService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class RentalServiceTest extends TestCase
{
    use RefreshDatabase;

    private RentalService $rentalService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rentalService = app(RentalService::class);
    }

    public function test_creates_rental_with_inclusive_duration_and_car_insurance(): void
    {
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'type' => VehicleType::Car,
            'daily_rate' => 300000,
            'status' => VehicleStatus::Available,
        ]);
        $user = User::factory()->create();

        $rental = $this->rentalService->createRental(
            $customer,
            $vehicle,
            '2026-12-01',
            '2026-12-03',
            $user,
        );

        $this->assertSame(3, $rental->duration_days);
        $this->assertSame('990000.00', $rental->total_amount);
        $this->assertSame(RentalStatus::Active, $rental->status);
        $this->assertSame(VehicleStatus::Rented, $vehicle->fresh()->status);
        $this->assertStringStartsWith('RNT-', $rental->rental_code);
    }

    public function test_rejects_overlapping_active_rental_for_same_vehicle(): void
    {
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->motorcycle()->create([
            'daily_rate' => 100000,
            'status' => VehicleStatus::Available,
        ]);
        $user = User::factory()->create();

        $this->rentalService->createRental($customer, $vehicle, '2026-12-01', '2026-12-03', $user);
        $vehicle->update(['status' => VehicleStatus::Available]);

        $this->expectException(VehicleNotAvailableException::class);

        $this->rentalService->createRental($customer, $vehicle, '2026-12-03', '2026-12-05', $user);
    }

    public function test_completes_rental_with_late_fee_and_releases_vehicle(): void
    {
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'type' => VehicleType::Car,
            'daily_rate' => 300000,
            'status' => VehicleStatus::Available,
        ]);
        $user = User::factory()->create();

        $rental = $this->rentalService->createRental($customer, $vehicle, '2026-12-01', '2026-12-03', $user);
        $completed = $this->rentalService->completeRental($rental, '2026-12-05');

        $this->assertSame(RentalStatus::Completed, $completed->status);
        $this->assertSame('300000.00', $completed->late_fee);
        $this->assertSame(VehicleStatus::Available, $vehicle->fresh()->status);
        $this->assertNotNull($completed->returned_at);
    }

    public function test_cancels_active_rental_and_cancels_pending_payments(): void
    {
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'type' => VehicleType::Car,
            'daily_rate' => 300000,
            'status' => VehicleStatus::Available,
        ]);
        $user = User::factory()->create();

        $rental = $this->rentalService->createRental($customer, $vehicle, '2026-12-01', '2026-12-03', $user);
        $payment = Payment::factory()->create([
            'rental_id' => $rental->id,
            'status' => PaymentStatus::Pending,
        ]);

        $cancelled = $this->rentalService->cancelRental($rental);

        $this->assertSame(RentalStatus::Cancelled, $cancelled->status);
        $this->assertSame(VehicleStatus::Available, $vehicle->fresh()->status);
        $this->assertSame(PaymentStatus::Cancelled, $payment->fresh()->status);
    }

    public function test_rejects_end_date_before_start_date(): void
    {
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create();
        $user = User::factory()->create();

        $this->expectException(InvalidArgumentException::class);

        $this->rentalService->createRental($customer, $vehicle, '2026-12-05', '2026-12-01', $user);
    }

    public function test_applies_motorcycle_long_rental_discount(): void
    {
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->motorcycle()->create([
            'daily_rate' => 100000,
            'status' => VehicleStatus::Available,
        ]);
        $user = User::factory()->create();

        $rental = $this->rentalService->createRental($customer, $vehicle, '2026-12-01', '2026-12-07', $user);

        $this->assertSame(7, $rental->duration_days);
        $this->assertSame('630000.00', $rental->total_amount);
    }

    public function test_updates_active_rental_and_recalculates_total(): void
    {
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'type' => VehicleType::Car,
            'daily_rate' => 300000,
            'status' => VehicleStatus::Available,
        ]);
        $user = User::factory()->create();

        $rental = $this->rentalService->createRental($customer, $vehicle, '2026-12-01', '2026-12-03', $user);
        $updated = $this->rentalService->updateRental($rental, [
            'start_date' => '2026-12-01',
            'end_date' => '2026-12-04',
        ]);

        $this->assertSame(4, $updated->duration_days);
        $this->assertSame('1320000.00', $updated->total_amount);
    }

    public function test_rental_relationships_and_paid_amount(): void
    {
        $rental = Rental::factory()->create();
        Payment::factory()->paid()->create([
            'rental_id' => $rental->id,
            'amount' => 250000,
        ]);

        $this->assertTrue($rental->customer()->exists());
        $this->assertTrue($rental->vehicle()->exists());
        $this->assertTrue($rental->user()->exists());
        $this->assertSame(250000.0, $rental->paidAmount());
    }
}
