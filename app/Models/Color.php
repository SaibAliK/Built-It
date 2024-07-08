<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{

    protected $dateFormat = 'U';
    public static $snakeAttributes = false;
    protected $table = 'colors';

    protected $fillable = [
        'store_id',
        'heading_color',
        'text_color',
        'icons_color',
        'background_color',
    ];

    public function store()
    {
        return $this->belongsTo(User::class, 'store_id');
    }
}
