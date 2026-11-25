<?php

namespace Database\Factories;

use App\Enums\RentalStatus;
use App\Models\Customer;
use App\Models\Rental;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rental>
 */
class RentalFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+1 month');
        $endDate = (clone $startDate)->modify('+2 days');
        $dailyRate = fake()->numberBetween(80000, 600000);

        return [
            'rental_code' => fake()->unique()->numerify('RNT-########-####'),
            'customer_id' => Customer::factory(),
            'vehicle_id' => Vehicle::factory(),
            'user_id' => User::factory(),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'duration_days' => 3,
            'daily_rate' => $dailyRate,
            'total_amount' => $dailyRate * 3,
            'late_fee' => 0,
            'status' => RentalStatus::Active,
            'returned_at' => null,
            'notes' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RentalStatus::Completed,
            'returned_at' => now(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RentalStatus::Cancelled,
        ]);
    }
}
