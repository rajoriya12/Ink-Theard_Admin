<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'orders';

    protected $fillable = [
        'customerName',
        'customerEmail',
        'phone',
        'address',
        'city',
        'state',
        'pincode',
        'productId',
        'productTitle',
        'productPrice',
        'productImage',
        'status',
    ];
}