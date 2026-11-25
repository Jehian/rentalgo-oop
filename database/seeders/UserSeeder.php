<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = (string) config('rental.demo_password', 'password');

        User::updateOrCreate(
            ['email' => 'admin@rentalgo.test'],
            [
                'name' => 'Admin RentalGo',
                'email_verified_at' => now(),
                'password' => $password,
                'role' => UserRole::Admin,
            ],
        );

        User::updateOrCreate(
            ['email' => 'staff@rentalgo.test'],
            [
                'name' => 'Staff RentalGo',
                'email_verified_at' => now(),
                'password' => $password,
                'role' => UserRole::Staff,
            ],
        );
    }
}
