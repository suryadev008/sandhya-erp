<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class OwnerCompany extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_name',
        'company_code',
        'company_type',
        'pan_number',
        'gstin',
        'incorporation_date',
        'financial_year_start',
        'base_currency',
        'timezone',
        'date_format',
        'invoice_prefix',
        'tax_regime',
        'reg_address_line1',
        'reg_city',
        'reg_state',
        'reg_pincode',
        'reg_country',
        'industry_type',
        'logo_path',
        'website',
        'cin_number',
        'tan_number',
        'msme_reg_no',
        'roc',
        'corp_address_line1',
        'corp_address_line2',
        'corp_city',
        'corp_state',
        'corp_pincode',
        'is_multi_branch',
        'authorized_capital',
        'paid_up_capital',
        'num_directors',
        'auditor_name',
        'auditor_firm',
        'cs_name',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'incorporation_date' => 'date',
        'is_multi_branch'    => 'boolean',
        'is_active'          => 'boolean',
        'num_directors'      => 'integer',
        'authorized_capital' => 'decimal:2',
        'paid_up_capital'    => 'decimal:2',
    ];

    // Accessors
    public function getLogoUrlAttribute()
    {
        if ($this->logo_path) {
            return Storage::url($this->logo_path);
        }
        return null;
    }

    // Relationships
    public function bankAccounts()
    {
        return $this->hasMany(OwnerCompanyBankAccount::class, 'owner_company_id');
    }

    public function primaryBank()
    {
        return $this->hasOne(OwnerCompanyBankAccount::class, 'owner_company_id')->where('is_primary', true);
    }

    public function contacts()
    {
        return $this->hasMany(OwnerCompanyContact::class, 'owner_company_id');
    }

    public function primaryContact()
    {
        return $this->hasOne(OwnerCompanyContact::class, 'owner_company_id')->where('is_primary', true);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
