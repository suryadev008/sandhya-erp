<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'machine_name',
        'machine_number',
        'machine_type_id',
        'description',
        'working',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function machineType()
    {
        return $this->belongsTo(MachineType::class);
    }
}
