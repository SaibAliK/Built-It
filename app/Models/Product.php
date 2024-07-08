<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $dateFormat = 'U';
    public static $snakeAttributes = false;
    protected $table = 'products';

    protected $casts = [
        'price' => 'float',
        'quantity' => 'int',
        'created_at' => 'int',
        'updated_at' => 'int',
        'name' => 'array',
        'is_featured' => 'boolean',
        'description' => 'array',
    ];

    protected $appends = ['default_image', 'discount_is_expired', 'offer_is_expired', 'is_featured'];

    protected $fillable = [
        'user_id',
        'category_id',
        'subcategory_id',
        'slug',
        'name',
        'description',
        'name->en',
        'name->ar',
        'description->ar',
        'description->en',
        'price',
        'quantity',
        'product_expiry_date',
        'offer_expiry_date',
        'reserve',
        'sold',
        'is_featured',
        'average_rating',
        'discounted_price',
        'status',
        'offer_percentage',
        'product_type',
    ];

    public function imagesWithTrashed()
    {
        return $this->hasMany(ProductImage::class)->orderBy('file_default', 'desc')->withTrashed();
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('file_default', 'desc');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->where('parent_id', '=', 0);
    }

    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id')->where('parent_id', '!=', 0);
    }

    public function userFeaturedSubscriptions()
    {
        return $this->hasOne(UserFeaturedSubscription::class)->where('is_expired', 0);
    }

    public function checkSlug($slug)
    {
        $slugName = str_replace(' ', '-', $slug);
        $countOfSameProductName = $this->where('name', 'like', '%' . $slug . '%')->withTrashed()->count();
        if ($countOfSameProductName > 0) {
            $product = $this->where('name', 'like', '%' . $slug . '%')->latest()->first();
            return $slugName . '-' . rand(2, 10000);
        } else {
            return $slugName . '-' . rand(3, 10000);
        }
    }

    public function store()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

//    public function orderDetailItems()
//    {
//        return $this->hasMany(\App\Models\OrderDetailItems::class, 'product_id');
//    }

    public function imageType()
    {
        $image_type = $this->images()->where('product_id', $this->id)->get()->first();
        if (!is_null($image_type)) {
            return $image_type->file_type;
        }
        return "image/jpeg";
    }


    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id')->orderBy('id', 'DESC');
    }

    public function getDiscountIsExpiredAttribute()
    {
        $is_expired = true;
        if ($this->offer_expiry_date > 0) {
            if ($this->offer_percentage > 0 && time() < $this->getOriginal('offer_expiry_date')) {
                $is_expired = false;
            }
        }
        return $is_expired;
    }

    public function getOfferIsExpiredAttribute()
    {
        $is_expired = true;
        if ($this->offer_expiry_date > 0) {
            if (time() < $this->getOriginal('offer_expiry_date')) {
                $is_expired = false;
            }
        }
        return $is_expired;
    }

    public function getIsFeaturedAttribute()
    {
        $subscription = $this->userFeaturedSubscriptions()->first();
        if (is_null($subscription)) {
            return false;
        }
        return true;
    }

    public function orderDetailItems()
    {
        return $this->hasMany(\App\Models\OrderDetailItems::class, 'product_id');
    }

    public function getDefaultImageAttribute()
    {
        $default_image = $this->images()->where('product_id', $this->id)->where('file_default', 1)->get()->first();
        if (!is_null($default_image)) {
            return $default_image->file_path;
        } elseif (is_null($default_image)) {
            $default_image = $this->images()->where('product_id', $this->id)->orWhere('file_default', 1)->get()->first();
            return $default_image->file_path;
        } else {
            return "assets/front/img/WebPlaceholders/Product.jpg";
        }
    }

    public function getFormattedModel(): Product
    {
        $product = $this;
        unset($product->created_at);
        unset($product->updated_at);
        unset($product->deleted_at);

        if ($product->product_type == 'product') {
            unset($product->offer_percentage);
            unset($product->offer_expiry_date);
            unset($product->product_expiry_date);
        } else {
            unset($product->product_expiry_date);
        }
        return $product;
    }
}
