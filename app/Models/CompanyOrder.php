<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyOrder extends Model
{
    use HasFactory, SoftDeletes;


    protected $dateFormat = 'U';
    public static $snakeAttributes = false;

    protected $casts = [
        'company_id'      => 'int',
        'order_detail_id' => 'int',
        'created_at'      => 'int',
        'updated_at'      => 'int',
        'is_deleted'      => 'int'
    ];
    protected $fillable = [
        'company_id',
        'status',
        'order_detail_id',
        'order_id',
        'is_deleted'
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->getTimestamp();
    }

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class, 'order_detail_id');
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

}
