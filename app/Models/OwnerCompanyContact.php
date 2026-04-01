<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerCompanyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_company_id',
        'contact_person',
        'designation',
        'phone',
        'alternate_phone',
        'email',
        'support_email',
        'is_primary',
        'is_active',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'is_active'  => 'boolean',
    ];

    public function ownerCompany()
    {
        return $this->belongsTo(OwnerCompany::class, 'owner_company_id');
    }
}
