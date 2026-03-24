<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'company_id',
        'operation_name',
        'applicable_for',
        'remark',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /** All price history records, newest first */
    public function prices()
    {
        return $this->hasMany(OperationPrice::class)->orderBy('applicable_from', 'desc');
    }

    /** Current applicable price — latest record where applicable_from <= today */
    public function currentPrice()
    {
        return $this->hasOne(OperationPrice::class)
            ->where('applicable_from', '<=', now()->toDateString())
            ->orderBy('applicable_from', 'desc');
    }

    /** Get price applicable on a specific date */
    public function priceOn(string $date): float
    {
        $record = $this->prices()
            ->where('applicable_from', '<=', $date)
            ->first();

        return $record ? (float) $record->price : 0.0;
    }
}
