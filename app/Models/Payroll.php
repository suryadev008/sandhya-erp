<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'total_working_days',
        'present_days',
        'sunday_half_days',
        'total_lathe_amount',
        'total_cnc_days',
        'cnc_rate_per_day',
        'total_cnc_amount',
        'extra_payment_total',
        'gross_amount',
        'deductions',
        'deduction_remarks',
        'net_amount',
        'status',
        'generated_by',
        'approved_by',
        'generated_at',
        'approved_at',
        'paid_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'approved_at'  => 'datetime',
        'paid_at'      => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function extraPayments()
    {
        return $this->hasMany(ExtraPayment::class);
    }

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getMonthLabelAttribute(): string
    {
        return date('F', mktime(0, 0, 0, $this->month, 1, $this->year));
    }
}
