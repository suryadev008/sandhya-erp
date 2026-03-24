<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin User ──────────────────────────────────────────────────
        User::factory()->create([
            'name' => 'Suraj Das',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin@1234'),
        ]);

        // ── Master Tables (order matters — FK dependencies) ─────────────
        $this->call([
            MachineTypeSeeder::class, // No FK dependency
            MachineSeeder::class,     // Depends on: machine_types
            CompanySeeder::class,     // No FK dependency
            OperationSeeder::class,   // Depends on: companies
            PartSeeder::class,        // Depends on: companies
            EmployeeSeeder::class,    // Depends on: users
        ]);
    }
}