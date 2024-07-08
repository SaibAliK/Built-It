<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class OrderDetailItems extends Model
{
    use SoftDeletes;

    protected $dateFormat = 'U';
    public static $snakeAttributes = false;

    protected $casts = [
        'user_id' => 'int',
        'created_at' => 'int',
        'updated_at' => 'int',
        'name' => 'array',
        'extras' => 'array'
    ];

    protected $fillable = [
        'order_detail_id',
        'product_id',
        'order_id',
        'store_id',
        'name',
        'image',
        'status',
        'price',
        'subtotal',
        'shipping',
        'discount',
        'total',
        'extras',
        'quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function store()
    {
        return $this->belongsTo(User::class, 'store_id')->withTrashed();
    }

    public function getExtrasAttribute($value)
    {
        return json_decode($value, true);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function orderDetail()
    {
        return $this->belongsTo(\App\Models\OrderDetail::class);
    }
}
