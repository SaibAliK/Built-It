<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubscription extends Model
{
    protected $dateFormat = 'U';

    protected $casts = [
        'is_expired' => 'bool',
        'created_at' => 'int',
        'updated_at' => 'int',
        'package' => 'array',
        'paypal_response' => 'array',
    ];
    protected $fillable = [
        'user_id',
        'type',
        'package',
        'is_expired',
        'payment_status',
        'payer_id',
        'first_name',
        'last_name',
        'payment_id',
        'payer_email',
        'payer_status',
        'payment_method',
        'paypal_response',
        'aed_price',
        'currency',
        'commission',
    ];

    public function store(){
        return $this->belongsTo(User::class);
    }






}
