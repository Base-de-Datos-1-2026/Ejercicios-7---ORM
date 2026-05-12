<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookAuthor extends Pivot
{
    protected $table = 'book_authors';

    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'book_id',
        'author_id',
        'contribution_type',
        'royalty_percentage',
    ];

    protected $casts = [
        'book_id' => 'integer',
        'author_id' => 'integer',
        'royalty_percentage' => 'decimal:2',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
