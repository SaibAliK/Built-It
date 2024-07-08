<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $dateFormat = 'U';
    public static $snakeAttributes = false;
    protected $table = 'categories';

    protected $fillable = [
        'parent_id',
        'name',
        'image',
        'name->en',
        'name->ar'
    ];

    protected $casts = [
        'created_at' => 'int',
        'updated_at' => 'int',
        'name' => 'array',
        'parent_id' => 'int'
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->getTimestamp();
    }


    protected function getImageAttribute()
    {
        if (!empty($this->attributes['image'])) {
            return url($this->attributes['image']);
        }
        return url('assets/front/img/WebPlaceholders/Category.jpg');
    }

    public function subCategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
