<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\User;
use App\Models\Vehicle;
use App\Services\RentalService;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::query()->orderBy('id')->get();
        $vehicles = Vehicle::query()->orderBy('id')->get();
        $staff = User::query()->where('role', UserRole::Staff->value)->firstOrFail();
        $rentalService = app(RentalService::class);

        if ($customers->isEmpty() || $vehicles->count() < 8) {
            return;
        }

        $customerIndex = 0;

        for ($i = 0; $i < 4; $i++) {
            $customer = $customers[$customerIndex++ % $customers->count()];
            $vehicle = $vehicles[$i];

            $completed = $rentalService->createRental(
                $customer,
                $vehicle,
                now()->subDays(20),
                now()->subDays(17),
                $staff,
            );
            $rentalService->completeRental($completed, now()->subDays(17));
            $vehicle->refresh();

            $rentalService->createRental(
                $customer,
                $vehicle,
                now()->addDays(2),
                now()->addDays(4),
                $staff,
            );
        }

        for ($i = 4; $i < 6; $i++) {
            $customer = $customers[$customerIndex++ % $customers->count()];

            $rentalService->createRental(
                $customer,
                $vehicles[$i],
                now()->addDay(),
                now()->addDays(3),
                $staff,
            );
        }

        for ($i = 6; $i < 8; $i++) {
            $customer = $customers[$customerIndex++ % $customers->count()];

            $completed = $rentalService->createRental(
                $customer,
                $vehicles[$i],
                now()->subDays(15),
                now()->subDays(12),
                $staff,
            );
            $rentalService->completeRental($completed, now()->subDays(12));
        }
    }
}
