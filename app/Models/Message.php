<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Message extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'contactforms';

    protected $fillable = [
        'name',
        'email',
        'message',
    ];
}