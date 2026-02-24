<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Donor extends Authenticatable
{
    use HasApiTokens;

    protected $connection = 'mongodb';
    protected $collection = 'donors';

    protected $fillable = [
        'name',
        'username',
        'password',
        'phone',
        'email',
        'address',
        'restaurant_name',
        'is_verified'
    ];
    protected $hidden = ['password'];
}
