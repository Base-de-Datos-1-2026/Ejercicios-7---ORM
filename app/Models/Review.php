<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'customer_id',
        'book_id',
        'rating',
        'title',
        'body',
        'is_verified_purchase',
        'reviewed_at',
    ];

    protected $casts = [
        'customer_id' => 'integer',
        'book_id' => 'integer',
        'rating' => 'integer',
        'is_verified_purchase' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}

