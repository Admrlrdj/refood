<?php

namespace App\Models;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Receiver extends Authenticatable
{
    use Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'receivers';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'last_latitude',
        'last_longitude',
        'foundation_name',
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
