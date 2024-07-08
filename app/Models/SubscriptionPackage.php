<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPackage extends Model
{
    use SoftDeletes;

    protected $dateFormat = 'U';
    public static $snakeAttributes = false;

    protected $hidden = [
        'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'created_at' => 'int',
        'updated_at' => 'int',
        'name' => 'array',
        'description' => 'array',
        'is_free' => 'bool',
    ];
    protected $fillable = [
        'id',
        'name',
        'duration',
        'duration_type',
        'is_free',
        'price',
        'description',
        'subscription_type',

    ];

    public function isSubscription(): bool
    {
        return $this->attributes['subscription_type'] == 'subscription';
    }

    public function isFeatured(): bool
    {
        return $this->attributes['subscription_type'] == 'featured';
    }

    public function isFree(): bool
    {
        return $this->attributes['is_free'];
    }


}
