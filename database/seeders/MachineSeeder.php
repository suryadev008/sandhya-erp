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
            ['machine_name' => 'Lathe Machine 1', 'machine_number' => 'LT-001',  'machine_type_id' => $types['Lathe'], 'description' => 'Heavy duty lathe machine',  'working' => 'Used for turning raw bar stock into precision cylindrical components. Handles shafts, bushings, and flanges up to 300mm diameter.', 'is_active' => true],
            ['machine_name' => 'Lathe Machine 2', 'machine_number' => 'LT-002',  'machine_type_id' => $types['Lathe'], 'description' => 'Medium duty lathe machine', 'working' => 'Dedicated to finish turning and facing operations for medium-batch production. Primarily used for coupling and adapter components.', 'is_active' => true],
            ['machine_name' => 'CNC Machine 1',   'machine_number' => 'CNC-001', 'machine_type_id' => $types['CNC'],   'description' => 'CNC turning center',         'working' => 'High-precision CNC turning center for complex profiles. Used for production of valve bodies, spindles, and threaded components with tight tolerances (±0.01mm).', 'is_active' => true],
            ['machine_name' => 'CNC Machine 2',   'machine_number' => 'CNC-002', 'machine_type_id' => $types['CNC'],   'description' => 'CNC milling machine',        'working' => 'CNC milling for flat surfaces, slots, and keyways. Used for machining gear blanks, brackets, and housing components.', 'is_active' => true],
            ['machine_name' => 'CNC Machine 3',   'machine_number' => 'CNC-003', 'machine_type_id' => $types['CNC'],   'description' => null,                         'working' => 'Backup CNC machine currently under maintenance. Scheduled for use in overflow production when primary CNC machines are at capacity.', 'is_active' => false],
            ['machine_name' => 'Drill Machine 1', 'machine_number' => 'DR-001',  'machine_type_id' => $types['Drill'], 'description' => 'Radial drill machine',       'working' => 'Radial drilling for hole-making operations on large workpieces. Used for drilling bolt hole patterns on flanges and mounting plates.', 'is_active' => true],
            ['machine_name' => 'Drill Machine 2', 'machine_number' => 'DR-002',  'machine_type_id' => $types['Drill'], 'description' => null,                         'working' => 'Bench drill for small-diameter holes and spot-facing operations. Handles drill sizes from 2mm to 25mm.', 'is_active' => true],
            ['machine_name' => 'Tap Machine 1',   'machine_number' => 'TP-001',  'machine_type_id' => $types['Tap'],   'description' => 'Tapping machine',            'working' => 'Semi-automatic tapping machine for internal threading. Used for M6 to M20 threads on steel and aluminum components. Processes approx. 150 pieces/hour.', 'is_active' => true],
            ['machine_name' => 'Tap Machine 2',   'machine_number' => 'TP-002',  'machine_type_id' => $types['Tap'],   'description' => null,                         'working' => 'Manual tapping stand for special or non-standard thread forms. Used for prototype and low-volume jobs requiring custom thread profiles.', 'is_active' => false],
            ['machine_name' => 'Lathe Machine 3', 'machine_number' => 'LT-003',  'machine_type_id' => $types['Lathe'], 'description' => 'Old lathe machine',          'working' => 'Legacy lathe retained for rough turning operations and training new operators. Not used for production-grade components.', 'is_active' => false],
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
