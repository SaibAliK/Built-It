<?php

namespace App\Http\Controllers\Admin;

use App\Http\Repositories\SiteSettingsRepository;
use App\Http\Requests\SiteSetting;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class SiteSettingsController extends Controller
{

    protected $siteSettingsRepository;

    public function __construct()
    {
        parent::__construct('adminData', 'admin');
        $this->siteSettingsRepository = new SiteSettingsRepository();
        $this->breadcrumbTitle = 'Site Settings';
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
    }

    public function index()
    {
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-cog', 'title' => 'Site Settings'];
        return view('admin.site_settings.index');
    }

    public function all()
    {
        $response = $this->siteSettingsRepository->allSettings();
        return response()->json($response);

    }

    public function edit($key)
    {
        $this->breadcrumbs[route('admin.dashboard.site-settings.index')] = ['icon' => 'fa fa-fw fa-building', 'title' => 'Site Settings '];
        $result = [];
//        $heading = (($key !== "0") ? 'Edit Languages':'Add Languages');
        if ($key !== "0") {
            $value = config('settings.' . $key);
            $result['key'] = $key;
            $result['value'] = $value;
            $heading = ('Edit ' . $key);
        } else {
            $heading = ('Add Value');
            $result['key'] = "";
            $result['value'] = "";
        }
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-files-o', 'title' => $heading];
        $settings = config('settings');
        unset($settings['created_at'], $settings['updated_at']);
        return view('admin.site_settings.edit', [
            'heading' => $heading,
            'action' => route('admin.dashboard.site-settings.update', ['site_setting' => $key]),
            'result' => $result,
            'settings' => $settings
        ]);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->except('_token');
            $this->siteSettingsRepository->storeSiteSettings($data);
            DB::commit();
            return redirect(route('admin.dashboard.site-settings.index'))->with('status', 'Site Settings Have Been Updated');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('err',$e->getMessage());
        }
    }

    public function delete($key)
    {
        try {
            $this->siteSettingsRepository->deleteSiteSetting($key);
            return response(['msg' => 'Site Setting  Deleted Successfully.']);

        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()]);
        }
    }

    public function destroy($key)
    {
        try {
            $this->siteSettingsRepository->deleteSiteSetting($key);
            return response(['msg' => 'Site Setting  Deleted Successfully.']);

        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()]);
        }
    }

    public function clearRouteCache()
    {
        try {
            $this->siteSettingsRepository->clearRouteCache();
            return response(['msg' => 'Route cache cleared successfully']);
        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()]);
        }
    }

    public function clearStorageCache()
    {
        try {
            $this->siteSettingsRepository->clearStorageCache();
            return response(['msg' => 'Site Setting  Deleted Successfully.']);
        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()]);
        }
    }

    public function clearConfigCache()
    {
        try {
            $this->siteSettingsRepository->clearConfigCache();

            return response(['msg' => 'Config cache cleared successfully']);
        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()]);
        }
    }

    public function clearViewCache()
    {
        try {
            $this->siteSettingsRepository->clearViewCache();
            return response(['msg' => 'View cache cleared successfully']);
        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()]);
        }
    }

    public function tableValues()
    {
        try {
            $this->siteSettingsRepository->updateFile();
            return redirect()->back()->with('status', "values updated successfully.");
        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()]);
        }

    }
}
