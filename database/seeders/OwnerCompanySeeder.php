<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OwnerCompany;
use App\Models\OwnerCompanyBankAccount;
use App\Models\OwnerCompanyContact;

class OwnerCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = OwnerCompany::create([
            'company_name' => 'Stark Solutions Pvt Ltd',
            'company_code' => 'SSPL',
            'company_type' => 'pvt_ltd',
            'pan_number' => 'ABCDE1234F',
            'gstin' => '27ABCDE1234F1Z5',
            'incorporation_date' => '2015-05-15',
            'financial_year_start' => 'april',
            'base_currency' => 'INR',
            'timezone' => 'Asia/Kolkata',
            'date_format' => 'd/m/Y',
            'invoice_prefix' => 'SSPL-',
            'tax_regime' => 'old',
            'reg_address_line1' => '123 Stark Tower',
            'reg_city' => 'Mumbai',
            'reg_state' => 'Maharashtra',
            'reg_pincode' => '400001',
            'reg_country' => 'India',
            'industry_type' => 'IT',
            'website' => 'https://stark-solutions.com',
            'is_active' => true,
        ]);

        // Banks
        $company->bankAccounts()->create([
            'bank_name' => 'HDFC Bank',
            'account_number' => '50100234567891',
            'ifsc_code' => 'HDFC0001234',
            'account_type' => 'current',
            'branch_name' => 'Fort Branch',
            'is_primary' => true,
        ]);

        $company->bankAccounts()->create([
            'bank_name' => 'ICICI Bank',
            'account_number' => '000123456789',
            'ifsc_code' => 'ICIC0000001',
            'account_type' => 'current',
            'branch_name' => 'Nariman Point',
            'is_primary' => false,
        ]);

        // Contacts
        $company->contacts()->create([
            'contact_person' => 'Tony Stark',
            'designation' => 'CEO',
            'phone' => '9876543210',
            'email' => 'tony@stark-solutions.com',
            'support_email' => 'support@stark-solutions.com',
            'is_primary' => true,
        ]);

        $company->contacts()->create([
            'contact_person' => 'Pepper Potts',
            'designation' => 'Director',
            'phone' => '9876543211',
            'email' => 'pepper@stark-solutions.com',
            'is_primary' => false,
        ]);
    }
}
