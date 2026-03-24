<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MachineTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['type_name' => 'Lathe',  'remark' => 'Lathe turning machines',  'is_active' => true],
            ['type_name' => 'CNC',    'remark' => 'CNC machining centers',    'is_active' => true],
            ['type_name' => 'Drill',  'remark' => 'Drilling machines',        'is_active' => true],
            ['type_name' => 'Tap',    'remark' => 'Tapping machines',         'is_active' => true],
        ];

        foreach ($types as $type) {
            DB::table('machine_types')->insert([
                ...$type,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
