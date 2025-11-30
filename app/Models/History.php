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
        return $this->belongsTo(User::class, 'user_id', '_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', '_id');
    }
}
