<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LatheProduction extends Model
{
    protected $fillable = [
        'employee_id',
        'machine_id',
        'date',
        'shift',
        'company_id',
        'part_id',
        'operation_id',
        'qty',
        'rate',
        'amount',
        'remarks',
        'downtime_type',
        'downtime_minutes',
        'is_half_day',
        'created_by',
    ];

    protected $casts = [
        'date'       => 'date',
        'qty'        => 'integer',
        'rate'       => 'decimal:2',
        'amount'     => 'decimal:2',
        'is_half_day' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
