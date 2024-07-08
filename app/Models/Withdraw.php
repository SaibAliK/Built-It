<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Withdraw extends Model
{
    use SoftDeletes;

    protected $dateFormat = 'U';
    public static $snakeAttributes = false;
    protected $casts = [
        'user_id' => 'int',
        'amount' => 'float',
        'created_at' => 'int',
        'updated_at' => 'int'
    ];
    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'method',
        'paypal_response'
    ];
    protected $hidden = [
        'paypal_response',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->getTimestamp();
    }

    public function store()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getAmountAttribute()
    {
        return json_decode(json_encode(getPriceObject($this->attributes['amount'])), true);
    }
}
