<?php

namespace Tests\Feature\Vehicles;

use App\Enums\RentalStatus;
use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use App\Models\Rental;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VehicleCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function validCarPayload(array $overrides = []): array
    {
        return array_merge([
            'plate_number' => 'B 1234 XYZ',
            'brand' => 'Toyota',
            'model' => 'Avanza',
            'type' => VehicleType::Car->value,
            'year' => 2022,
            'daily_rate' => 350000,
            'status' => VehicleStatus::Available->value,
            'seat_count' => 7,
            'transmission' => 'automatic',
            'description' => 'Mobil keluarga.',
        ], $overrides);
    }

    public function test_guest_is_redirected_from_vehicle_pages(): void
    {
        $vehicle = Vehicle::factory()->create();

        $this->get(route('vehicles.index'))->assertRedirect(route('login'));
        $this->get(route('vehicles.create'))->assertRedirect(route('login'));
        $this->get(route('vehicles.show', $vehicle))->assertRedirect(route('login'));
        $this->get(route('vehicles.edit', $vehicle))->assertRedirect(route('login'));
    }

    public function test_staff_can_search_filter_and_sort_vehicles(): void
    {
        $staff = User::factory()->staff()->create(['name' => 'Operator Rental']);

        $car = Vehicle::factory()->create([
            'brand' => 'Toyota',
            'model' => 'Avanza',
            'type' => VehicleType::Car,
            'status' => VehicleStatus::Available,
            'daily_rate' => 300000,
        ]);

        $motorcycle = Vehicle::factory()->motorcycle()->create([
            'brand' => 'Yamaha',
            'model' => 'NMAX',
            'status' => VehicleStatus::Maintenance,
            'daily_rate' => 100000,
        ]);

        $this->actingAs($staff)
            ->get(route('vehicles.index', ['search' => 'Avanza']))
            ->assertOk()
            ->assertSee('Toyota Avanza')
            ->assertDontSee('Yamaha NMAX');

        $this->actingAs($staff)
            ->get(route('vehicles.index', ['type' => VehicleType::Motorcycle->value]))
            ->assertOk()
            ->assertSee('Yamaha NMAX')
            ->assertDontSee('Toyota Avanza');

        $this->actingAs($staff)
            ->get(route('vehicles.index', ['status' => VehicleStatus::Maintenance->value]))
            ->assertOk()
            ->assertSee('Yamaha NMAX')
            ->assertDontSee('Toyota Avanza');

        $this->actingAs($staff)
            ->get(route('vehicles.index', ['sort' => 'rate_asc']))
            ->assertOk()
            ->assertViewHas('vehicles', fn ($vehicles): bool => $vehicles->first()->is($motorcycle));

        $this->actingAs($staff)
            ->get(route('vehicles.index', ['sort' => 'rate_desc']))
            ->assertOk()
            ->assertViewHas('vehicles', fn ($vehicles): bool => $vehicles->first()->is($car));
    }

    public function test_vehicle_index_is_paginated(): void
    {
        $staff = User::factory()->staff()->create();
        Vehicle::factory()->count(12)->create();

        $this->actingAs($staff)
            ->get(route('vehicles.index'))
            ->assertOk()
            ->assertViewHas('vehicles', fn ($vehicles): bool => $vehicles->count() === 10 && $vehicles->total() === 12);
    }

    public function test_staff_can_create_car_with_image(): void
    {
        Storage::fake('public');

        $staff = User::factory()->staff()->create();

        $response = $this->actingAs($staff)->post(route('vehicles.store'), $this->validCarPayload([
            'image' => UploadedFile::fake()->image('car.jpg', 800, 600),
        ]));

        $vehicle = Vehicle::query()->where('plate_number', 'B 1234 XYZ')->firstOrFail();

        $response->assertRedirect(route('vehicles.show', $vehicle));
        $this->assertSame(VehicleType::Car, $vehicle->type);
        $this->assertSame('automatic', $vehicle->transmission);
        $this->assertNull($vehicle->engine_cc);
        $this->assertNotNull($vehicle->image_path);
        Storage::disk('public')->assertExists($vehicle->image_path);
    }

    public function test_staff_can_create_motorcycle_without_car_fields(): void
    {
        $staff = User::factory()->staff()->create();

        $response = $this->actingAs($staff)->post(route('vehicles.store'), [
            'plate_number' => 'B 5678 ABC',
            'brand' => 'Honda',
            'model' => 'Vario',
            'type' => VehicleType::Motorcycle->value,
            'year' => 2023,
            'daily_rate' => 120000,
            'status' => VehicleStatus::Available->value,
            'engine_cc' => 160,
            'description' => 'Motor harian.',
        ]);

        $vehicle = Vehicle::query()->where('plate_number', 'B 5678 ABC')->firstOrFail();

        $response->assertRedirect(route('vehicles.show', $vehicle));
        $this->assertSame(VehicleType::Motorcycle, $vehicle->type);
        $this->assertSame(160, $vehicle->engine_cc);
        $this->assertNull($vehicle->seat_count);
        $this->assertNull($vehicle->transmission);
    }

    public function test_vehicle_validation_requires_type_specific_fields_and_unique_plate(): void
    {
        $staff = User::factory()->staff()->create();
        Vehicle::factory()->create(['plate_number' => 'B 9999 ZZZ']);

        $this->actingAs($staff)
            ->post(route('vehicles.store'), $this->validCarPayload([
                'seat_count' => null,
                'transmission' => null,
            ]))
            ->assertSessionHasErrors(['seat_count', 'transmission']);

        $this->actingAs($staff)
            ->post(route('vehicles.store'), [
                'plate_number' => 'B 1111 AAA',
                'brand' => 'Yamaha',
                'model' => 'Aerox',
                'type' => VehicleType::Motorcycle->value,
                'year' => 2023,
                'daily_rate' => 150000,
                'status' => VehicleStatus::Available->value,
            ])
            ->assertSessionHasErrors('engine_cc');

        $this->actingAs($staff)
            ->post(route('vehicles.store'), $this->validCarPayload([
                'plate_number' => 'B 9999 ZZZ',
            ]))
            ->assertSessionHasErrors('plate_number');

        $this->actingAs($staff)
            ->post(route('vehicles.store'), $this->validCarPayload([
                'image' => UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'),
            ]))
            ->assertSessionHasErrors('image');
    }

    public function test_vehicle_update_replaces_image_and_deletes_old_file(): void
    {
        Storage::fake('public');

        $staff = User::factory()->staff()->create();
        $vehicle = Vehicle::factory()->create([
            'plate_number' => 'B 2222 BBB',
            'image_path' => 'vehicles/old.jpg',
        ]);
        Storage::disk('public')->put('vehicles/old.jpg', 'old-image');

        $response = $this->actingAs($staff)->put(route('vehicles.update', $vehicle), $this->validCarPayload([
            'plate_number' => $vehicle->plate_number,
            'image' => UploadedFile::fake()->image('new.jpg', 800, 600),
        ]));

        $response->assertRedirect(route('vehicles.show', $vehicle));

        $vehicle->refresh();
        $this->assertNotSame('vehicles/old.jpg', $vehicle->image_path);
        Storage::disk('public')->assertMissing('vehicles/old.jpg');
        Storage::disk('public')->assertExists($vehicle->image_path);
    }

    public function test_vehicle_update_keeps_image_when_no_new_file_is_uploaded(): void
    {
        Storage::fake('public');

        $staff = User::factory()->staff()->create();
        $vehicle = Vehicle::factory()->create([
            'plate_number' => 'B 3333 CCC',
            'image_path' => 'vehicles/saved.jpg',
        ]);
        Storage::disk('public')->put('vehicles/saved.jpg', 'saved-image');

        $this->actingAs($staff)
            ->put(route('vehicles.update', $vehicle), $this->validCarPayload([
                'plate_number' => $vehicle->plate_number,
                'brand' => 'Honda',
            ]))
            ->assertRedirect(route('vehicles.show', $vehicle));

        $this->assertSame('vehicles/saved.jpg', $vehicle->fresh()->image_path);
        Storage::disk('public')->assertExists('vehicles/saved.jpg');
    }

    public function test_vehicle_with_active_rental_cannot_change_status_or_type(): void
    {
        $staff = User::factory()->staff()->create();
        $vehicle = Vehicle::factory()->rented()->create([
            'plate_number' => 'B 4444 DDD',
        ]);

        Rental::factory()->create([
            'vehicle_id' => $vehicle->id,
            'status' => RentalStatus::Active,
        ]);

        $this->actingAs($staff)
            ->put(route('vehicles.update', $vehicle), $this->validCarPayload([
                'plate_number' => $vehicle->plate_number,
                'status' => VehicleStatus::Available->value,
            ]))
            ->assertSessionHasErrors('status');

        $this->actingAs($staff)
            ->put(route('vehicles.update', $vehicle), $this->validCarPayload([
                'plate_number' => $vehicle->plate_number,
                'type' => VehicleType::Motorcycle->value,
                'status' => VehicleStatus::Rented->value,
                'engine_cc' => 150,
            ]))
            ->assertSessionHasErrors('type');

        $vehicle->refresh();
        $this->assertSame(VehicleStatus::Rented, $vehicle->status);
        $this->assertSame(VehicleType::Car, $vehicle->type);
    }

    public function test_vehicle_update_rejects_plate_number_owned_by_another_vehicle(): void
    {
        $staff = User::factory()->staff()->create();
        $vehicle = Vehicle::factory()->create(['plate_number' => 'B 5555 EEE']);
        $other = Vehicle::factory()->create(['plate_number' => 'B 6666 FFF']);

        $this->actingAs($staff)
            ->put(route('vehicles.update', $vehicle), $this->validCarPayload([
                'plate_number' => $other->plate_number,
            ]))
            ->assertSessionHasErrors('plate_number');

        $this->assertSame('B 5555 EEE', $vehicle->fresh()->plate_number);
    }

    public function test_vehicle_creation_rejects_plate_number_from_soft_deleted_vehicle(): void
    {
        $staff = User::factory()->staff()->create();
        $deleted = Vehicle::factory()->create(['plate_number' => 'B 7777 GGG']);
        $deleted->delete();

        $this->actingAs($staff)
            ->post(route('vehicles.store'), $this->validCarPayload([
                'plate_number' => 'B 7777 GGG',
            ]))
            ->assertSessionHasErrors('plate_number');

        $this->assertDatabaseCount('vehicles', 1);
        $this->assertSoftDeleted($deleted);
    }

    public function test_vehicle_detail_shows_rental_history(): void
    {
        $staff = User::factory()->staff()->create();
        $vehicle = Vehicle::factory()->create();
        $rental = Rental::factory()->create([
            'vehicle_id' => $vehicle->id,
            'status' => RentalStatus::Active,
        ]);

        $this->actingAs($staff)
            ->get(route('vehicles.show', $vehicle))
            ->assertOk()
            ->assertSee($rental->rental_code)
            ->assertSee($rental->customer->name);
    }

    public function test_staff_cannot_delete_vehicle(): void
    {
        $staff = User::factory()->staff()->create();
        $vehicle = Vehicle::factory()->create();

        $this->actingAs($staff)
            ->delete(route('vehicles.destroy', $vehicle))
            ->assertForbidden();

        $this->assertNotSoftDeleted($vehicle);
    }

    public function test_admin_can_soft_delete_vehicle_and_remove_image(): void
    {
        Storage::fake('public');

        $admin = User::factory()->admin()->create();
        $vehicle = Vehicle::factory()->create([
            'image_path' => 'vehicles/delete-me.jpg',
        ]);
        Storage::disk('public')->put('vehicles/delete-me.jpg', 'image');

        $this->actingAs($admin)
            ->delete(route('vehicles.destroy', $vehicle))
            ->assertRedirect(route('vehicles.index'))
            ->assertSessionHas('success');

        $this->assertSoftDeleted($vehicle);
        Storage::disk('public')->assertMissing('vehicles/delete-me.jpg');
    }

    public function test_vehicle_with_active_rental_cannot_be_deleted(): void
    {
        $admin = User::factory()->admin()->create();
        $vehicle = Vehicle::factory()->create();

        Rental::factory()->create([
            'vehicle_id' => $vehicle->id,
            'status' => RentalStatus::Active,
        ]);

        $this->actingAs($admin)
            ->delete(route('vehicles.destroy', $vehicle))
            ->assertRedirect(route('vehicles.index'))
            ->assertSessionHas('error');

        $this->assertNotSoftDeleted($vehicle);
    }
}
