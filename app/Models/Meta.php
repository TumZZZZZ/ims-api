<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Meta extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'metas';

    protected $fillable = [
        'key',
        'value',
        'object_id',
    ];
}
