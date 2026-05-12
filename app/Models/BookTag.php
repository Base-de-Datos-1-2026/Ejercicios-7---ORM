<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class BookTag extends Pivot
{
    protected $table = 'book_tags';

    public $incrementing = true;

    public $timestamps = true;

    protected $fillable = [
        'book_id',
        'tag_id',
    ];

    protected $casts = [
        'book_id' => 'integer',
        'tag_id' => 'integer',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
