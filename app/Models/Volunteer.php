<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Volunteer extends Authenticatable
{
    use HasApiTokens;

    protected $connection = 'mongodb';
    protected $collection = 'volunteers';

    protected $fillable = [
        'name',
        'username',
        'password',
        'phone',
        'vehicle_type',
        'last_latitude',
        'last_longitude',
        'status',
        'is_verified'
    ];
    protected $hidden = ['password'];
}
