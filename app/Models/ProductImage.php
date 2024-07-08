<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImage extends Model
{
    use SoftDeletes;

    protected $dateFormat = 'U';
    public static $snakeAttributes = false;
    protected $table = 'product_images';

//    protected $appends = ['image'];
    protected $fillable = [
        'product_id',
        'file_path',
        'file_default',
        'file_type',
    ];

    protected $casts = [
        'product_id' => 'int',
        'created_at' => 'int',
        'updated_at' => 'int',
        'deleted_at' => 'int',
        'file_default' => 'int'
    ];

    public function getImageUrlLargeAttribute()
    {
        if (empty($this->attributes['file_path'])) {
            return url('assets/front/img/WebPlaceholders/Product.jpg');
        }
        return url($this->attributes['file_path']);
    }

    public function getImageUrlSmallAttribute()
    {
        if (empty($this->attributes['file_path'])) {
            return url('assets/front/img/WebPlaceholders/Product.jpg');
        }
        return url($this->attributes['file_path']);
    }

    public function getFormattedModel(): ProductImage
    {
        $productImage = $this;
        unset($productImage->created_at);
        unset($productImage->updated_at);
        unset($productImage->deleted_at);
        return $productImage;
    }

    public function imageType()
    {
        if (!is_null($this->attributes['file_type'])) {
            return $this->attributes['file_type'];
        }
        return "image/jpeg";
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
