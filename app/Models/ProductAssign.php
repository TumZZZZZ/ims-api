<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use MongoDB\Laravel\Eloquent\Model;

class ProductAssign extends Model
{
    use SoftDeletes;

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

    public function branch()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}
