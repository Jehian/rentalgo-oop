<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::factory()->create([
            'name' => 'Pelanggan Demo',
            'email' => 'customer@rentalgo.test',
            'password' => (string) config('rental.demo_password', 'password'),
        ]);

        Customer::factory()->count(7)->create();
    }
}
