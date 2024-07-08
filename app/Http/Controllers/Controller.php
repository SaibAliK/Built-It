<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CartRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\City;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user, $breadcrumbs, $breadcrumbTitle;

    public function __construct($userDataKey = 'userData', $guard = null, $getUserCallback = null)
    {

        $this->middleware(function ($request, $next) use ($guard, $getUserCallback) {
            $this->user = ($guard == 'admin') ? session('ADMIN_DATA') : session('USER_DATA');

            if ($getUserCallback) {
                $getUserCallback($this->user);
            }
            return $next($request);
        });

        View::composer('*', function ($view) use ($userDataKey, $guard) {

            $cartCount = 0;
            if (Auth::check()) {
                $cartRepository = new CartRepository();
                $cartCount = $cartRepository->getCartCount();
            }

            $languages = [];
            $segments = request()->segments(1);
            $queryParams = explode('?', request()->fullUrl());
            foreach (config('app.locales') as $lang) {
                $segments[0] = $lang;
                $languages[$lang] = [
                    'title' => $lang == 'ar' ? 'العربية' : 'English',
                    'url' => url(implode('/', $segments) . ((count($queryParams) > 1) ? '?' . $queryParams[1] : '')),
                    'short_code' => ucwords($lang),
                ];
            }
            $cities = City::where('parent_id', 0)->where('deleted_at', null)->get();

            if (!$guard) {
                $view->with([
                    'userData' => $this->user,
                    'cartCount' => $cartCount,
                    'user' => $this->user,
                    'authUserData' => Auth::check() ? Auth::user()->getFormattedModel() : Auth::user(),
                    'locale' => config('app.locale'),
                    'currency' => session()->get('currency') ? session()->get('currency') : 'AED',
                    'currencies' => config('app.currencies'),
                    'maintenance_mode' => session('maintenanceMode', 1),
                    'breadcrumbs' => $this->breadcrumbs,
                    'locales' => config('app.locales'),
                    'languages' => $languages,
                    'cities' => $cities,
                    'breadcrumbTitle' => $this->breadcrumbTitle,
                ]);
            } else {
                $view->with([
                    'maintenance_mode' => session('maintenanceMode', 1),
                    'breadcrumbs' => $this->breadcrumbs,
                    'breadcrumbTitle' => $this->breadcrumbTitle,
                    'locale' => config('app.locale'),
                    'admin' => $this->user,
                    'adminData' => $this->user,
                    'locales' => config('app.locales'),

                ]);
            }
        });
    }
}
