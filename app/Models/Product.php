<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'products';

    protected $fillable = [
        'title',
        'description',
        'price',
        'image',
        'category',
        'featured',
        'discount'
    ];
}