<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerCompanyBankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_company_id',
        'bank_name',
        'account_number',
        'ifsc_code',
        'account_type',
        'branch_name',
        'swift_code',
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
