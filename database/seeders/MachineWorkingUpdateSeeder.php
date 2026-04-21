<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MachineWorkingUpdateSeeder extends Seeder
{
    public function run(): void
    {
        $workingData = [
            'LT-001'  => 'Used for turning raw bar stock into precision cylindrical components. Handles shafts, bushings, and flanges up to 300mm diameter.',
            'LT-002'  => 'Dedicated to finish turning and facing operations for medium-batch production. Primarily used for coupling and adapter components.',
            'CNC-001' => 'High-precision CNC turning center for complex profiles. Used for production of valve bodies, spindles, and threaded components with tight tolerances (±0.01mm).',
            'CNC-002' => 'CNC milling for flat surfaces, slots, and keyways. Used for machining gear blanks, brackets, and housing components.',
            'CNC-003' => 'Backup CNC machine currently under maintenance. Scheduled for use in overflow production when primary CNC machines are at capacity.',
            'DR-001'  => 'Radial drilling for hole-making operations on large workpieces. Used for drilling bolt hole patterns on flanges and mounting plates.',
            'DR-002'  => 'Bench drill for small-diameter holes and spot-facing operations. Handles drill sizes from 2mm to 25mm.',
            'TP-001'  => 'Semi-automatic tapping machine for internal threading. Used for M6 to M20 threads on steel and aluminum components. Processes approx. 150 pieces/hour.',
            'TP-002'  => 'Manual tapping stand for special or non-standard thread forms. Used for prototype and low-volume jobs requiring custom thread profiles.',
            'LT-003'  => 'Legacy lathe retained for rough turning operations and training new operators. Not used for production-grade components.',
        ];

        foreach ($workingData as $machineNumber => $working) {
            DB::table('machines')
                ->where('machine_number', $machineNumber)
                ->update(['working' => $working, 'updated_at' => now()]);
        }

        $this->command->info('Machine working/use-case data updated successfully.');
    }
}
