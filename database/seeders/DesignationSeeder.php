<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        $designations = [
            'Managing Director',
            'Director',
            'General Manager',
            'Purchase Manager',
            'Sales Manager',
            'Production Manager',
            'Manager',
            'Assistant Manager',
            'Engineer',
            'Supervisor',
            'Executive',
            'Operator',
        ];

        foreach ($designations as $name) {
            DB::table('designations')->insertOrIgnore([
                'name'       => $name,
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
