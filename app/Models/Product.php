<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

    protected $fillable = [
        'name',
        'barcode',
        'description',
        'unit',
    ];

    public function image()
    {
        return $this->hasOne(Image::class, 'object_id', '_id')->whereNull('deleted_at');
    }

    public function assigns()
    {
        return $this->hasMany(ProductAssign::class, 'product_id', '_id')
            ->whereNull('deleted_at');
    }

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            null,
            'product_ids',
            '_id'
        )->whereNull('deleted_at');
    }
}
