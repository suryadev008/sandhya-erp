<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MachineSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch machine type IDs seeded by MachineTypeSeeder
        $types = DB::table('machine_types')->pluck('id', 'type_name');

        $machines = [
            ['machine_name' => 'Lathe Machine 1', 'machine_number' => 'LT-001',  'machine_type_id' => $types['Lathe'], 'description' => 'Heavy duty lathe machine',  'is_active' => true],
            ['machine_name' => 'Lathe Machine 2', 'machine_number' => 'LT-002',  'machine_type_id' => $types['Lathe'], 'description' => 'Medium duty lathe machine', 'is_active' => true],
            ['machine_name' => 'CNC Machine 1',   'machine_number' => 'CNC-001', 'machine_type_id' => $types['CNC'],   'description' => 'CNC turning center',         'is_active' => true],
            ['machine_name' => 'CNC Machine 2',   'machine_number' => 'CNC-002', 'machine_type_id' => $types['CNC'],   'description' => 'CNC milling machine',        'is_active' => true],
            ['machine_name' => 'CNC Machine 3',   'machine_number' => 'CNC-003', 'machine_type_id' => $types['CNC'],   'description' => null,                         'is_active' => false],
            ['machine_name' => 'Drill Machine 1', 'machine_number' => 'DR-001',  'machine_type_id' => $types['Drill'], 'description' => 'Radial drill machine',       'is_active' => true],
            ['machine_name' => 'Drill Machine 2', 'machine_number' => 'DR-002',  'machine_type_id' => $types['Drill'], 'description' => null,                         'is_active' => true],
            ['machine_name' => 'Tap Machine 1',   'machine_number' => 'TP-001',  'machine_type_id' => $types['Tap'],   'description' => 'Tapping machine',            'is_active' => true],
            ['machine_name' => 'Tap Machine 2',   'machine_number' => 'TP-002',  'machine_type_id' => $types['Tap'],   'description' => null,                         'is_active' => false],
            ['machine_name' => 'Lathe Machine 3', 'machine_number' => 'LT-003',  'machine_type_id' => $types['Lathe'], 'description' => 'Old lathe machine',          'is_active' => false],
        ];

        foreach ($machines as $machine) {
            DB::table('machines')->insert([
                ...$machine,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
