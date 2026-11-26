<?php

namespace Tests\Feature\Rentals;

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RentalCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_rental_pages(): void
    {
        $rental = Rental::factory()->create();

        $this->get(route('rentals.index'))->assertRedirect(route('login'));
        $this->get(route('rentals.create'))->assertRedirect(route('login'));
        $this->post(route('rentals.store'), [])->assertRedirect(route('login'));
        $this->get(route('rentals.show', $rental))->assertRedirect(route('login'));
        $this->get(route('rentals.edit', $rental))->assertRedirect(route('login'));
        $this->put(route('rentals.update', $rental), [])->assertRedirect(route('login'));
        $this->post(route('rentals.return', $rental), [])->assertRedirect(route('login'));
        $this->post(route('rentals.cancel', $rental))->assertRedirect(route('login'));
    }

    public function test_staff_can_search_filter_and_sort_rentals(): void
    {
        $staff = User::factory()->staff()->create();
        $budi = Customer::factory()->create(['name' => 'Budi Santoso']);
        $ani = Customer::factory()->create(['name' => 'Ani Lestari']);
        $car = Vehicle::factory()->create(['plate_number' => 'B 1111 AA']);
        $motor = Vehicle::factory()->motorcycle()->create(['plate_number' => 'B 2222 BB']);

        $activeRental = Rental::factory()->create([
            'rental_code' => 'RNT-20261201-AAAA',
            'customer_id' => $budi->id,
            'vehicle_id' => $car->id,
            'start_date' => '2026-12-01',
            'end_date' => '2026-12-03',
            'total_amount' => 990000,
            'status' => RentalStatus::Active,
        ]);

        $completedRental = Rental::factory()->completed()->create([
            'rental_code' => 'RNT-20261110-BBBB',
            'customer_id' => $ani->id,
            'vehicle_id' => $motor->id,
            'start_date' => '2026-11-10',
            'end_date' => '2026-11-12',
            'total_amount' => 300000,
        ]);

        $this->actingAs($staff)
            ->get(route('rentals.index', ['search' => 'Budi']))
            ->assertOk()
            ->assertSee($activeRental->rental_code)
            ->assertDontSee($completedRental->rental_code);

        $this->actingAs($staff)
            ->get(route('rentals.index', ['search' => 'B 2222 BB']))
            ->assertOk()
            ->assertSee($completedRental->rental_code)
            ->assertDontSee($activeRental->rental_code);

        $this->actingAs($staff)
            ->get(route('rentals.index', ['status' => RentalStatus::Completed->value]))
            ->assertOk()
            ->assertSee($completedRental->rental_code)
            ->assertDontSee($activeRental->rental_code);

        $this->actingAs($staff)
            ->get(route('rentals.index', ['date_from' => '2026-12-01', 'date_to' => '2026-12-05']))
            ->assertOk()
            ->assertSee($activeRental->rental_code)
            ->assertDontSee($completedRental->rental_code);

        $this->actingAs($staff)
            ->get(route('rentals.index', ['sort' => 'total_desc']))
            ->assertOk()
            ->assertSeeInOrder([$activeRental->rental_code, $completedRental->rental_code]);
    }

    public function test_rental_index_is_paginated(): void
    {
        $staff = User::factory()->staff()->create();
        Rental::factory()->count(12)->create();

        $this->actingAs($staff)
            ->get(route('rentals.index'))
            ->assertOk()
            ->assertViewHas('rentals', fn ($rentals): bool => $rentals->count() === 10 && $rentals->total() === 12);
    }

    public function test_staff_can_create_rental_for_car_and_motorcycle(): void
    {
        $staff = User::factory()->staff()->create();
        $customer = Customer::factory()->create();
        $car = Vehicle::factory()->create([
            'type' => VehicleType::Car,
            'daily_rate' => 300000,
            'status' => VehicleStatus::Available,
        ]);
        $motor = Vehicle::factory()->motorcycle()->create([
            'daily_rate' => 100000,
            'status' => VehicleStatus::Available,
        ]);

        $carResponse = $this->actingAs($staff)->post(route('rentals.store'), [
            'customer_id' => $customer->id,
            'vehicle_id' => $car->id,
            'start_date' => '2026-12-01',
            'end_date' => '2026-12-03',
            'notes' => 'Ambil pagi hari',
        ]);

        $carRental = Rental::query()->where('vehicle_id', $car->id)->firstOrFail();
        $carResponse->assertRedirect(route('rentals.show', $carRental));
        $this->assertSame(3, $carRental->duration_days);
        $this->assertSame('300000.00', $carRental->daily_rate);
        $this->assertSame('990000.00', $carRental->total_amount);
        $this->assertSame($staff->id, $carRental->user_id);
        $this->assertSame(RentalStatus::Active, $carRental->status);
        $this->assertSame(VehicleStatus::Rented, $car->fresh()->status);

        $motorResponse = $this->actingAs($staff)->post(route('rentals.store'), [
            'customer_id' => $customer->id,
            'vehicle_id' => $motor->id,
            'start_date' => '2026-12-01',
            'end_date' => '2026-12-07',
        ]);

        $motorRental = Rental::query()->where('vehicle_id', $motor->id)->firstOrFail();
        $motorResponse->assertRedirect(route('rentals.show', $motorRental));
        $this->assertSame(7, $motorRental->duration_days);
        $this->assertSame('630000.00', $motorRental->total_amount);
        $this->assertSame(VehicleStatus::Rented, $motor->fresh()->status);
    }

    public function test_rental_create_validates_required_fields_and_date_order(): void
    {
        $staff = User::factory()->staff()->create();
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create(['status' => VehicleStatus::Available]);

        $this->actingAs($staff)
            ->post(route('rentals.store'), [])
            ->assertSessionHasErrors(['customer_id', 'vehicle_id', 'start_date', 'end_date']);

        $this->actingAs($staff)
            ->post(route('rentals.store'), [
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'start_date' => '2026-12-05',
                'end_date' => '2026-12-01',
            ])
            ->assertSessionHasErrors(['end_date']);

        $this->assertDatabaseCount('rentals', 0);
    }

    public function test_rental_creation_rejects_unavailable_or_overlapping_vehicle(): void
    {
        $staff = User::factory()->staff()->create();
        $customer = Customer::factory()->create();
        $maintenanceVehicle = Vehicle::factory()->maintenance()->create();
        $availableVehicle = Vehicle::factory()->create(['status' => VehicleStatus::Available]);

        $this->actingAs($staff)
            ->post(route('rentals.store'), [
                'customer_id' => $customer->id,
                'vehicle_id' => $maintenanceVehicle->id,
                'start_date' => '2026-12-01',
                'end_date' => '2026-12-02',
            ])
            ->assertSessionHas('error');

        Rental::factory()->create([
            'vehicle_id' => $availableVehicle->id,
            'start_date' => '2026-12-01',
            'end_date' => '2026-12-05',
            'status' => RentalStatus::Active,
        ]);

        $this->actingAs($staff)
            ->post(route('rentals.store'), [
                'customer_id' => $customer->id,
                'vehicle_id' => $availableVehicle->id,
                'start_date' => '2026-12-04',
                'end_date' => '2026-12-06',
            ])
            ->assertSessionHas('error');

        $this->assertDatabaseCount('rentals', 1);
    }

    public function test_staff_can_update_active_rental_and_clear_notes(): void
    {
        $staff = User::factory()->staff()->create();
        $vehicle = Vehicle::factory()->create([
            'type' => VehicleType::Car,
            'daily_rate' => 300000,
            'status' => VehicleStatus::Rented,
        ]);
        $rental = Rental::factory()->create([
            'vehicle_id' => $vehicle->id,
            'start_date' => '2026-12-01',
            'end_date' => '2026-12-03',
            'duration_days' => 3,
            'daily_rate' => 300000,
            'total_amount' => 990000,
            'status' => RentalStatus::Active,
            'notes' => 'Catatan awal',
        ]);

        $response = $this->actingAs($staff)->put(route('rentals.update', $rental), [
            'start_date' => '2026-12-01',
            'end_date' => '2026-12-04',
            'notes' => '',
        ]);

        $response->assertRedirect(route('rentals.show', $rental));

        $rental->refresh();
        $this->assertSame(4, $rental->duration_days);
        $this->assertSame('1320000.00', $rental->total_amount);
        $this->assertNull($rental->notes);
    }

    public function test_update_rental_rejects_overlapping_dates_and_non_active_rental(): void
    {
        $staff = User::factory()->staff()->create();
        $vehicle = Vehicle::factory()->create([
            'type' => VehicleType::Car,
            'daily_rate' => 300000,
            'status' => VehicleStatus::Rented,
        ]);

        $activeRental = Rental::factory()->create([
            'vehicle_id' => $vehicle->id,
            'start_date' => '2026-12-01',
            'end_date' => '2026-12-03',
            'daily_rate' => 300000,
            'total_amount' => 990000,
            'status' => RentalStatus::Active,
        ]);

        Rental::factory()->create([
            'vehicle_id' => $vehicle->id,
            'start_date' => '2026-12-05',
            'end_date' => '2026-12-07',
            'status' => RentalStatus::Active,
        ]);

        $this->actingAs($staff)
            ->put(route('rentals.update', $activeRental), [
                'start_date' => '2026-12-01',
                'end_date' => '2026-12-05',
            ])
            ->assertSessionHas('error');

        $completedRental = Rental::factory()->completed()->create([
            'vehicle_id' => $vehicle->id,
        ]);

        $this->actingAs($staff)
            ->get(route('rentals.edit', $completedRental))
            ->assertRedirect(route('rentals.show', $completedRental))
            ->assertSessionHas('error');

        $this->actingAs($staff)
            ->put(route('rentals.update', $completedRental), [
                'start_date' => '2026-12-10',
                'end_date' => '2026-12-12',
            ])
            ->assertRedirect(route('rentals.show', $completedRental))
            ->assertSessionHas('error');
    }

    public function test_update_rental_rejects_reducing_total_below_paid_amount(): void
    {
        $staff = User::factory()->staff()->create();
        $vehicle = Vehicle::factory()->create([
            'type' => VehicleType::Car,
            'daily_rate' => 300000,
            'status' => VehicleStatus::Rented,
        ]);
        $rental = Rental::factory()->create([
            'vehicle_id' => $vehicle->id,
            'start_date' => '2026-12-01',
            'end_date' => '2026-12-03',
            'duration_days' => 3,
            'daily_rate' => 300000,
            'total_amount' => 990000,
            'status' => RentalStatus::Active,
        ]);
        Payment::factory()->paid()->create([
            'rental_id' => $rental->id,
            'amount' => 900000,
        ]);

        $this->actingAs($staff)
            ->put(route('rentals.update', $rental), [
                'start_date' => '2026-12-01',
                'end_date' => '2026-12-01',
            ])
            ->assertSessionHas('error');

        $this->assertSame(3, $rental->fresh()->duration_days);
        $this->assertSame('990000.00', $rental->fresh()->total_amount);
    }

    public function test_staff_can_return_vehicle_with_and_without_late_fee(): void
    {
        $staff = User::factory()->staff()->create();

        $onTimeVehicle = Vehicle::factory()->create([
            'type' => VehicleType::Car,
            'daily_rate' => 300000,
            'status' => VehicleStatus::Rented,
        ]);
        $onTimeRental = Rental::factory()->create([
            'vehicle_id' => $onTimeVehicle->id,
            'start_date' => '2026-12-01',
            'end_date' => '2026-12-03',
            'daily_rate' => 300000,
            'status' => RentalStatus::Active,
        ]);

        $this->actingAs($staff)
            ->post(route('rentals.return', $onTimeRental), [
                'returned_at' => '2026-12-03 17:00',
            ])
            ->assertRedirect(route('rentals.show', $onTimeRental))
            ->assertSessionHas('success');

        $onTimeRental->refresh();
        $this->assertSame(RentalStatus::Completed, $onTimeRental->status);
        $this->assertSame('0.00', $onTimeRental->late_fee);
        $this->assertSame(VehicleStatus::Available, $onTimeVehicle->fresh()->status);

        $lateVehicle = Vehicle::factory()->create([
            'type' => VehicleType::Car,
            'daily_rate' => 400000,
            'status' => VehicleStatus::Rented,
        ]);
        $lateRental = Rental::factory()->create([
            'vehicle_id' => $lateVehicle->id,
            'start_date' => '2026-12-01',
            'end_date' => '2026-12-03',
            'daily_rate' => 400000,
            'status' => RentalStatus::Active,
        ]);

        $this->actingAs($staff)
            ->post(route('rentals.return', $lateRental), [
                'returned_at' => '2026-12-05 10:00',
            ])
            ->assertRedirect(route('rentals.show', $lateRental))
            ->assertSessionHas('success');

        $lateRental->refresh();
        $this->assertSame(RentalStatus::Completed, $lateRental->status);
        $this->assertSame('400000.00', $lateRental->late_fee);
        $this->assertSame(VehicleStatus::Available, $lateVehicle->fresh()->status);
    }

    public function test_staff_can_cancel_active_rental_and_cancels_pending_payments(): void
    {
        $staff = User::factory()->staff()->create();
        $vehicle = Vehicle::factory()->create(['status' => VehicleStatus::Rented]);
        $rental = Rental::factory()->create([
            'vehicle_id' => $vehicle->id,
            'status' => RentalStatus::Active,
        ]);
        $pendingPayment = Payment::factory()->create([
            'rental_id' => $rental->id,
            'status' => PaymentStatus::Pending,
        ]);
        $paidPayment = Payment::factory()->paid()->create([
            'rental_id' => $rental->id,
            'amount' => 100000,
        ]);

        $this->actingAs($staff)
            ->post(route('rentals.cancel', $rental))
            ->assertRedirect(route('rentals.show', $rental))
            ->assertSessionHas('success');

        $this->assertSame(RentalStatus::Cancelled, $rental->fresh()->status);
        $this->assertSame(VehicleStatus::Available, $vehicle->fresh()->status);
        $this->assertSame(PaymentStatus::Cancelled, $pendingPayment->fresh()->status);
        $this->assertSame(PaymentStatus::Paid, $paidPayment->fresh()->status);
    }

    public function test_return_and_cancel_reject_non_active_rentals(): void
    {
        $staff = User::factory()->staff()->create();
        $completedRental = Rental::factory()->completed()->create();

        $this->actingAs($staff)
            ->post(route('rentals.return', $completedRental), [
                'returned_at' => '2026-12-05',
            ])
            ->assertSessionHas('error');

        $this->actingAs($staff)
            ->post(route('rentals.cancel', $completedRental))
            ->assertSessionHas('error');
    }
}
