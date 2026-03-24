<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineType extends Model
{
    protected $fillable = [
        'type_name',
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

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }
}
