<?php

namespace Database\Seeders;

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Models\Payment;
use App\Models\Rental;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $rentals = Rental::query()->with('payments')->get();

        foreach ($rentals as $rental) {
            if ($rental->payments->isNotEmpty()) {
                continue;
            }

            $isCompleted = $rental->status === RentalStatus::Completed;

            Payment::factory()->create([
                'rental_id' => $rental->id,
                'amount' => $rental->totalBill(),
                'status' => $isCompleted ? PaymentStatus::Paid : PaymentStatus::Pending,
                'paid_at' => $isCompleted ? ($rental->returned_at ?? now()) : null,
            ]);
        }
    }
}
