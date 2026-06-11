<?php
// app/Models/Book.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'publisher',
        'published_year',
        'category',
        'synopsis',
        'total_copies',
        'available_copies',
        'shelf_location',
        'shelf_section',
        'pages',
        'language',
        'cover_image',
        'is_ebook',
        'rating',
        'rating_count',
    ];

    protected $casts = [
        'is_ebook' => 'boolean',
        'rating' => 'float',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function activeLoans()
    {
        return $this->hasMany(Loan::class)->whereIn('status', ['borrowed', 'overdue']);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->available_copies > 0;
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->is_ebook) return 'E-BOOK';
        if ($this->available_copies <= 0) return 'BORROWED';
        return 'AVAILABLE';
    }

    public function scopeAvailable($query)
    {
        return $query->where('available_copies', '>', 0);
    }

    public function scopeByCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }
        return $query;
    }

    public function scopeSearch($query, $term)
    {
        if ($term) {
            return $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('author', 'like', "%{$term}%")
                    ->orWhere('isbn', 'like', "%{$term}%");
            });
        }
        return $query;
    }

    public static function getCategories(): array
    {
        return [
            'Science Fiction',
            'Computer Science',
            'Philosophy',
            'Literature',
            'History',
            'Architecture',
            'Non-Fiction',
            'Other'
        ];
    }
}
