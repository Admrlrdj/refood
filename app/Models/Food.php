<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Food extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'foods';

    protected $fillable = [
        'donor_id',
        'name',
        'description',
        'portion',
        'pickup_address',
        'latitude',
        'longitude',
        'status',
        'expired_at',
        'photo_url'
    ];
}
