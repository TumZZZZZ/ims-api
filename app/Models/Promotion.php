<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Promotion extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'promotions';

    protected $fillable = [
        'branch_id',
        'name',
        'type',
        'value',
        'start_date',
        'end_date',
        'category_ids',
        'product_ids',
    ];

    public function branch()
    {
        return $this->hasOne(Store::class, '_id', 'branch_id')->whereNull('deleted_at');
    }
}
