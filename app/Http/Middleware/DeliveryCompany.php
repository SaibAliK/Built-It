<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DeliveryCompany
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if ($user->isUser() || $user->isSupplier() || $user->isEmployee()) {
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
