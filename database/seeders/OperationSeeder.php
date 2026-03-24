<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationSeeder extends Seeder
{
    public function run(): void
    {
        $defaultCompanyId = DB::table('companies')->value('id');

        $operations = [
            ['operation_name' => 'Turning',        'applicable_for' => 'lathe', 'remark' => 'Basic turning operation', 'is_active' => true,  'price' => 5.00],
            ['operation_name' => 'Facing',          'applicable_for' => 'lathe', 'remark' => 'Facing operation',        'is_active' => true,  'price' => 4.50],
            ['operation_name' => 'Boring',          'applicable_for' => 'lathe', 'remark' => 'Boring operation',        'is_active' => true,  'price' => 6.00],
            ['operation_name' => 'Threading',       'applicable_for' => 'lathe', 'remark' => 'Threading operation',     'is_active' => true,  'price' => 7.50],
            ['operation_name' => 'Knurling',        'applicable_for' => 'lathe', 'remark' => 'Knurling operation',      'is_active' => true,  'price' => 5.50],
            ['operation_name' => 'Grooving',        'applicable_for' => 'lathe', 'remark' => 'Grooving operation',      'is_active' => true,  'price' => 4.00],
            ['operation_name' => 'CNC Milling',     'applicable_for' => 'cnc',   'remark' => 'CNC milling operation',  'is_active' => true,  'price' => 12.00],
            ['operation_name' => 'CNC Drilling',    'applicable_for' => 'cnc',   'remark' => 'CNC drilling operation', 'is_active' => true,  'price' => 8.00],
            ['operation_name' => 'CNC Turning',     'applicable_for' => 'cnc',   'remark' => 'CNC turning operation',  'is_active' => true,  'price' => 10.00],
            ['operation_name' => 'CNC Tapping',     'applicable_for' => 'cnc',   'remark' => 'CNC tapping operation',  'is_active' => true,  'price' => 9.00],
            ['operation_name' => 'Finish Grinding', 'applicable_for' => 'both',  'remark' => 'Final finish grinding',  'is_active' => true,  'price' => 15.00],
            ['operation_name' => 'Quality Check',   'applicable_for' => 'both',  'remark' => 'Quality inspection',     'is_active' => false, 'price' => 3.00],
        ];

        foreach ($operations as $op) {
            $opId = DB::table('operations')->insertGetId([
                'company_id'     => $defaultCompanyId,
                'operation_name' => $op['operation_name'],
                'applicable_for' => $op['applicable_for'],
                'remark'         => $op['remark'],
                'is_active'      => $op['is_active'],
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            DB::table('operation_prices')->insert([
                'operation_id'   => $opId,
                'price'          => $op['price'],
                'applicable_from'=> now()->toDateString(),
                'remark'         => 'Initial price',
                'created_by'     => null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
