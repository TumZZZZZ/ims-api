<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class History extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'histories';

    protected $fillable = [
        'user_id',
        'merchant_id',
        'branch_id',
        'action',
        'details',
    ];

    public function user()
    {
        return $this->hasOne(User::class, '_id', 'user_id');
    }

    public function merchant()
    {
        return $this->hasOne(Store::class, '_id', 'merchant_id');
    }

    public function branch()
    {
        return $this->hasOne(Store::class, '_id', 'branch_id');
    }
}
