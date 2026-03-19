<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartSeeder extends Seeder
{
    public function run(): void
    {
        // Company IDs (CompanySeeder के बाद run होगा)
        // 1=Tata, 2=Bosch, 3=Mahindra, 4=Bajaj, 5=Hero

        $parts = [
            // Tata Motors Parts
            ['company_id' => 1, 'part_number' => 'TM-001', 'part_name' => 'Engine Shaft',       'description' => 'Main engine shaft for truck',        'is_active' => true],
            ['company_id' => 1, 'part_number' => 'TM-002', 'part_name' => 'Gear Box Housing',   'description' => 'Gear box housing component',          'is_active' => true],
            ['company_id' => 1, 'part_number' => 'TM-003', 'part_name' => 'Brake Drum',         'description' => 'Rear brake drum',                     'is_active' => true],
            ['company_id' => 1, 'part_number' => 'TM-004', 'part_name' => 'Axle Shaft',         'description' => 'Front axle shaft',                    'is_active' => false],

            // Bosch Parts
            ['company_id' => 2, 'part_number' => 'BS-001', 'part_name' => 'Fuel Injector Body', 'description' => 'Fuel injector housing',               'is_active' => true],
            ['company_id' => 2, 'part_number' => 'BS-002', 'part_name' => 'Pump Shaft',         'description' => 'Hydraulic pump shaft',                'is_active' => true],
            ['company_id' => 2, 'part_number' => 'BS-003', 'part_name' => 'Valve Body',         'description' => 'Control valve body',                  'is_active' => true],

            // Mahindra Parts
            ['company_id' => 3, 'part_number' => 'MM-001', 'part_name' => 'Camshaft',           'description' => 'Engine camshaft',                     'is_active' => true],
            ['company_id' => 3, 'part_number' => 'MM-002', 'part_name' => 'Crankshaft',         'description' => 'Engine crankshaft',                   'is_active' => true],
            ['company_id' => 3, 'part_number' => 'MM-003', 'part_name' => 'Flywheel',           'description' => 'Engine flywheel assembly',            'is_active' => false],

            // Bajaj Parts
            ['company_id' => 4, 'part_number' => 'BJ-001', 'part_name' => 'Piston Pin',        'description' => 'Gudgeon pin / piston pin',            'is_active' => true],
            ['company_id' => 4, 'part_number' => 'BJ-002', 'part_name' => 'Connecting Rod',    'description' => 'Engine connecting rod',               'is_active' => true],

            // Hero Parts
            ['company_id' => 5, 'part_number' => 'HR-001', 'part_name' => 'Sprocket',          'description' => 'Chain sprocket front',                'is_active' => true],
            ['company_id' => 5, 'part_number' => 'HR-002', 'part_name' => 'Disc Rotor',        'description' => 'Front disc brake rotor',              'is_active' => true],
        ];

        foreach ($parts as $part) {
            DB::table('parts')->insert([
                ...$part,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
