<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactPhone extends Model
{
    protected $fillable = ['contact_id', 'phone_number', 'label', 'is_primary'];

    protected $casts = ['is_primary' => 'boolean'];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
