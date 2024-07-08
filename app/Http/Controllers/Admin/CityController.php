<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Repositories\CityRepository;
use App\Http\Requests\CityRequest;
use App\Models\City;
use Exception;


class CityController extends Controller
{
    public CityRepository $cityRepository;

    public function __construct()
    {
        parent::__construct('adminData', 'admin');
        $this->breadcrumbTitle = 'Cities';
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
        $this->cityRepository = new CityRepository();
    }

    public function index()
    {
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => 'Manage Cities'];
        $cities = $this->cityRepository->all(true);
        return view('admin.cities.index', ['cities' => $cities]);
    }

    public function edit($id)
    {
        $heading = (($id > 0) ? 'Edit City' : 'Add City');
        $this->breadcrumbs[route('admin.dashboard.cities.index')] = ['icon' => 'fa fa-fw fa-building', 'title' => 'Manage Cities'];
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => $heading];
        $city = $this->cityRepository->get($id);
        return view('admin.cities.edit', [
            'cityId' => $id,
            'city' => $city,
            'action' => route('admin.dashboard.cities.update', $id)
        ]);
    }

    public function update(CityRequest $request, $id)
    {
        try {
            $this->cityRepository->update($request, $id);
            if ($id == 0) {
                return redirect(route('admin.dashboard.cities.index'))->with('status', 'City is Added Successfully.');
            } else {
                return redirect(route('admin.dashboard.cities.index'))->with('status', __('City is Updated Successfully.'));
            }
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }

    }

    public function destroy($id)
    {
        try {
            $this->cityRepository->destroy($id);
            return response(['msg' => 'City is Deleted Successfully.']);
        } catch (Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
}
