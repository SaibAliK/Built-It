<?php

namespace App\Http\Controllers\Api;

use App\Http\Repositories\SliderRepository;
use App\Models\Language;
use App\Http\Controllers\Controller;
use App\Http\Repositories\SiteSettingsRepository;
use App\Http\Repositories\CityRepository;
use App\Http\Repositories\MallRepository;
use Illuminate\Support\Facades\Cache;


class SettingController extends Controller
{
    protected $siteSettingsRepository;
    protected $cityRepository;

    public function __construct(SiteSettingsRepository $siteSettingsRepository) {
        $this->siteSettingsRepository = $siteSettingsRepository;
    }
    public function settings()
    {
        if (Cache::has('settings')) {
            $settings = Cache::get('settings');
        } else {
            $settings = config('settings');
            Cache::add('settings', $settings, 1440);
        }

        $data = [
            'settings' => $settings,
            'locales' => config('app.locales'),
        ];
        return  responseBuilder()->success('settings', ['settings'=>$data]);
    }
}
