<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'person_name',
        'area',
        'contact_category_id',
        'upi_no',
        'account_holder_name',
        'account_no',
        'ifsc_code',
        'bank_name',
        'branch',
        'remarks',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function category()
    {
        return $this->belongsTo(ContactCategory::class, 'contact_category_id');
    }

    public function phones()
    {
        return $this->hasMany(ContactPhone::class)->orderByDesc('is_primary');
    }

    public function primaryPhone()
    {
        return $this->hasOne(ContactPhone::class)->where('is_primary', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
