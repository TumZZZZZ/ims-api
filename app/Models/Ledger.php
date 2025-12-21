<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Ledger extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'ledgers';

    protected $fillable = [
        'branch_id',
        'product_id',
        'starting_quantity',
        'quantity',
        'unit_cost',
        'cost',
        'type'
    ];

    public function branch()
    {
        return $this->belongsTo(Store::class, 'branch_id', '_id')->whereNull('deleted_at');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', '_id')->whereNull('deleted_at');
    }
}
