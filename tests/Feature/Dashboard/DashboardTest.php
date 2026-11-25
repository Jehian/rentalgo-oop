<?php

namespace Tests\Feature\Dashboard;

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Enums\VehicleStatus;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Rental;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_dashboard_shows_summary_charts_and_latest_rentals(): void
    {
        $user = User::factory()->admin()->create();
        $customer = Customer::factory()->create(['name' => 'Budi Santoso']);
        $availableVehicle = Vehicle::factory()->create(['status' => VehicleStatus::Available]);
        $rentedVehicle = Vehicle::factory()->rented()->create([
            'brand' => 'Toyota',
            'model' => 'Avanza',
        ]);

        $rental = Rental::factory()->create([
            'customer_id' => $customer->id,
            'vehicle_id' => $rentedVehicle->id,
            'user_id' => $user->id,
            'rental_code' => 'RNT-TEST-0001',
            'status' => RentalStatus::Active,
        ]);

        Payment::factory()->paid()->create([
            'rental_id' => $rental->id,
            'amount' => 1200000,
            'paid_at' => now(),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk()
            ->assertViewIs('dashboard.index')
            ->assertViewHas('summary', fn (array $summary): bool => $summary == [
                'customers' => 1,
                'vehicles_total' => 2,
                'vehicles_available' => 1,
                'vehicles_rented' => 1,
                'active_rentals' => 1,
                'monthly_revenue' => 1200000.0,
            ])
            ->assertViewHas('revenueChart', fn (array $chart): bool => count($chart['labels']) === 6 && count($chart['totals']) === 6)
            ->assertViewHas('vehicleChart', fn (array $chart): bool => $chart['totals'] === [1, 1, 0])
            ->assertSee('Budi Santoso')
            ->assertSee('RNT-TEST-0001')
            ->assertSee('Rp 1.200.000')
            ->assertSee('dashboard-chart-data', false);
    }

    public function test_monthly_revenue_only_counts_paid_payments_from_the_last_six_months(): void
    {
        $user = User::factory()->create();
        $rental = Rental::factory()->create(['user_id' => $user->id]);

        Payment::factory()->paid()->create([
            'rental_id' => $rental->id,
            'amount' => 500000,
            'paid_at' => now()->subMonths(2),
        ]);

        Payment::factory()->create([
            'rental_id' => $rental->id,
            'amount' => 900000,
            'status' => PaymentStatus::Pending,
            'paid_at' => null,
        ]);

        Payment::factory()->paid()->create([
            'rental_id' => $rental->id,
            'amount' => 300000,
            'paid_at' => now()->subMonths(7),
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk()
            ->assertViewHas('summary', fn (array $summary): bool => $summary['monthly_revenue'] === 0.0)
            ->assertViewHas('revenueChart', function (array $chart): bool {
                return in_array(500000.0, $chart['totals'], true)
                    && ! in_array(300000.0, $chart['totals'], true)
                    && ! in_array(900000.0, $chart['totals'], true);
            });
    }

    public function test_staff_can_access_dashboard(): void
    {
        $staff = User::factory()->staff()->create();

        $this->actingAs($staff)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Dashboard');
    }
}
