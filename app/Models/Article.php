<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $dateFormat = 'U';

    protected $hidden = [
        'updated_at',
    ];

    protected $casts = [
        'name' => 'array',
        'content' => 'array',
        'author' => 'array'
    ];

    protected $fillable = ['id',
        'name->en', 'name->ar', 'content->en', 'content->ar', 'image', 'slug', 'author->en', 'author->ar'
    ];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->getTimestamp();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'article_id');
    }

    public function getNameAttribute()
    {
        if (isset($this->attributes['name'])) {
            if (is_array($this->attributes['name'])) {
                return $this->attributes['name'];
            } else {
                return json_decode($this->attributes['name'], true);
            }
        } else {
            return null;
        }
    }

    public function getImageUrlAttribute()
    {
        if (empty($this->attributes['image'])) {
            return url('assets/front/img/Placeholders/Articles.jpg');
        }
        return url($this->attributes['image']);
    }


    public function getAuthorAttribute()
    {
        if (isset($this->attributes['author'])) {
            if (is_array($this->attributes['author'])) {
                return $this->attributes['author'];
            } else {
                return json_decode($this->attributes['author'], true);
            }
        } else {
            return null;
        }
    }

    public function getContentAttribute()
    {
        if (isset($this->attributes['content'])) {
            if (is_array($this->attributes['content'])) {
                return $this->attributes['content'];
            } else {
                return json_decode($this->attributes['content'], true);
            }
        } else {
            return null;
        }
    }

}
