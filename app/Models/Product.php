<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock',
        'image_url',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    protected $appends = ['image_src'];


    /** Relationships */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /** Computed helpers used by Blade */
    public function getInStockAttribute(): bool
    {
        // in stock if has stock and marked available
        return $this->status === 'available' && $this->stock > 0;
    }

    public function getIsNewAttribute(): bool
    {
        // Show "New" badge for items created within last 30 days
        return optional($this->created_at)->gt(now()->subDays(30));
    }

    /** 🔧 Image URL accessor (always returns a usable URL) */
    public function getImageSrcAttribute(): string
    {
        $val = trim((string) $this->image_url);
        if (!$val) {
            return asset('images/placeholder.jpg');
        }

        // Absolute URL (CDN/external)
        if (Str::startsWith($val, ['http://', 'https://'])) {
            return $val;
        }

        // Normalize common saved formats
        $rel = ltrim($val, '/');                 // remove leading slash
        $rel = str_replace('\\', '/', $rel);     // windows -> unix
        if (Str::startsWith($rel, 'storage/')) {
            $rel = Str::after($rel, 'storage/'); // keep "products/xxx.jpg"
        }

        // Serve via public storage symlink
        return asset('storage/' . $rel);
    }

    /** Scopes for filters */
    public function scopeSearch(Builder $q, ?string $term): Builder
    {
        if (!$term) return $q;
        return $q->where(
            fn($qq) =>
            $qq->where('name', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%")
        );
    }

    public function scopeCategoryName(Builder $q, ?string $categoryName): Builder
    {
        if (!$categoryName || $categoryName === 'All') return $q;
        return $q->whereHas('category', fn($cq) => $cq->where('name', $categoryName));
    }

    public function scopePriceBetween(Builder $q, ?int $min, ?int $max): Builder
    {
        if (!is_null($min)) $q->where('price', '>=', $min);
        if (!is_null($max)) $q->where('price', '<=', $max);
        return $q;
    }

    public function scopeAvailability(Builder $q, $inStock, $outOfStock): Builder
    {
        if ($inStock && !$outOfStock) {
            return $q->where('status', 'available')->where('stock', '>', 0);
        }
        if ($outOfStock && !$inStock) {
            return $q->where(
                fn($qq) =>
                $qq->where('status', 'unavailable')->orWhere('stock', '<=', 0)
            );
        }
        return $q; // both or none -> no filter
    }
}
