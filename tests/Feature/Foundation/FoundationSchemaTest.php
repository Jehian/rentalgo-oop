<?php

namespace Tests\Feature\Foundation;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Enums\UserRole;
use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FoundationSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_business_tables_and_columns_exist(): void
    {
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasTable('customers'));
        $this->assertTrue(Schema::hasTable('vehicles'));
        $this->assertTrue(Schema::hasTable('rentals'));
        $this->assertTrue(Schema::hasTable('payments'));

        $this->assertTrue(Schema::hasColumns('users', ['role']));
        $this->assertTrue(Schema::hasColumns('customers', ['password', 'remember_token']));
        $this->assertTrue(Schema::hasColumns('vehicles', ['type', 'status', 'daily_rate']));
        $this->assertTrue(Schema::hasColumns('rentals', ['rental_code', 'duration_days', 'total_amount', 'late_fee']));
        $this->assertTrue(Schema::hasColumns('payments', ['payment_code', 'method', 'status']));
    }

    public function test_enum_casts_work_for_models(): void
    {
        $user = User::factory()->admin()->create();
        $vehicle = Vehicle::factory()->create([
            'type' => VehicleType::Car,
            'status' => VehicleStatus::Available,
        ]);
        $rental = Rental::factory()->create([
            'status' => RentalStatus::Active,
        ]);
        $payment = Payment::factory()->create([
            'rental_id' => $rental->id,
            'method' => PaymentMethod::Transfer,
            'status' => PaymentStatus::Pending,
        ]);

        $this->assertSame(UserRole::Admin, $user->role);
        $this->assertSame(VehicleType::Car, $vehicle->type);
        $this->assertSame(VehicleStatus::Available, $vehicle->status);
        $this->assertSame(RentalStatus::Active, $rental->status);
        $this->assertSame(PaymentMethod::Transfer, $payment->method);
        $this->assertSame(PaymentStatus::Pending, $payment->status);
    }

    public function test_customers_and_vehicles_use_soft_deletes(): void
    {
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create();

        $customer->delete();
        $vehicle->delete();

        $this->assertSoftDeleted('customers', ['id' => $customer->id]);
        $this->assertSoftDeleted('vehicles', ['id' => $vehicle->id]);
    }
}
