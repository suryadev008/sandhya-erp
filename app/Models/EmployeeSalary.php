<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    protected $fillable = [
        'employee_id',
        'per_day',
        'per_month',
        'effect_from',
        'remark',
        'created_by',
    ];

    protected $casts = [
        'effect_from' => 'date',
        'per_day'     => 'decimal:2',
        'per_month'   => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
