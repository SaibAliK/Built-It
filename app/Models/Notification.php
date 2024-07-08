<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;
    protected $dateFormat = 'U';
    public static $snakeAttributes = false;
    protected $table = 'notifications';

    protected $casts = [
        'created_at' => 'int',
        'updated_at' => 'int',
        'extras' => 'array',
        'title' => 'array',
        'description' => 'array'
    ];

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'extras->conversation_id',
        'extras->order_id',
        'extras->product_slug',
        'extras->product_id',
        'extras->display_name',
        'extras->app_logo',
        'extras->order_no',
        'extras->notification_id',
        'extras->store_id',
        'action',
        'title->en',
        'title->ar',
        'description->en',
        'description->ar'
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->getTimestamp();
    }


    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
