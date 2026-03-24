<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperationPrice extends Model
{
    protected $fillable = [
        'operation_id',
        'price',
        'applicable_from',
        'remark',
        'created_by',
    ];

    protected $casts = [
        'applicable_from' => 'date',
        'price'           => 'decimal:2',
    ];

    public function operation()
    {
        return $this->belongsTo(Operation::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
