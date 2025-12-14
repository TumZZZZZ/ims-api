<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'purchase_orders';

    protected $fillable = [
        'branch_id',
        'user_id',
        'supplier_id',
        'order_number',
        'requested_date',
        'rejected_date',
        'recieved_date',
        'status',
        'total_cost',
        'reason',
        'purchase_order_details',
    ];

    public function branch()
    {
        return $this->hasOne(Store::class, '_id', 'branch_id')->whereNull('deleted_at');
    }

    public function user()
    {
        return $this->hasOne(User::class, '_id', 'user_id')->whereNull('deleted_at');
    }

    public function supplier()
    {
        return $this->hasOne(Supplier::class, '_id', 'supplier_id')->whereNull('deleted_at');
    }
}
