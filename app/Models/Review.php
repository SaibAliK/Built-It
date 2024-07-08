<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;
    protected $dateFormat = 'U';
    public static $snakeAttributes = false;

    protected $table = 'reviews';


    protected $casts = [
        'store_id' => 'int',
        'user_id' => 'int',
        'rating' => 'float',
        'created_at' => 'int',
        'updated_at' => 'int'
    ];

    protected $fillable = [
        'store_id',
        'user_id',
        'product_id',
        'order_detail_id',
        'order_detail_items_id',
        'rating',
        'review'
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->getTimestamp();
    }

    public function store()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

//    public function orderDetails()
//    {
//        return $this->hasMany(OrderDetail::class, 'id');
//    }
//
//    public function orderDetailItems()
//    {
//        return $this->belongsTo(OrderDetailItems::class);
//    }

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
