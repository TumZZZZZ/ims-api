<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Casts\ObjectId;
use MongoDB\Laravel\Eloquent\Model;

class Image extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'images';

    protected $fillable = [
        'object_id',
        'collection',
        'url',
    ];

    // protected $casts = [
    //     'object_id' => ObjectId::class,
    // ];
}
