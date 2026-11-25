<?php

namespace Tests\Feature\Customer;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use App\Models\Customer;
use App\Models\Rental;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_browse_and_filter_public_vehicle_catalog(): void
    {
        Vehicle::factory()->create([
            'brand' => 'Toyota',
            'model' => 'Avanza',
            'type' => VehicleType::Car,
            'status' => VehicleStatus::Available,
        ]);

        Vehicle::factory()->motorcycle()->create([
            'brand' => 'Yamaha',
            'model' => 'NMAX',
            'status' => VehicleStatus::Maintenance,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Toyota Avanza')
            ->assertSee('Yamaha NMAX');

        $this->get('/?type=car&search=Avanza')
            ->assertOk()
            ->assertSee('Toyota Avanza')
            ->assertDontSee('Yamaha NMAX');
    }

    public function test_guest_can_view_public_vehicle_detail(): void
    {
        $vehicle = Vehicle::factory()->create([
            'brand' => 'Honda',
            'model' => 'Brio',
            'daily_rate' => 300000,
        ]);

        $this->get(route('portal.vehicles.show', $vehicle))
            ->assertOk()
            ->assertSee('Honda Brio')
            ->assertSee('Rp 300.000');
    }

    public function test_customer_can_book_available_vehicle_with_pending_payment(): void
    {
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'type' => VehicleType::Car,
            'daily_rate' => 300000,
            'status' => VehicleStatus::Available,
        ]);

        $startDate = now()->addDay()->toDateString();
        $endDate = now()->addDays(2)->toDateString();

        $response = $this->actingAs($customer, 'customer')->post(route('customer.rentals.store', $vehicle), [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'payment_method' => PaymentMethod::Transfer->value,
            'notes' => 'Ambil pagi hari',
        ]);

        $rental = Rental::query()->where('customer_id', $customer->id)->firstOrFail();

        $response->assertRedirect(route('customer.rentals.show', $rental));
        $this->assertNull($rental->user_id);
        $this->assertSame(2, $rental->duration_days);
        $this->assertSame('660000.00', $rental->total_amount);
        $this->assertSame('Ambil pagi hari', $rental->notes);
        $this->assertSame(VehicleStatus::Rented, $vehicle->fresh()->status);

        $this->assertDatabaseHas('payments', [
            'rental_id' => $rental->id,
            'amount' => 660000,
            'method' => PaymentMethod::Transfer->value,
            'status' => PaymentStatus::Pending->value,
        ]);
    }

    public function test_customer_cannot_book_unavailable_vehicle(): void
    {
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->rented()->create();

        $response = $this->actingAs($customer, 'customer')
            ->from(route('portal.vehicles.show', $vehicle))
            ->post(route('customer.rentals.store', $vehicle), [
                'start_date' => now()->addDay()->toDateString(),
                'end_date' => now()->addDays(2)->toDateString(),
            ]);

        $response->assertRedirect(route('portal.vehicles.show', $vehicle));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('rentals', 0);
    }

    public function test_customer_can_view_own_rentals_and_cancel_active_rental_but_not_others(): void
    {
        $customer = Customer::factory()->create();
        $otherCustomer = Customer::factory()->create();

        $vehicle = Vehicle::factory()->rented()->create();

        $ownRental = Rental::factory()->create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'user_id' => null,
            'status' => RentalStatus::Active,
        ]);

        $otherRental = Rental::factory()->create([
            'customer_id' => $otherCustomer->id,
        ]);

        $this->actingAs($customer, 'customer')
            ->get(route('customer.rentals.index'))
            ->assertOk()
            ->assertSee($ownRental->rental_code)
            ->assertDontSee($otherRental->rental_code);

        $this->actingAs($customer, 'customer')
            ->get(route('customer.rentals.show', $otherRental))
            ->assertForbidden();

        $this->actingAs($customer, 'customer')
            ->post(route('customer.rentals.cancel', $ownRental))
            ->assertRedirect(route('customer.rentals.show', $ownRental));

        $this->assertSame(RentalStatus::Cancelled, $ownRental->fresh()->status);
        $this->assertSame(VehicleStatus::Available, $vehicle->fresh()->status);
    }

    public function test_customer_can_update_own_profile(): void
    {
        $customer = Customer::factory()->create([
            'name' => 'Nama Lama',
        ]);

        $response = $this->actingAs($customer, 'customer')->put(route('customer.profile.update'), [
            'name' => 'Nama Baru',
            'phone' => '089999999999',
            'identity_number' => $customer->identity_number,
            'license_number' => $customer->license_number,
            'address' => 'Jl. Sudirman No. 1',
        ]);

        $response->assertRedirect(route('customer.profile.edit'));
        $this->assertSame('Nama Baru', $customer->fresh()->name);
        $this->assertSame('089999999999', $customer->fresh()->phone);
        $this->assertSame('Jl. Sudirman No. 1', $customer->fresh()->address);
    }
}
