<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\Facades\JWTAuth;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $dateFormat = 'U';
    public static $snakeAttributes = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'city_id',
        'expiry_date',
        'user_name',
        'email',
        'password',
        'phone',
        'image',
        'id_card_images',
        'company_id',
        'is_verified',
        'is_active',
        'is_id_card_verified',
        'verification_code',
        'address',
        'latitude',
        'longitude',
        'user_type',
        'supplier_name',
        'about',
        'rating',
        'client_id',
        'secret_id',
        'amount_on_hold',
        'available_balance',
        'total_earning',
        'settings',
        'fcm_token',
        'facebook_id',
        'google_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'is_id_card_verified' => 'boolean',
        'supplier_name' => 'array',
        'id_card_images' => 'array',
        'about' => 'array',
        'rating' => 'float',
    ];

    protected $appends = [
        'image_url',
        'id_card_images_url',
    ];


    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function subscription()
    {
        return $this->hasOne(UserSubscription::class)->latest();
    }

    public function isUser()
    {
        return $this->user_type == 'user';
    }

    public function areas()
    {
        return $this->belongsToMany(City::class, 'area_users', 'user_id', 'area_id')->where('parent_id', '!=', null)->withPivot('id', 'area_id', 'price', 'user_id');
    }

    public function isRider()
    {
        return $this->user_type == 'rider';
    }

    public function coveredAreas()
    {
        return $this->belongsToMany(City::class, 'area_users', 'user_id', 'area_id')->withPivot('price')->whereNull('area_users.deleted_at');
    }

    public function is_CoveredArea($area_id)
    {

        return $this->belongsToMany(City::class, 'area_users', 'user_id', 'area_id')->withPivot('price')->whereNull('area_users.deleted_at')->where('area_users.area_id', $area_id)->exists();
    }

    public function isEmployee()
    {
        return $this->user_type == 'employee';
    }

    public function isSupplier()
    {
        return $this->user_type == 'supplier';
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('default_address', 1);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'store_id');
    }

    public function isCompany()
    {
        return $this->user_type == 'company';
    }

    public function deliveryCompany()
    {
        return $this->belongsTo(User::class, 'company_id', 'id');
    }

    public function isVerified()
    {
        return $this->is_verified;
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function storeFeaturedSubscription()
    {
        return $this->hasMany(UserFeaturedSubscription::class, 'user_id')->where('is_expired', 0)->where('product_id', 0);
    }

    public function UserFeaturedSubscriptionCount($id)
    {
        return $this->storeFeaturedSubscription()->whereJsonContains('package->id', $id)->count();
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'user_id');
    }


    public function getFormattedModel($generateNewToken = false, $updateSession = false): User
    {
        $user = $this;
        unset($user->password);
        unset($user->created_at);
        unset($user->updated_at);
        unset($user->deleted_at);
        if ($user->isUser()) {
            unset($user->supplier_name);
            unset($user->expiry_date);
            //            unset($user->id_card_images);
            unset($user->is_id_card_verified);
            unset($user->about);
            unset($user->rating);
            unset($user->client_id);
            unset($user->secret_id);
            unset($user->amount_on_hold);
            unset($user->available_balance);
            unset($user->total_earning);
            ;
        } else {
            unset($user->user_name);
            $user->is_subscribed = $user->isSubscribed();
            $user->has_subscriptions = $user->hasSubscriptions();
        }
        if ($generateNewToken) {
            $JWTToken = JWTAuth::fromUser($user);
            $user->token = 'Bearer ' . $JWTToken;
        }
        if ($updateSession) {
            session()->put('USER_DATA', $user);
        }
        return $user;
    }


    public function color()
    {
        return $this->hasOne(Color::class, 'store_id');
    }

    public function checkIfActive()
    {
        $message = __('Your account has been suspended. Please contact the admin.');
        if ($this->isVerified() && !$this->isActive()) {
            auth()->logout();
            throw new Exception($message);
        }
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();

    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getImageUrlAttribute()
    {
        if (empty($this->attributes['image'])) {
            return url('images/default-image.jpg');
        }
        return url($this->attributes['image']);
    }


    public function fcms()
    {
        return $this->hasMany(Fcm::class)->orderBy('id', 'desc')->first();
    }

    public function getIdCardImagesUrlAttribute()
    {
        if (empty($this->attributes['id_card_images'])) {
            return url('images/default-image.jpg');
        }
        return url($this->attributes['id_card_images']);
    }

    public function isSubscribed()
    {
        $subscription = $this->subscription()->first();
        if ($subscription) {
            if (!$subscription->is_expired) {
                return true;
            }
        }
        return false;
    }

    public function hasSubscriptions()
    {
        $subscription = $this->subscription()->first();
        if ($subscription) {
            return true;
        } else {
            return false;
        }
    }

    public function storeSubscription()
    {
        return $this->hasOne(UserSubscription::class)->latest();
    }

    public function isCardImageVerified()
    {
        return $this->is_id_card_verified == 1;
    }
}
