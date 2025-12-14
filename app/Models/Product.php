<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'products';

    protected $fillable = [
        'name',
        'sku',
        'barcode',
        'description',
        'category_ids',
    ];

    public function image()
    {
        return $this->hasOne(Image::class, 'object_id', '_id')->whereNull('deleted_at');
    }

    public function assign()
    {
        return $this->hasOne(ProductAssign::class, 'product_id', '_id')
            ->where('branch_id', Auth::user()->active_on)
            ->whereNull('deleted_at');
    }

    public function assignAll()
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
