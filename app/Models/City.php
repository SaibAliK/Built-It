<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;

    protected $dateFormat = 'U';
    public static $snakeAttributes = false;

    protected $table = 'cities';

    protected $fillable = [
        'parent_id',
        'name',
        'name->en',
        'name->ar',
        'polygon',
        'latitude',
        'longitude'
    ];
    protected $casts = [
        'created_at' => 'int',
        'updated_at' => 'int',
        'name' => 'array',
        'polygon' => 'array',
        'parent_id' => 'int'
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function areas()
    {
        return $this->hasMany(City::class, 'parent_id');
    }
}
