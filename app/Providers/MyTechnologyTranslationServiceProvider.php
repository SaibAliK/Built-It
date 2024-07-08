<?php

namespace App\Providers;

use App\Http\Libraries\MyTechnologyTranslator;
use Illuminate\Translation\TranslationServiceProvider;

class MyTechnologyTranslationServiceProvider extends TranslationServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot() {
        //
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->registerLoader();

        $this->app->singleton('translator', function($app){

            $loader = $app['translation.loader'];
            $locale = $app['config']['app.locale'];
            $myTechnologyTranslator = new MyTechnologyTranslator($loader, $locale);
            $myTechnologyTranslator->setFallback($app['config']['app.fallback_locale']);
//            $myTechnologyTranslator->getFromJson('about us', [], 'en');
            return $myTechnologyTranslator;
        });
    }

}
