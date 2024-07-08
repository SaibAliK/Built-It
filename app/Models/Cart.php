<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;

    protected $dateFormat = 'U';
    public static $snakeAttributes = false;
    protected $casts = [
        'created_at' => 'int',
        'updated_at' => 'int',
        'images' => 'string',
        'extras' => 'array',
    ];
    protected $fillable = [
        'user_id',
        'store_id',
        'product_id',
        'price',
        'discounted_subtotal',
        'quantity',
        'quantity_dependent',
        'subtotal',
        'shipping',
        'total',
        'images',
        'extras',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function store()
    {
        return $this->belongsTo(User::class, 'store_id')->withTrashed();
    }

    public function getExtrasAttribute($extras)
    {
        return json_decode($extras);
    }

    public function getImagesAttribute($images)
    {
        if (empty($images)) {
            $images = 'assets/front/img/WebPlaceholders/Product.jpg';
        }
        return url($images);
    }
}
