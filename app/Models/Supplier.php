<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Supplier extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'suppliers';

    protected $fillable = [
        'merchant_id',
        'name',
        'email',
        'calling_code',
        'phone_number',
        'address',
    ];

    public function merchant()
    {
        return $this->hasOne(Store::class, '_id', 'merchant_id')->whereNull('deleted_at');
    }
}
