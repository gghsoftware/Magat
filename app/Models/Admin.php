<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'users'; // same table
    protected $fillable = ['role_id', 'name', 'email', 'password', 'phone', 'address', 'status'];
    protected $hidden = ['password', 'remember_token'];
}
