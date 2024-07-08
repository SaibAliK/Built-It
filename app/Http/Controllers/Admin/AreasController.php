<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Repositories\CityRepository;
use App\Http\Requests\CityRequest;
use App\Models\City;
use Exception;

class AreasController extends Controller
{

    protected CityRepository $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        parent::__construct('adminData', 'admin');
        $this->cityRepository = $cityRepository;
        $this->breadcrumbTitle = 'Delivery Areas';
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
    }

    public function index($id)
    {
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => 'Manage Delivery Areas'];
        return view('admin.areas.subCategory', ['id' => $id]);
    }

    public function show($id)
    {
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => 'Manage Delivery Areas'];
        return view('admin.areas.subCategory', ['id' => $id]);
    }

    public function edit($parentId, $id)
    {
        try {
            $Category = $this->cityRepository->get($parentId);
            $this->breadcrumbTitle = $Category->name['en'];
            $heading = (($id > 0) ? 'Edit Delivery Areas' : 'Add Delivery Areas');
            $this->breadcrumbs[route('admin.dashboard.cities.index')] = ['icon' => 'fa fa-fw fa-building', 'title' => 'Manage Cities'];
            $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => $heading];
            return view('admin.areas.edit', [
                'categoryId' => $id,
                'area' => $this->getViewParamsArea($id),
                'city_id' => $parentId,
                'action' => route('admin.dashboard.cities.areas.update', [$parentId, $id]),
                'parent' => $parentId
            ]);
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }

    private function getViewParamsArea($id)
    {
        $area = new City();

        if ($id > 0) {
            $area = City::findOrFail($id);
        }
        if (is_null($area->polygon)) {
            $area->polygon = json_encode([]);
        } else {
            $area->polygon = json_encode($area->polygon);
        }
        if (is_null($area->name) || !is_array($area->name)) {
            $area->name = ['en' => '', 'ar' => ''];
        } else {
            $name = $area->name;
            if (!isset($name['en'])) {
                $name['en'] = '';
            }
            if (!isset($name['ar'])) {
                $name['ar'] = '';
            }
            $area->name = $name;
        }
        return $area;
    }

    public function update(CityRequest $request, $parent_id, $id)
    {
        try {
            $this->cityRepository->save($request, $id);
            if ($id == 0) {
                return redirect(route('admin.dashboard.cities.index'))->with('status', 'Delivery Areas Added Successfully.');
            } else {
                return redirect(route('admin.dashboard.cities.index'))->with('status', 'Delivery Areas Updated Successfully.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err', $e->getMessage());
        }
    }

    public function destroy($ar, $id)
    {
        try {
            $this->cityRepository->destroyArea($ar, $id);
            return response(['msg' => 'Area is Deleted Successfully.']);
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
