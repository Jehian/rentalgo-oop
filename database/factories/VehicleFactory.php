<?php

namespace Database\Factories;

use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'plate_number' => strtoupper(fake()->unique()->bothify('B #### ???')),
            'brand' => fake()->randomElement(['Toyota', 'Honda', 'Suzuki', 'Daihatsu', 'Mitsubishi']),
            'model' => fake()->randomElement(['Avanza', 'Brio', 'Ertiga', 'Xenia', 'Xpander']),
            'type' => VehicleType::Car,
            'year' => fake()->numberBetween(2015, 2026),
            'daily_rate' => fake()->numberBetween(250000, 600000),
            'status' => VehicleStatus::Available,
            'seat_count' => 5,
            'transmission' => fake()->randomElement(['manual', 'automatic']),
            'engine_cc' => null,
            'image_path' => null,
            'description' => fake()->sentence(),
        ];
    }

    public function motorcycle(): static
    {
        return $this->state(fn (array $attributes) => [
            'brand' => fake()->randomElement(['Honda', 'Yamaha', 'Suzuki', 'Kawasaki']),
            'model' => fake()->randomElement(['Beat', 'Vario', 'NMAX', 'Satria', 'Aerox']),
            'type' => VehicleType::Motorcycle,
            'daily_rate' => fake()->numberBetween(80000, 150000),
            'seat_count' => null,
            'transmission' => null,
            'engine_cc' => fake()->numberBetween(110, 250),
        ]);
    }

    public function rented(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => VehicleStatus::Rented,
        ]);
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => VehicleStatus::Maintenance,
        ]);
    }
}
