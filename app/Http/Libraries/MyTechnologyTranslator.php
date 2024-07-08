<?php

namespace App\Http\Libraries;

use Illuminate\Translation\Translator;

class MyTechnologyTranslator extends Translator {

    public function get($key, array $replace = array(), $locale = null, $fallback = true)
    {
        $lowerCaseKey = mb_strtolower($key);
        $locale = $locale ?: $this->locale;

        // For JSON translations, there is only one file per locale, so we will simply load
        // that file and then we will be ready to check the array for the key. These are
        // only one level deep so we do not need to do any fancy searching through it.
        $this->load('*', '*', $locale);

        $line = $this->loaded['*']['*'][$locale][$lowerCaseKey] ?? null;

        // If we can't find a translation for the JSON key, we will attempt to translate it
        // using the typical translation file. This way developers can always just use a
        // helper such as __ instead of having to pick between trans or __ with views.
        if (! isset($line)) {
            [$namespace, $group, $item] = $this->parseKey($lowerCaseKey);

            // Here we will get the locale that should be used for the language line. If one
            // was not passed, we will use the default locales which was given to us when
            // the translator was instantiated. Then, we can load the lines and return.
            $locales = $fallback ? $this->localeArray($locale) : [$locale];

            foreach ($locales as $localeNew) {
                if (! is_null($line = $this->getLine(
                    $namespace, $group, $localeNew, $item, $replace
                ))) {
                    return $line ?? $lowerCaseKey;
                }
            }
        }
        if($locale == 'en' && $line == null){
            $this->loaded['*']['*'][$locale][$lowerCaseKey] = $key;
            $langFile = $this->loaded['*']['*'][$locale];
            $langFile[$lowerCaseKey] = $key;
            file_put_contents(resource_path('lang/en.json'), json_encode($langFile,JSON_PRETTY_PRINT));
            $line = $key;
        }

        if($locale == 'ar' && $line == null){
            $this->loaded['*']['*'][$locale][$lowerCaseKey] = '';
            $langFile = $this->loaded['*']['*'][$locale];
            $langFile[$lowerCaseKey] = '';
            file_put_contents(resource_path('lang/ar.json'), json_encode($langFile,JSON_PRETTY_PRINT));
            $line = $key;
        }

        return $this->makeReplacements($line ?: $key, $replace);
    }

}
