<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFeaturedSubscription extends Model
{
    protected $dateFormat = 'U';
    protected $table = 'user_featured_subscription';

    protected $casts = [
        'is_expired' => 'bool',
        'created_at' => 'int',
        'updated_at' => 'int',
        'package' => 'array',
        'paypal_response' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'product_id',
        'first_name',
        'last_name',
        'package',
        'is_expired',
        'payment_status',
        'payer_id',
        'payment_id',
        'payer_email',
        'payer_status',
        'payment_method',
        'paypal_response',
        'aed_price',
        'currency',
    ];

    public function store()
    {
        return $this->belongsTo(User::class);
    }

    public function storeSubcriptionPackages()
    {
        return $this->hasMany(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedModel(): array
    {
        $data = [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "product_id" => $this->product_id,
            "package" => [
                "id" => $this->package['id'],
                "name" => $this->package['name'],
            ],
            "is_expired" => $this->is_expired,
        ];
        return $data;
    }
}
