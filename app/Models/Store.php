<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Store extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'stores';

    protected $fillable = [
        'name',
        'location'
    ];

    public function admin()
    {
        return $this->hasOne(User::class, 'store_id', '_id')->where('role', 'ADMIN');
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'object_id', '_id')->whereNull('deleted_at');
    }
}
