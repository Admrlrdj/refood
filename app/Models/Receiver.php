<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Receiver extends Authenticatable
{
    use HasApiTokens;

    protected $connection = 'mongodb';
    protected $collection = 'receivers';

    protected $fillable = [
        'organization_name',
        'username',
        'password',
        'contact_person',
        'phone',
        'address',
        'capacity',
        'is_verified'
    ];
    protected $hidden = ['password'];
}
