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
        'status',
        'type',
        'effective_price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'effective_price' => 'decimal:2',
    ];

    // --- Composition ---
    // Products included in this package
    public function components()
    {
        return $this->belongsToMany(Product::class, 'package_items', 'package_id', 'product_id')
            ->withPivot(['quantity', 'price_override', 'is_optional'])
            ->withTimestamps();
    }

    // Packages that include this (inverse)
    public function includedInPackages()
    {
        return $this->belongsToMany(Product::class, 'package_items', 'product_id', 'package_id')
            ->withPivot(['quantity', 'price_override', 'is_optional'])
            ->withTimestamps();
    }

    // --- Type helpers ---
    public function getIsPackageAttribute(): bool
    {
        return $this->type === 'package';
    }

    public function getListPriceAttribute(): float
    {
        // Use effective_price for package (precomputed), else normal price
        return (float) ($this->effective_price ?? $this->price ?? 0);
    }

    // --- Compute total (server-side) ---
    public function computeEffectivePrice(): float
    {
        if (!$this->is_package) {
            return (float) $this->price;
        }

        $this->loadMissing('components');
        $sum = 0;
        foreach ($this->components as $child) {
            $unit = $child->pivot->price_override ?? $child->price ?? 0;
            $qty  = max(1, (int) $child->pivot->quantity);
            $sum += $unit * $qty;
        }
        return round($sum, 2);
    }

    protected static function booted()
    {
        // Any time a package or its composition changes, keep effective_price in sync.
        static::saved(function (Product $product) {
            if ($product->type === 'package') {
                $product->effective_price = $product->computeEffectivePrice();
                $product->saveQuietly();
            }
        });
    }

    // --- Filters (make them package-aware) ---
    public function scopePriceBetweenEffective(Builder $q, ?int $min, ?int $max): Builder
    {
        if (!is_null($min)) $q->whereRaw('COALESCE(effective_price, price) >= ?', [$min]);
        if (!is_null($max)) $q->whereRaw('COALESCE(effective_price, price) <= ?', [$max]);
        return $q;
    }

    public function scopeOrderByListPrice(Builder $q, string $dir = 'asc'): Builder
    {
        return $q->orderByRaw('COALESCE(effective_price, price) ' . ($dir === 'desc' ? 'DESC' : 'ASC'));
    }

    public function scopePackages(Builder $q): Builder
    {
        return $q->where('type', 'package');
    }

    // --- Stock for packages (required items must be in stock) ---
    public function getInStockAttribute(): bool
    {
        if ($this->is_package) {
            $this->loadMissing('components');
            foreach ($this->components as $child) {
                if (!$child->pivot->is_optional) {
                    // Use the child’s own simple in-stock logic:
                    $ok = ($child->status === 'available' && $child->stock > 0);
                    if (!$ok) return false;
                }
            }
            return true;
        }

        // original logic for simple products
        return $this->status === 'available' && $this->stock > 0;
    }
}
