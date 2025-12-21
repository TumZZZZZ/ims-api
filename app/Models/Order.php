<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'orders';

    protected $fillable = [
        'branch_id',
        'payment_id',
        'sale_by',
        'order_number',
        'date',
        'status',
    ];

    public function payment()
    {
        return $this->belongsTo(Meta::class, 'payment_id', '_id')->whereNull('deleted_at');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', '_id')
            ->whereNull('deleted_at');
    }
}
