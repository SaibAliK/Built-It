<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Subscription extends Model

{
    use SoftDeletes;

    protected $dateFormat = 'U';

    public static $snakeAttributes = false;


    protected $casts = [

        'notify' => 'int',

        'created_at' => 'int',

        'updated_at' => 'int'

    ];


    protected $fillable = [

        'email',
        'notify'
    ];

}

