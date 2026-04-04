<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
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
        'bank_branch',
        'ifsc_code',
        'employee_type',
        'cnc_payment_type',
        'cnc_target_per_shift',
        'cnc_incentive_rate',
        'experience_years',
        'joining_date',
        'status',
        'aadhar_image',
    ];

    protected $casts = [
        'joining_date'         => 'date',
        'cnc_incentive_rate'   => 'decimal:2',
        'cnc_target_per_shift' => 'integer',
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

    /** All employee-specific operation rates */
    public function operationRates()
    {
        return $this->hasMany(EmployeeOperationRate::class)->orderBy('applicable_from', 'desc');
    }

    /** Check if this employee uses CNC production */
    public function isCnc(): bool
    {
        return in_array($this->employee_type, ['cnc', 'both']);
    }

    /** Check if this employee uses Lathe production */
    public function isLathe(): bool
    {
        return in_array($this->employee_type, ['lathe', 'both']);
    }
}
