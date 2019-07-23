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

    protected $visible = ['id', 'content', 'done_at'];

    protected $perPage = 30;

    protected function setDoneAtAttribute($date): void
    {
        $this->attributes['done_at'] = empty($date) ? null : Carbon::parse($date);
    }

    public function jsonSerialize(): array
    {
        $data = $this->toArray();
        $data['done_at'] = $this->done_at === null ? 0 : $this->done_at->timestamp;

        return $data;
    }
}
