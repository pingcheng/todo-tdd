<?php

namespace App\Todo;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $guarded = [];

    protected $casts = [
        'user_id' => 'int'
    ];
}
