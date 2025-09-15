<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['role_name'];

    /**
     * A role has many users.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Convenience scope: find role by name easily.
     */
    public function scopeNamed($query, string $name)
    {
        return $query->where('role_name', $name);
    }

    /**
     * Helper: check if this role is "customer".
     */
    public function isCustomer(): bool
    {
        return strtolower($this->role_name) === 'customer';
    }
}
