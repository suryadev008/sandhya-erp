<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationSeeder extends Seeder
{
    public function run(): void
    {
        $operations = [
            ['operation_name' => 'Turning', 'price' => 5.00, 'applicable_for' => 'lathe', 'remark' => 'Basic turning operation', 'is_active' => true],
            ['operation_name' => 'Facing', 'price' => 4.50, 'applicable_for' => 'lathe', 'remark' => 'Facing operation', 'is_active' => true],
            ['operation_name' => 'Boring', 'price' => 6.00, 'applicable_for' => 'lathe', 'remark' => 'Boring operation', 'is_active' => true],
            ['operation_name' => 'Threading', 'price' => 7.50, 'applicable_for' => 'lathe', 'remark' => 'Threading operation', 'is_active' => true],
            ['operation_name' => 'Knurling', 'price' => 5.50, 'applicable_for' => 'lathe', 'remark' => 'Knurling operation', 'is_active' => true],
            ['operation_name' => 'Grooving', 'price' => 4.00, 'applicable_for' => 'lathe', 'remark' => 'Grooving operation', 'is_active' => true],
            ['operation_name' => 'CNC Milling', 'price' => 12.00, 'applicable_for' => 'cnc', 'remark' => 'CNC milling operation', 'is_active' => true],
            ['operation_name' => 'CNC Drilling', 'price' => 8.00, 'applicable_for' => 'cnc', 'remark' => 'CNC drilling operation', 'is_active' => true],
            ['operation_name' => 'CNC Turning', 'price' => 10.00, 'applicable_for' => 'cnc', 'remark' => 'CNC turning operation', 'is_active' => true],
            ['operation_name' => 'CNC Tapping', 'price' => 9.00, 'applicable_for' => 'cnc', 'remark' => 'CNC tapping operation', 'is_active' => true],
            ['operation_name' => 'Finish Grinding', 'price' => 15.00, 'applicable_for' => 'both', 'remark' => 'Final finish grinding', 'is_active' => true],
            ['operation_name' => 'Quality Check', 'price' => 3.00, 'applicable_for' => 'both', 'remark' => 'Quality inspection operation', 'is_active' => false],
        ];

        foreach ($operations as $operation) {
            DB::table('operations')->insert([
                ...$operation,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
