<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        Vehicle::factory()->count(4)->create();
        Vehicle::factory()->motorcycle()->count(4)->create();
    }
}
