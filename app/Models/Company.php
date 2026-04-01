<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'company_name',
        'plant_name',
        'contact_person',
        'contact_phone',
        'address',
        'remark',
        'is_active',
        'gst_no',
        'gst_trade_name',
        'gst_legal_name',
        'gst_status',
        'gst_state',
        'gst_pan',
        'gst_registration_date',
        'gst_business_type',
        'gst_verified_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
