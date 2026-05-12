<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    protected $fillable = [
        'name',
        'slug',
    ];

    protected $casts = [];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_tags')
            ->using(BookTag::class)
            ->withPivot(['id'])
            ->withTimestamps();
    }

    public function bookTags(): HasMany
    {
        return $this->hasMany(BookTag::class);
    }
}

