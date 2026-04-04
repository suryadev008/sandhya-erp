<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactCategory extends Model
{
    protected $fillable = ['name', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'contact_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
