<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'phone',
        'address',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    /**
     * Relations
     */
    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class);
    }

    /**
     * Scopes
     */
    public function scopeOnlyCustomers($q)
    {
        // If a "customer" role exists, limit to it; otherwise return all.
        try {
            $customerRoleId = \App\Models\Role::query()
                ->where('role_name', 'customer')
                ->value('id');

            if ($customerRoleId) {
                return $q->where('role_id', $customerRoleId);
            }
        } catch (\Throwable $e) {
            // fall through to return $q
        }
        return $q;
    }

    public function scopeSearch($q, ?string $term)
    {
        if (!$term) return $q;

        $like = "%{$term}%";
        return $q->where(function ($qq) use ($like, $term) {
            $qq->where('id', $term)
               ->orWhere('name', 'like', $like)
               ->orWhere('email', 'like', $like)
               ->orWhere('phone', 'like', $like)
               ->orWhere('address', 'like', $like);
        });
    }

    /**
     * Mutators
     */
    public function setPasswordAttribute($value): void
    {
        if (empty($value)) {
            unset($this->attributes['password']);
            return;
        }

        // Avoid double-hashing
        $this->attributes['password'] = Hash::needsRehash($value)
            ? Hash::make($value)
            : $value;
    }
}
