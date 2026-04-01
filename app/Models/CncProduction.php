<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CncProduction extends Model
{
    protected $fillable = [
        'employee_id',
        'machine_id',
        'date',
        'shift',
        'company_id',
        'job_name',
        'part_id',
        'operation_type',
        'actual_cycle_time',
        'production_qty',
        'target_qty',
        'incentive_qty',
        'rate_per_piece',
        'incentive_rate',
        'amount',
        'target_met',
        'downtime_type',
        'downtime_minutes',
        'is_sunday',
        'is_half_day',
        'remark',
        'created_by',
    ];

    protected $casts = [
        'date'           => 'date',
        'target_met'     => 'boolean',
        'is_sunday'      => 'boolean',
        'is_half_day'    => 'boolean',
        'rate_per_piece' => 'decimal:2',
        'incentive_rate' => 'decimal:2',
        'amount'         => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────────────────

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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    /**
     * Calculate amount based on employee payment type.
     * day_rate:  incentive_qty × incentive_rate
     * per_piece: production_qty × rate_per_piece
     */
    public function calculateAmount(): float
    {
        $employee = $this->employee;
        if (!$employee) return 0.0;

        if ($employee->cnc_payment_type === 'per_piece') {
            return (float) $this->production_qty * (float) $this->rate_per_piece;
        }

        // day_rate: only incentive portion stored here; base salary handled at payroll level
        return (float) $this->incentive_qty * (float) $this->incentive_rate;
    }

    public static function operationTypeLabels(): array
    {
        return [
            'full_finish'         => 'Full Finish',
            'finish_first_side'   => 'Finish – First Side',
            'finish_second_side'  => 'Finish – Second Side',
        ];
    }
}
