<?php

namespace App\Models\User;

use MongoDB\Laravel\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $connection = 'mongodb';
    protected $collection = 'users';

    protected $fillable = ['name', 'username', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];
}
