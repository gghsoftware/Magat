<?php

// app/Models/Package.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Package extends Model
{
    protected $fillable = ['name', 'slug', 'price', 'thumbnail', 'inclusions', 'gallery', 'is_active'];
    protected $casts = [
        'inclusions' => 'array',
        'gallery'    => 'array',
        'is_active'  => 'boolean',
        'price'      => 'integer', // or 'decimal:2' if you prefer
    ];

    // Helpers
    public function getCanUpgradeGardenAttribute(): bool
    {
        return $this->price <= 90000;
    }
    public function getHasFreeAddonsAttribute(): bool
    {
        return $this->price == 90000;
    }

    // Auto slug on create if missing
    protected static function booted()
    {
        static::creating(function ($p) {
            if (!$p->slug) $p->slug = Str::slug($p->name);
        });
    }
}
