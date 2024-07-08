<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use SoftDeletes;

    protected $dateFormat = 'U';
    public static $snakeAttributes = false;

    protected $casts = [
        'user_id' => 'int',
        'total_amount' => 'int',
        'created_at' => 'int',
        'updated_at' => 'int',
        'cancel_reason' => 'array'
    ];

    protected $fillable = [
        'order_id',
        'store_id',
        'user_id',
        'status',
        'order_no',
        'image',
        'invoice',
        'cancel_reason',
        'subtotal',
        'vat_percentage',
        'vat',
        'shipping',
        'discount',
        'commission',
        'total'
    ];

    protected $appends = ['created_date'];

    protected function getImageAttribute()
    {
        if (!empty($this->attributes['image'])) {
            return url($this->attributes['image']);
        }
        return url('assets/front/img/WebPlaceholders/Product.jpg');
    }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->getTimestamp();
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function getCreatedDateAttribute()
    {
        return $this->attributes['created_at'];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItems()
    {
        return $this->hasMany(\App\Models\OrderDetailItems::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function store()
    {
        return $this->belongsTo(User::class, 'store_id')->withTrashed();
    }

    public function orders()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function companyOrder()
    {
        return $this->hasMany(CompanyOrder::class, 'order_detail_id');
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function riderOrder()
    {
        return $this->hasMany(RiderOrder::class, 'order_detail_id');
    }
}
