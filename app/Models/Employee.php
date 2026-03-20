<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'emp_code',
        'name',
        'aadhar_no',
        'mobile_primary',
        'mobile_secondary',
        'whatsapp_no',
        'upi_number',
        'permanent_address',
        'present_address',
        'bank_account_no',
        'bank_name',
        'ifsc_code',
        'employee_type',
        'experience_years',
        'joining_date',
        'status',
        'aadhar_image',
    ];

    protected $casts = [
        'joining_date' => 'date',
    ];

    public function salaries()
    {
        return $this->hasMany(EmployeeSalary::class)->orderBy('effect_from', 'desc');
    }

    /** Current active salary — latest record where effect_from <= today */
    public function currentSalary()
    {
        return $this->hasOne(EmployeeSalary::class)
            ->where('effect_from', '<=', now()->toDateString())
            ->orderBy('effect_from', 'desc');
    }
}
