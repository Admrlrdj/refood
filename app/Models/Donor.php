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
        'phone',
        'email',
        'password',
        'restaurant_name',
        'last_latitude',
        'last_longitude',
    ];
    protected $hidden = ['password'];
}
