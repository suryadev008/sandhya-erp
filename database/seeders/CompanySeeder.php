<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            ['company_name' => 'Tata Motors Ltd',       'plant_name' => 'Jamshedpur Plant',  'contact_person' => 'Ramesh Kumar',   'contact_phone' => '9876543210', 'address' => 'Jamshedpur, Jharkhand',  'remark' => 'Main client',        'is_active' => true],
            ['company_name' => 'Bosch India Ltd',       'plant_name' => 'Pune Plant',        'contact_person' => 'Sunil Sharma',   'contact_phone' => '9876543211', 'address' => 'Pune, Maharashtra',     'remark' => 'Auto parts supplier', 'is_active' => true],
            ['company_name' => 'Mahindra & Mahindra',   'plant_name' => 'Nashik Plant',      'contact_person' => 'Anil Gupta',     'contact_phone' => '9876543212', 'address' => 'Nashik, Maharashtra',   'remark' => null,                 'is_active' => true],
            ['company_name' => 'Bajaj Auto Ltd',        'plant_name' => 'Aurangabad Plant',  'contact_person' => 'Vijay Patil',    'contact_phone' => '9876543213', 'address' => 'Aurangabad, MH',        'remark' => 'Two wheeler parts',  'is_active' => true],
            ['company_name' => 'Hero MotoCorp',         'plant_name' => 'Dharuhera Plant',   'contact_person' => 'Rakesh Singh',   'contact_phone' => '9876543214', 'address' => 'Dharuhera, Haryana',    'remark' => null,                 'is_active' => true],
            ['company_name' => 'Ashok Leyland',         'plant_name' => 'Hosur Plant',       'contact_person' => 'Praveen Nair',   'contact_phone' => '9876543215', 'address' => 'Hosur, Tamil Nadu',     'remark' => 'Heavy vehicle parts', 'is_active' => true],
            ['company_name' => 'Maruti Suzuki India',   'plant_name' => 'Manesar Plant',     'contact_person' => 'Deepak Verma',   'contact_phone' => '9876543216', 'address' => 'Manesar, Haryana',      'remark' => null,                 'is_active' => false],
        ];

        foreach ($companies as $company) {
            DB::table('companies')->insert([
                ...$company,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
