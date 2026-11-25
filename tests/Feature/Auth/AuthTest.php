<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_see_login_page(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Masuk ke akun Anda');
    }

    public function test_authenticated_user_is_redirected_away_from_login_page(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/login')
            ->assertRedirect(route('dashboard'));
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@rentalgo.test',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@rentalgo.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        User::factory()->create([
            'email' => 'staff@rentalgo.test',
            'password' => 'password',
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'staff@rentalgo.test',
            'password' => 'password-salah',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $this->assertGuest();
    }

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_staff_cannot_access_admin_only_route(): void
    {
        Route::middleware(['web', 'auth', 'admin'])->get('/test-admin', fn () => 'admin-ok');

        $staff = User::factory()->staff()->create();

        $this->actingAs($staff)
            ->get('/test-admin')
            ->assertForbidden();
    }

    public function test_admin_can_access_admin_only_route(): void
    {
        Route::middleware(['web', 'auth', 'admin'])->get('/test-admin', fn () => 'admin-ok');

        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->get('/test-admin')
            ->assertOk()
            ->assertSee('admin-ok');
    }

    public function test_login_is_rate_limited_after_five_failed_attempts(): void
    {
        User::factory()->create([
            'email' => 'rate-limit@rentalgo.test',
            'password' => 'password',
        ]);

        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/login', [
                'email' => 'rate-limit@rentalgo.test',
                'password' => 'password-salah',
            ]);
        }

        $response = $this->post('/login', [
            'email' => 'rate-limit@rentalgo.test',
            'password' => 'password-salah',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString(
            'Terlalu banyak percobaan login',
            $response->getSession()->get('errors')->first('email'),
        );
    }

    public function test_guest_can_register_as_customer_without_creating_operator_user(): void
    {
        $response = $this->post(route('customer.register.store'), [
            'name' => 'Rina Wijaya',
            'email' => 'rina@example.test',
            'phone' => '081234567890',
            'identity_number' => '3271010101010001',
            'license_number' => 'SIM-9876543210',
            'address' => 'Jl. Merdeka No. 10',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated('customer');
        $this->assertGuest('web');

        $this->assertDatabaseHas('customers', [
            'email' => 'rina@example.test',
            'identity_number' => '3271010101010001',
        ]);
        $this->assertDatabaseMissing('users', [
            'email' => 'rina@example.test',
        ]);
    }

    public function test_customer_login_uses_separate_guard_and_cannot_access_dashboard(): void
    {
        $customer = Customer::factory()->create([
            'email' => 'pelanggan@rentalgo.test',
            'password' => 'password',
        ]);

        $this->post(route('customer.login.store'), [
            'email' => 'pelanggan@rentalgo.test',
            'password' => 'password',
        ])->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($customer, 'customer');
        $this->assertGuest('web');

        $this->actingAs($customer, 'customer')
            ->get('/dashboard')
            ->assertRedirect(route('login'));

        $this->actingAs($customer, 'customer')
            ->post(route('customer.logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest('customer');
    }

    public function test_operator_session_cannot_access_customer_only_portal_routes(): void
    {
        $staff = User::factory()->staff()->create();

        $this->actingAs($staff, 'web')
            ->get(route('customer.rentals.index'))
            ->assertRedirect(route('customer.login'));
    }
}
