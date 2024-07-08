<?php

namespace App\Http\Controllers\Front\Dashboard;

use App\Http\Dtos\UserRegisterDto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\CityRepository;
use App\Http\Repositories\UserRepository;

class RiderController extends Controller
{
    public UserRepository $userRepository;
    public CityRepository $cityRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->cityRepository = new CityRepository();
        $this->breadcrumbs[route('front.dashboard.index')] = ['title' => __('Dashboard')];
    }

    public function index()
    {
        $this->breadcrumbTitle = __('Manage Riders');
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => __('Manage Riders')];
        $this->userRepository->setSelect([
            'id',
            'city_id',
            'supplier_name',
            'email',
            'phone',
            'user_type',
            'address',
            'latitude',
            'longitude',
        ]);
        if ($this->user->isSupplier()) {
            $this->userRepository->setRelations(['city' => function ($q) {
                $q->select(['id', 'parent_id', 'name']);
            }]);
        }
        $user = $this->userRepository->get($this->user->id);

        return view('front.dashboard.rider.index', ['user' => $user->getFormattedModel()]);
    }

    public function edit($id)
    {
        $heading = (($id > 0) ? 'Edit Rider' : 'Add Rider');
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => $heading];
        $this->cityRepository->setSelect([
            'id',
            'name',
            'parent_id'
        ]);
        $this->cityRepository->setPaginate(0);
        $cities = $this->cityRepository->all();
        $user = $this->userRepository->get($id);
        return view('front.dashboard.rider.edit', [
            'method' => 'POST',
            'action' => route('front.dashboard.update.rider', $id),
            'heading' => $heading,
            'user' => $user,
            'cities' => $cities,
        ]);


    }

    public function Update(Request $request)
    {
        try {
//            $request->merge(['user_id' => $id]);
//            $riderDto = UserRegisterDto::fromRequest($request);
//            $this->userRepository->save($riderDto);
//            if ($id == 0) {
//                return redirect(route('front.dashboard.riders'))->with('status', 'Rider added successfully.');
//            }
            return redirect(route('front.dashboard.riders'))->with('status', 'Rider updated successfully.');
        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
//        $this->userRepository->destroy($id);
//        return response(['msg' => 'Rider is Deleted Successfully.']);
    }
}
