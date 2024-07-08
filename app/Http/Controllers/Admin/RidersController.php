<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Repositories\UserRepository;
use  App\Http\Repositories\CityRepository;
use App\Http\Requests\UserRequest;
use App\Http\Dtos\UserRegisterDto;

class RidersController extends Controller
{

    public function __construct(UserRepository $userRepository, CityRepository $cityRepository)
    {
        parent::__construct('adminData', 'admin');
        $this->userRepository = $userRepository;
        $this->cityRepository = $cityRepository;
        $this->breadcrumbTitle = "Riders";
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
        $this->breadcrumbs[route('admin.dashboard.riders.index')] = ['icon' => 'fa fa-fw fa-building', 'title' => 'Manage riders'];
    }

    public function index()
    {
        return view('admin.riders.index');
    }

    public function all()
    {
        $columns = [
            ['db' => 'id', 'dt' => 'id'],
            ['db' => 'supplier_name', 'dt' => 'supplier_name'],
            ['db' => 'email', 'dt' => 'email'],
            ['db' => 'city_id', 'dt' => 'city_id'],
            ['db' => 'rating', 'dt' => 'rating'],
            ['db' => 'user_type', 'dt' => 'user_type'],
            ['db' => 'company_id', 'dt' => 'company_id'],
            ['db' => 'is_id_card_verified', 'dt' => 'is_id_card_verified'],
        ];
        $type = 'rider';
        $store = $this->userRepository->adminDataTable($columns, $type);
        return response($store);
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $heading = (($id > 0) ? 'Edit Store' : 'Add Rider');
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => $heading];
        $this->cityRepository->setSelect([
            'id',
            'name',
            'company_id'
        ]);
        $this->cityRepository->setPaginate(0);
        $cities = $this->cityRepository->all();

        $this->userRepository->setRelations(['city']);
        $user = $this->userRepository->get($id);
        $deliveryCompanese = $this->userRepository->getDeliveryCompany();

        return view('admin.riders.edit', [
            'method' => 'PUT',
            'action' => route('admin.dashboard.riders.update', $id),
            'heading' => $heading,
            'user' => $user,
            'cities' => $cities,
            'deliveryCompanese' => $deliveryCompanese
        ]);
    }


    public function update(UserRequest $request, $id)
    {
        try {
            $request->merge(['user_id' => $id]);
            $request->merge(['user_type' => 'rider']);
            $productDto = UserRegisterDto::fromRequest($request);
            $this->userRepository->save($productDto);
            if ($id == 0) {
                return redirect(route('admin.dashboard.riders.index'))->with('status', 'Rider added successfully.');
            }
            return redirect(route('admin.dashboard.riders.index'))->with('status', 'Rider updated successfully.');
        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()]);
        }
    }


    public function destroy($id)
    {
        //
    }
}
