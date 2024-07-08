<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{


    
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'language' => \App\Http\Middleware\Language::class,
        'email_verified' => \App\Http\Middleware\EmailVerified::class,
        'jwt.verify' => \App\Http\Middleware\MyTechnologyJWT::class,
        'check_subscription' => \App\Http\Middleware\CheckSubscription::class,
        'user' => \App\Http\Middleware\User::class,
        'supplier' => \App\Http\Middleware\Supplier::class,
        'delivery_Company' => \App\Http\Middleware\DeliveryCompany::class,

    ];
}
