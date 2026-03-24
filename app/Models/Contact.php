<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'person_name',
        'contact_no',
        'whatsapp_no',
        'upi_no',
        'account_holder_name',
        'account_no',
        'ifsc_code',
        'bank_name',
        'branch',
        'remarks',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
