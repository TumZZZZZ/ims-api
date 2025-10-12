<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ProductAssign extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'product_assigns';

    protected $fillable = [
        'store_id',
        'product_id',
        'quantity',
        'threshold',
        'price',
        'cost',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}
