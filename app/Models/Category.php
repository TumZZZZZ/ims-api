<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'categories';

    protected $fillable = [
        'name',
        'parent_id',
        'branch_ids',
        'product_ids',
        'promotion_id',
    ];

    public function image()
    {
        return $this->hasOne(Image::class, 'object_id', '_id')->whereNull('deleted_at');
    }

    public function getProducts()
    {
        return $this->product_ids ? Product::whereIn('_id', $this->product_ids)
            ->whereNull('deleted_at')
            ->get() : collect([]);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promotion_id', '_id')->whereNull('deleted_at');
    }
}
