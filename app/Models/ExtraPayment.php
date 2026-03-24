<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExtraPayment extends Model
{
    protected $fillable = [
        'payroll_id',
        'employee_id',
        'month',
        'year',
        'payment_name',
        'amount',
        'created_by',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
