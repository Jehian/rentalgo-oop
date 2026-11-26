<?php

namespace Tests\Feature\Customers;

use App\Enums\RentalStatus;
use App\Models\Customer;
use App\Models\Rental;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_customer_pages(): void
    {
        $customer = Customer::factory()->create();

        $this->get(route('customers.index'))->assertRedirect(route('login'));
        $this->get(route('customers.create'))->assertRedirect(route('login'));
        $this->get(route('customers.show', $customer))->assertRedirect(route('login'));
        $this->get(route('customers.edit', $customer))->assertRedirect(route('login'));
    }

    public function test_staff_can_search_and_sort_customers(): void
    {
        $staff = User::factory()->staff()->create(['name' => 'Operator Rental']);
        Customer::factory()->create(['name' => 'Budi Santoso']);
        Customer::factory()->create(['name' => 'Ani Lestari']);

        $this->actingAs($staff)
            ->get(route('customers.index', ['search' => 'Budi']))
            ->assertOk()
            ->assertSee('Budi Santoso')
            ->assertDontSee('Ani Lestari');

        $this->actingAs($staff)
            ->get(route('customers.index', ['sort' => 'name_asc']))
            ->assertOk()
            ->assertSeeInOrder(['Ani Lestari', 'Budi Santoso']);
    }

    public function test_customer_index_is_paginated(): void
    {
        $staff = User::factory()->staff()->create();
        Customer::factory()->count(12)->create();

        $this->actingAs($staff)
            ->get(route('customers.index'))
            ->assertOk()
            ->assertViewHas('customers', fn ($customers): bool => $customers->count() === 10 && $customers->total() === 12);
    }

    public function test_staff_can_create_customer_without_portal_password(): void
    {
        $staff = User::factory()->staff()->create();

        $response = $this->actingAs($staff)->post(route('customers.store'), [
            'name' => 'Siti Aminah',
            'email' => 'siti@example.test',
            'phone' => '081234567890',
            'identity_number' => '3171234567890001',
            'license_number' => 'SIM-1234567890',
            'address' => 'Jl. Merdeka No. 1',
        ]);

        $customer = Customer::query()->where('email', 'siti@example.test')->firstOrFail();

        $response->assertRedirect(route('customers.show', $customer));
        $this->assertSame('Siti Aminah', $customer->name);
        $this->assertSame('081234567890', $customer->phone);
        $this->assertNull($customer->password);
    }

    public function test_customer_create_validates_required_and_unique_fields(): void
    {
        $staff = User::factory()->staff()->create();
        Customer::factory()->create([
            'email' => 'used@example.test',
            'identity_number' => '3171234567890002',
            'license_number' => 'SIM-0987654321',
        ]);

        $this->actingAs($staff)
            ->post(route('customers.store'), [])
            ->assertSessionHasErrors(['name', 'phone', 'identity_number', 'license_number']);

        $this->actingAs($staff)
            ->post(route('customers.store'), [
                'name' => 'Pelanggan Duplikat',
                'email' => 'used@example.test',
                'phone' => '081200000000',
                'identity_number' => '3171234567890002',
                'license_number' => 'SIM-0987654321',
            ])
            ->assertSessionHasErrors(['email', 'identity_number', 'license_number']);

        $this->assertDatabaseCount('customers', 1);
    }

    public function test_staff_can_update_customer_and_keep_own_unique_values(): void
    {
        $staff = User::factory()->staff()->create();
        $customer = Customer::factory()->create();

        $response = $this->actingAs($staff)->put(route('customers.update', $customer), [
            'name' => 'Nama Diperbarui',
            'email' => $customer->email,
            'phone' => '089999999999',
            'identity_number' => $customer->identity_number,
            'license_number' => $customer->license_number,
            'address' => 'Jl. Sudirman No. 10',
        ]);

        $response->assertRedirect(route('customers.show', $customer));

        $customer->refresh();
        $this->assertSame('Nama Diperbarui', $customer->name);
        $this->assertSame('089999999999', $customer->phone);
        $this->assertSame('Jl. Sudirman No. 10', $customer->address);
    }

    public function test_customer_update_rejects_values_owned_by_another_customer(): void
    {
        $staff = User::factory()->staff()->create();
        $customer = Customer::factory()->create();
        $other = Customer::factory()->create([
            'email' => 'other@example.test',
            'identity_number' => '3171234567890099',
            'license_number' => 'SIM-9999999999',
        ]);

        $this->actingAs($staff)
            ->put(route('customers.update', $customer), [
                'name' => $customer->name,
                'email' => $other->email,
                'phone' => $customer->phone,
                'identity_number' => $other->identity_number,
                'license_number' => $other->license_number,
            ])
            ->assertSessionHasErrors(['email', 'identity_number', 'license_number']);

        $this->assertSame($customer->email, $customer->fresh()->email);
    }

    public function test_customer_creation_rejects_values_from_soft_deleted_customer(): void
    {
        $staff = User::factory()->staff()->create();
        $deleted = Customer::factory()->create([
            'email' => 'deleted@example.test',
            'identity_number' => '3171234567890088',
            'license_number' => 'SIM-8888888888',
        ]);
        $deleted->delete();

        $this->actingAs($staff)
            ->post(route('customers.store'), [
                'name' => 'Pelanggan Baru',
                'email' => 'deleted@example.test',
                'phone' => '081200000001',
                'identity_number' => '3171234567890088',
                'license_number' => 'SIM-8888888888',
            ])
            ->assertSessionHasErrors(['email', 'identity_number', 'license_number']);

        $this->assertDatabaseCount('customers', 1);
        $this->assertSoftDeleted($deleted);
    }

    public function test_customer_detail_shows_rental_history(): void
    {
        $staff = User::factory()->staff()->create();
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create();
        $rental = Rental::factory()->create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'status' => RentalStatus::Active,
        ]);

        $this->actingAs($staff)
            ->get(route('customers.show', $customer))
            ->assertOk()
            ->assertSee($rental->rental_code)
            ->assertSee($vehicle->plate_number);
    }

    public function test_staff_cannot_delete_customer(): void
    {
        $staff = User::factory()->staff()->create();
        $customer = Customer::factory()->create();

        $this->actingAs($staff)
            ->delete(route('customers.destroy', $customer))
            ->assertForbidden();

        $this->assertNotSoftDeleted($customer);
    }

    public function test_admin_can_soft_delete_customer_without_active_rental(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create();

        Rental::factory()->completed()->create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
        ]);

        $this->actingAs($admin)
            ->delete(route('customers.destroy', $customer))
            ->assertRedirect(route('customers.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted($customer);
    }

    public function test_customer_with_active_rental_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = Customer::factory()->create();
        $vehicle = Vehicle::factory()->create();

        Rental::factory()->create([
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'status' => RentalStatus::Active,
        ]);

        $this->actingAs($admin)
            ->delete(route('customers.destroy', $customer))
            ->assertRedirect(route('customers.index'))
            ->assertSessionHas('error');

        $this->assertNotSoftDeleted($customer);
    }
}
