<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaUser extends Model
{
    protected $dateFormat = 'U';
    public static $snakeAttributes = false;

    protected $casts = [
        'created_at' => 'int',
        'updated_at' => 'int',
        'deleted_at' => 'int',
    ];

    protected $fillable = [
        'user_id',
        'area_id',
        'price'
    ];


    public function area()
    {
        return $this->belongsTo(city::class,'area_id')->withTrashed();
    }
}
