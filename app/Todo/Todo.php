<?php

namespace App\Todo;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int user_id
 * @property Carbon done_at
 */
class Todo extends Model
{
    protected $guarded = [];

    protected $casts = [
        'user_id' => 'int',
    ];

    protected $dates = [
        'done_at'
    ];

    protected $visible = ['id', 'content'];

    protected $perPage = 30;
}
