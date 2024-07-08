<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fcm extends Model
{
    protected $table = 'fcms';
    protected $dateFormat = 'U';
    protected $casts = [
        'created_at' => 'int',
        'updated_at' => 'int',
    ];

    protected $fillable = [
        'user_id',
        'fcm_token',
        'selected_lang',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
