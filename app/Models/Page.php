<?php

/**
 * Created by Reliese Model.
 * Date: Fri, 20 Apr 2018 05:47:07 +0000.
 */

namespace App\Models;

//use Reliese\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Page
 *
 * @property int $id
 * @property string $slug
 * @property string $image
 * @property int $created_at
 * @property int $updated_at
 *
 * @property \Illuminate\Database\Eloquent\Collection $languages
 *
 * @package App\Models
 */
class Page extends Model
{
    protected $dateFormat = 'U';
    public static $snakeAttributes = false;

    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = [
        'created_at' => 'int',
        'updated_at' => 'int',
        'content' => 'array',
        'name' => 'array',
    ];

    protected $fillable = [
        'slug',
        'image',
        'page_type',
        'icon',
        'name',
        'name->en',
        'name->ar',
        'content',
        'content->en',
        'content->ar'
    ];

    protected $appends = [
        'image_url',
    ];


    public function getImageUrlAttribute()
    {
        if (empty($this->attributes['image']))
        {
            return url('images/default-image.jpg');
        }
        return url( $this->attributes['image']);
    }
}
