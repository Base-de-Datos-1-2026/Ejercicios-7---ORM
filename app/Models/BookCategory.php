<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookCategory extends Pivot
{
    protected $table = 'book_categories';

    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'book_id',
        'category_id',
        'is_primary',
    ];

    protected $casts = [
        'book_id' => 'integer',
        'category_id' => 'integer',
        'is_primary' => 'boolean',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
