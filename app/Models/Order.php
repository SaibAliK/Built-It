<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use SoftDeletes;

    protected $dateFormat = 'U';
    public static $snakeAttributes = false;

    protected $casts = [
        'user_id' => 'int',
        'created_at' => 'int',
        'updated_at' => 'int',
        'cancel_reason' => 'array',
    ];

    protected $appends = ['full_name', 'created_date'];

    protected $fillable = [
        'user_id',
        'full_name',
        'status',
        'address',
        'payment_status',
        'payer_id',
        'charges',
        'order_notes',
        'currency',
        'transaction_details',
        'order_number',
        'payment_id',
        'payer_email',
        'payer_status',
        'payment_method',
        'paypal_response',
        'image',
        'invoice',
        'vat_percentage',
        'discount_percentage',
        'discount',
        'subtotal',
        'vat',
        'total',
        'shipping',
        'cancel_reason'

    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->getTimestamp();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function store()
    {
        return $this->belongsTo(User::class, 'store_id', 'id')->withTrashed()->where('type', '!=', 'user');
    }

    public function orderDetails()
    {
        return $this->hasMany(\App\Models\OrderDetail::class);
    }

    public function deliveryCompany()
    {
        return $this->hasOne(\App\Models\CompanyOrder::class);
    }

    public function rider()
    {
        return $this->hasOne(\App\Models\RiderOrder::class);
    }

    public function orderItems()
    {
        return $this->hasManyThrough(\App\Models\OrderDetailItems::class, OrderDetail::class);
    }

    protected function getImageAttribute()
    {
        if (!empty($this->attributes['image'])) {
            return url($this->attributes['image']);
        }
        return url('assets/front/img/WebPlaceholders/Product.jpg');
    }

    public function getCreatedDateAttribute()
    {
        return $this->attributes['created_at'];
    }

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAddressAttribute($value)
    {
        return json_decode($value, true);
    }
}
