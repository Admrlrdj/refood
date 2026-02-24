<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Delivery extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'deliveries';

    protected $fillable = [
        'food_id',
        'receiver_id',
        'volunteer_id',
        'pickup_time',
        'delivered_time',
        'status',
        'proof_photo',
        'notes'
    ];
}
