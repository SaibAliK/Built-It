<?php

namespace App\Http\Middleware;

use App\Http\Repositories\FcmRepository;
use Closure;
use Illuminate\Validation\ValidationException;

class EmailVerified
{
    
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if ($request->expectsJson()) {
            if (!$user->isVerified()) {
                return responseBuilder()->error(__('Your email is not verified, please verify your email address'));
            }
            if (!$user->isActive()) {
                return responseBuilder()->error(__('Your account has been suspended. Please contact the admin.'));
            }
        } else {
            if (!$user->isVerified()) {
                return redirect(route('front.auth.verification'));
            }
            $message = __('Your account has been suspended. Please contact the admin.');
            if (!$user->isActive()) {
                auth()->logout();
                session()->forget('USER_DATA');
                throw ValidationException::withMessages([
                    'email' => [$message],
                ]);
            }
        }

        return $next($request);
    }

}
