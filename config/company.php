<?php

return [
    'types' => [
        'pvt_ltd'     => 'Private Limited',
        'public_ltd'  => 'Public Limited',
        'llp'         => 'Limited Liability Partnership',
        'partnership' => 'Partnership',
        'sole_prop'   => 'Sole Proprietorship',
    ],
    'industry_types' => [
        'Manufacturing',
        'IT',
        'Retail',
        'Healthcare',
        'Finance',
        'Education',
        'Real Estate',
        'Construction',
        'Logistics',
        'Agriculture',
        'Other',
    ],
    'currencies' => [
        'INR' => 'Indian Rupee (INR)',
        'USD' => 'US Dollar (USD)',
        'EUR' => 'Euro (EUR)',
        'AED' => 'UAE Dirham (AED)',
        'GBP' => 'British Pound (GBP)',
    ],
    'timezones' => [
        'Asia/Kolkata' => 'Asia/Kolkata (IST)',
        'UTC'          => 'UTC',
        'America/New_York' => 'America/New_York (EST)',
        'Europe/London'  => 'Europe/London (GMT)',
    ],
    // Machine type name used to filter lathe machines in production module
    'lathe_machine_type' => 'lathe',

    // Covering all 28 states and 8 union territories of India
    'states' => [
        'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 
        'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka', 
        'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram', 
        'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu', 
        'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
        'Andaman and Nicobar Islands', 'Chandigarh', 'Dadra and Nagar Haveli and Daman and Diu', 
        'Delhi', 'Lakshadweep', 'Puducherry', 'Jammu and Kashmir', 'Ladakh'
    ],
];
