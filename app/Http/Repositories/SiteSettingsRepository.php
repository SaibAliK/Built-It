<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Setting;
use Artisan;

class SiteSettingsRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(new Setting());
    }

    public function arrayFlatten($array, $netKey = '')
    {
        if (!is_array($array)) {
            return $array;
        }
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = array_merge($result, $this->arrayFlatten($value, $netKey . $key . '.'));
            } else {
                $result[$netKey . $key] = $value;
            }
        }
        return $result;
    }

    public function allSettings()
    {
        $settings = config('settings');
        $response = [];
        $data = $this->arrayFlatten($settings);
        ((\request('datatable.sort.sort') == "desc") ? arsort($data) : ksort($data));
        if (!empty(\request('datatable.query.key'))) {
            foreach ($data as $settingsKey => $settingsValue) {
                if (strpos($settingsKey, request('datatable.query.key')) !== false) {
                    unset($data);
                    $data[$settingsKey] = $settingsValue;
                }
            }
        }
//        $length = \Illuminate\Support\Facades\Request::input('datatable.pagination.perpage', 10);
//        $draw = \Illuminate\Support\Facades\Request::input('datatable.pagination.page', 1);
//        if (\Illuminate\Support\Facades\Request::input('datatable.pagination.page', 1) > 1) {
//            $start = (\Illuminate\Support\Facades\Request::input('datatable.pagination.page', 1) * $length) - $length;
//        } else {
//            $start = 0;
//        }
//        $recordsTotal = count($data);
//        if ($recordsTotal > 0) {
//            $pages = $recordsTotal / $length;
//        } else {
//            $pages = 0;
//        }
//        $slicedData = [];
//        $count = 0;
//        if ($recordsTotal < $length){
//            $start = 0;
//        }

//        $perPage = \request('datatable.pagination.perpage', 1);
//        $page = \request('datatable.pagination.page', 1);
//        $perPage = ($page * $perPage) - $perPage;

        $length = \Illuminate\Support\Facades\Request::input('datatable.pagination.perpage', 10);
        $draw = \Illuminate\Support\Facades\Request::input('datatable.pagination.page', 1);
//        $start = \Illuminate\Support\Facades\Request::input('datatable.pagination.start', 0);
        if(\Illuminate\Support\Facades\Request::input('datatable.pagination.page', 1) > 1){
            $start = (\Illuminate\Support\Facades\Request::input('datatable.pagination.page', 1) * $length) - $length;
        }else{
            $start = 0;
        }
        $recordsTotal = count($data);

        if ($recordsTotal > 0){
            $pages = $recordsTotal/$length ;
            $pages = ceil($pages);
        }
        else{
            $pages = 0 ;
        }
        $slicedData = [];

//        $perPage = \request('datatable.pagination.perpage', 1);
//        $page = \request('datatable.pagination.page', 1);

//        if the user is on page other then 1st.
//        and changes the page size, this will reset the table and re-enter the table using new page size.
//        and start the pagination from 1, with new page size.
//        other wise it does not show any data due to array_slice which sets the offset to 200, if 100 page size is set, and we do not have 200+ site settings.
//        dd($draw, $length, $recordsTotal,$start, $pages);
        if ($draw > $pages){
            $start = 0;
            $draw = 1;
        }
        $count = $start;
        if ($draw == 1){
            $count = 1;
        }

        if ($start > 0 && $draw > 1){
            $count = $count + 1;
        }



        foreach (array_slice($data, $start, $length) as $key => $value) {
            if ($key == 'email_header' && !is_null($value)) {
                $value = '<img src="' . url($value) . '" style="height:100px;">';
            }
            $slicedData[] = [
                "id" => $count ++,
                "key" => $key,
                "value" => $value,
               "actions" => '<a href="' . route('admin.dashboard.site-settings.edit', $key) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-fw fa-edit"></i></i></a>'
                   . '<a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete-record-button" href="javascript:{};" data-url="' . route('admin.dashboard.site-settings.destroy', $key) . '" title="Delete"><i class="fa fa-fw fa-trash-o"></i></a>'
            ];
        }
        $meta = [
            "page" => intval($draw),
            "pages" => intval($pages),
            "total" => intval($recordsTotal),
            "perpage" => intval($length),
            "start" => intval($start)
        ];
        $response['meta'] = $meta;
        $response['data'] = $slicedData;
        return $response;
    }

    public function storeSiteSettings($data)
    {
        $id = 1;
        if (isset($data['id'])){
            $id = $data['id'];
        }
        $settings = $this->getModel()->updateOrCreate(['id' => $id], $data);
        if ($settings){
            $this->updateFile($settings->toArray());
            return true;
        }

//        $siteSettings = $request->only(['key', 'value', 'image']);
//
//        if ($request->key == 'logo' || $request->key == 'login_page_image' || $request->key == 'email_logo') {
//            if (empty($request->image)) {
//                $siteSettings['value'] = config('settings.' . $request->key);
//            } else {
//                $siteSettings['value'] = $request->image;
//            }
//        }
//        if ($request->key == 'service_fee') {
//            Product::query()->update(['service_fee' => $request->value]);
//        }
//
//        \Config::set('settings.' . $siteSettings['key'], $siteSettings['value']);
//        $settings = serialize(config('settings'));
//        $file = base_path('config/settings.php');
//        $data = "<?php return unserialize(base64_decode('" . base64_encode($settings) . "'));";
//        file_put_contents($file, $data);
//        Artisan::call('config:clear');
//        \Cache::forget('settings');
    }

    public function deleteSiteSetting($key)
    {
        $settings = config('settings');
        array_forget($settings, $key);
        $settings = serialize($settings);
        $file = base_path('config/settings.php');
        $data = "<?php return unserialize(base64_decode('" . base64_encode($settings) . "'));";
        file_put_contents($file, $data);
        Artisan::call('config:clear');
        \Cache::forget('settings');
    }

    public function clearRouteCache()
    {
        Artisan::call('route:clear');
    }

    public function clearStorageCache()
    {
        Artisan::call('cache:clear');
    }

    public function clearConfigCache()
    {
        Artisan::call('config:clear');
    }

    public function clearViewCache()
    {
        Artisan::call('view:clear');
    }

    public function updateFile($setting = null){
        if (is_null($setting)){
            $setting = Setting::first()->toArray();
        }
        unset($setting['created_at'],$setting['updated_at']);
        if (count($setting) > 0){
            unset($setting['created_at'],$setting['updated_at']);
            \Config::set('settings', $setting);
            $settings = serialize(config('settings'));
            $file = base_path('config/settings.php');
            $data = "<?php return unserialize(base64_decode('" . base64_encode($settings) . "'));";
            file_put_contents($file, $data);
            Artisan::call('config:clear');
            \Cache::forget('settings');
            return true;
        }
        return false;

    }

}
