<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    protected $fillable = [
        'name',
        'country',
        'birth_date',
        'bio',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_authors')
            ->using(BookAuthor::class)
            ->withPivot(['id', 'contribution_type', 'royalty_percentage'])
            ->withTimestamps();
    }

    public function bookAuthors(): HasMany
    {
        return $this->hasMany(BookAuthor::class);
    }
}

