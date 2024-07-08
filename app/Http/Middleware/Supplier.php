<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Supplier
{

    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if ($user->isUser() || $user->isCompany() || $user->isRider()) {
            if ($request->expectsJson()) {
                return responseBuilder()->error(__('You are not allow to access this page'));
            } else {
                return redirect(route('front.dashboard.index'))->with('err', __('You are not allow to access this page'));
            }
        } else {
            return $next($request);
        }
    }
}
