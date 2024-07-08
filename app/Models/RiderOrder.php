<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiderOrder extends Model
{
    use HasFactory;

    protected $dateFormat = 'U';
    public static $snakeAttributes = false;

    protected $casts = [
        'user_id'      => 'int',
        'total_amount' => 'int',
        'created_at'   => 'int',
        'updated_at'   => 'int',
    ];
    protected $fillable = [
        'order_id',
        'delivery_company_id',
        'rider_id',
        'status',
        'order_detail_id',
        'product_price',
        'quantity',
        'total_price',
        'created_at',
        'updated_at'
    ];
    public function orders()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function deliveryCompany()
    {
        return $this->belongsTo(User::class, 'delivery_company_id');
    }

    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    public function orderDetails()
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }
}
