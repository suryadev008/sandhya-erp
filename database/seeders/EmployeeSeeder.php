<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employees = [
            // Lathe Employees
            [
                'emp_code' => 'EMP001',
                'name' => 'Raju Prasad',
                'aadhar_no' => '123456789012',
                'mobile_primary' => '9801234561',
                'mobile_secondary' => null,
                'whatsapp_no' => '9801234561',
                'upi_number' => 'raju@upi',
                'permanent_address' => 'Village Rampur, Jharkhand',
                'present_address' => 'Gamharia, Seraikela, Jharkhand',
                'aadhar_image' => null,
                'bank_account_no' => '1234567890',
                'bank_name' => 'SBI',
                'ifsc_code' => 'SBIN0001234',
                'employee_type' => 'lathe',
                'experience_years' => 5.0,
                'joining_date' => '2020-01-01',
                'status' => 'active',
                'created_by' => 1,
            ],
            [
                'emp_code' => 'EMP002',
                'name' => 'Mohan Singh',
                'aadhar_no' => '234567890123',
                'mobile_primary' => '9801234562',
                'mobile_secondary' => '9801234572',
                'whatsapp_no' => '9801234562',
                'upi_number' => null,
                'permanent_address' => 'Village Simdega, Jharkhand',
                'present_address' => 'Adityapur, Jharkhand',
                'aadhar_image' => null,
                'bank_account_no' => '2345678901',
                'bank_name' => 'Bank of India',
                'ifsc_code' => 'BKID0001234',
                'employee_type' => 'lathe',
                'experience_years' => 3.5,
                'joining_date' => '2021-03-15',
                'status' => 'active',
                'created_by' => 1,
            ],
            [
                'emp_code' => 'EMP003',
                'name' => 'Sanjay Kumar',
                'aadhar_no' => '345678901234',
                'mobile_primary' => '9801234563',
                'mobile_secondary' => null,
                'whatsapp_no' => '9801234563',
                'upi_number' => 'sanjay@ybl',
                'permanent_address' => 'Village Bokaro, Jharkhand',
                'present_address' => 'Gamharia, Jharkhand',
                'aadhar_image' => null,
                'bank_account_no' => '3456789012',
                'bank_name' => 'PNB',
                'ifsc_code' => 'PUNB0001234',
                'employee_type' => 'lathe',
                'experience_years' => 7.0,
                'joining_date' => '2018-06-01',
                'status' => 'active',
                'created_by' => 1,
            ],

            // CNC Employees
            [
                'emp_code' => 'EMP004',
                'name' => 'Vikash Mahto',
                'aadhar_no' => '456789012345',
                'mobile_primary' => '9801234564',
                'mobile_secondary' => null,
                'whatsapp_no' => '9801234564',
                'upi_number' => 'vikash@paytm',
                'permanent_address' => 'Village Ranchi, Jharkhand',
                'present_address' => 'Adityapur, Jharkhand',
                'aadhar_image' => null,
                'bank_account_no' => '4567890123',
                'bank_name' => 'HDFC',
                'ifsc_code' => 'HDFC0001234',
                'employee_type' => 'cnc',
                'experience_years' => 4.0,
                'joining_date' => '2020-07-01',
                'status' => 'active',
                'created_by' => 1,
            ],
            [
                'emp_code' => 'EMP005',
                'name' => 'Amit Sharma',
                'aadhar_no' => '567890123456',
                'mobile_primary' => '9801234565',
                'mobile_secondary' => '9801234575',
                'whatsapp_no' => '9801234575',
                'upi_number' => null,
                'permanent_address' => 'Village Dhanbad, Jharkhand',
                'present_address' => 'Gamharia, Jharkhand',
                'aadhar_image' => null,
                'bank_account_no' => '5678901234',
                'bank_name' => 'ICICI',
                'ifsc_code' => 'ICIC0001234',
                'employee_type' => 'cnc',
                'experience_years' => 6.5,
                'joining_date' => '2019-01-15',
                'status' => 'active',
                'created_by' => 1,
            ],

            // Both (Lathe + CNC)
            [
                'emp_code' => 'EMP006',
                'name' => 'Deepak Yadav',
                'aadhar_no' => '678901234567',
                'mobile_primary' => '9801234566',
                'mobile_secondary' => null,
                'whatsapp_no' => '9801234566',
                'upi_number' => 'deepak@gpay',
                'permanent_address' => 'Village Hazaribagh, Jharkhand',
                'present_address' => 'Adityapur, Jharkhand',
                'aadhar_image' => null,
                'bank_account_no' => '6789012345',
                'bank_name' => 'Axis Bank',
                'ifsc_code' => 'UTIB0001234',
                'employee_type' => 'both',
                'experience_years' => 8.0,
                'joining_date' => '2017-04-01',
                'status' => 'active',
                'created_by' => 1,
            ],

            // Inactive Employee
            [
                'emp_code' => 'EMP007',
                'name' => 'Suresh Oraon',
                'aadhar_no' => '789012345678',
                'mobile_primary' => '9801234567',
                'mobile_secondary' => null,
                'whatsapp_no' => null,
                'upi_number' => null,
                'permanent_address' => 'Village Gumla, Jharkhand',
                'present_address' => 'Gamharia, Jharkhand',
                'aadhar_image' => null,
                'bank_account_no' => null,
                'bank_name' => null,
                'ifsc_code' => null,
                'employee_type' => 'lathe',
                'experience_years' => 2.0,
                'joining_date' => '2022-01-01',
                'status' => 'inactive',
                'created_by' => 1,
            ],
        ];

        foreach ($employees as $employee) {
            DB::table('employees')->insert([
                ...$employee,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
