<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeOperationRate extends Model
{
    protected $fillable = [
        'employee_id',
        'operation_id',
        'rate',
        'applicable_from',
        'remark',
        'created_by',
    ];

    protected $casts = [
        'applicable_from' => 'date',
        'rate'            => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────────

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Scopes ────────────────────────────────────────────────────────────

    /**
     * Get the effective rate for an employee+operation on a given date.
     * Falls back to 0 if no record exists.
     */
    public static function rateFor(int $employeeId, int $operationId, string $date): float
    {
        $rate = static::where('employee_id', $employeeId)
            ->where('operation_id', $operationId)
            ->where('applicable_from', '<=', $date)
            ->orderBy('applicable_from', 'desc')
            ->value('rate');

        return $rate !== null ? (float) $rate : 0.0;
    }
}
