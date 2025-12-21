<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class OrderDetail extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'order_details';

    protected $fillable = [
        'order_id',
        'category_id',
        'product_id',
        'discount_id',
        'price',
        'cost',
        'quantity',
        'discount_amount',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id')->whereNull('deleted_at');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', '_id')->whereNull('deleted_at');
    }
}
