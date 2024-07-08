<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Validation\ValidationException;

class CheckSubscription
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if ($user->isSupplier()) {
            if (!$user->isSubscribed()) {
                if ($request->expectsJson()) {
                    return responseBuilder()->error(__('Please purchase a subscription package.'));
                } else {
                    return redirect(route('front.dashboard.subscription.type'))->with('err', __('Please select a one subscription type.'));
                }
            }
        }
        return $next($request);
    }
}
