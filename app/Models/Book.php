<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'publisher_id',
        'title',
        'isbn',
        'description',
        'price',
        'stock',
        'published_at',
        'is_active',
    ];

    protected $casts = [
        'publisher_id' => 'integer',
        'price' => 'decimal:2',
        'stock' => 'integer',
        'published_at' => 'date',
        'is_active' => 'boolean',
    ];

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'book_authors')
            ->using(BookAuthor::class)
            ->withPivot(['id', 'contribution_type', 'royalty_percentage'])
            ->withTimestamps();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'book_categories')
            ->using(BookCategory::class)
            ->withPivot(['id', 'is_primary'])
            ->withTimestamps();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'book_tags')
            ->using(BookTag::class)
            ->withPivot(['id'])
            ->withTimestamps();
    }

    public function bookAuthors(): HasMany
    {
        return $this->hasMany(BookAuthor::class);
    }

    public function bookCategories(): HasMany
    {
        return $this->hasMany(BookCategory::class);
    }

    public function bookTags(): HasMany
    {
        return $this->hasMany(BookTag::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}

