<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Language as Lang;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Foundation\Application;

class Language {

    public function __construct(Application $app, Redirector $redirector, Request $request) {
        $this->app = $app;
        $this->redirector = $redirector;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $locales = $this->app->config->get('app.locales');
        $locale = $request->segment(1);
        if (!in_array($locale, $locales)) {
            $segments = array_merge([$this->app->config->get('app.fallback_locale')], $request->segments());

            if ($this->request->expectsJson()) {
//                return response()->json(['Invalid/missing language parameter!']);
                return responseBuilder()->error('Invalid/missing language parameter!');
            }
            else {
                return $this->redirector->to(implode('/', $segments));

            }
        }
        $this->app->setLocale($locale);
        return $next($request);
    }

}
