<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'customer_id',
        'label',
        'line_one',
        'line_two',
        'city',
        'state',
        'postal_code',
        'country',
        'is_primary',
    ];

    protected $casts = [
        'customer_id' => 'integer',
        'is_primary' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}

