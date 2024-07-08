<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\AdminResetPassword;


class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $dateFormat = 'U';

    protected $fillable = [
        'name', 'email', 'password','is_active','is_verified','image'
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = [
        'image_url',
    ];


    public function sendPasswordResetNotification($token) {
        $this->notify(new AdminResetPassword($token));
    }

    public function getImageUrlAttribute()
    {
        if (empty($this->attributes['image'])) {
            return url('images/default-image.jpg');
        }
        return url($this->attributes['image']);
    }

    public function isActive()
    {
        return $this->is_active;
    }

}
