<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class History extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'histories';

    protected $fillable = [
        'user_id',
        'store_id',
        'action',
    ];

    public function user()
    {
        return $this->hasOne(User::class, '_id', 'user_id');
    }

    public function store()
    {
        return $this->hasOne(Store::class, '_id', 'store_id');
    }
}
