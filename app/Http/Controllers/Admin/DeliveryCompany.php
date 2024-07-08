<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Dtos\UserRegisterDto;
use App\Http\Dtos\UserSubscriptionSaveDto;
use App\Http\Repositories\CityRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Repositories\UserSubscriptionRepository;
use App\Http\Repositories\SubscriptionPackageRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeliveryCompany extends Controller
{
    protected CityRepository $cityRepository;
    protected UserRepository $userRepository;
    protected UserSubscriptionRepository $userSubscriptionRepository;
    protected SubscriptionPackageRepository $subscriptionPackageRepository;

    public function __construct(UserRepository $userRepository, CityRepository $cityRepository, SubscriptionPackageRepository $subscriptionPackageRepository, UserSubscriptionRepository $userSubscriptionRepository)
    {
        parent::__construct('adminData', 'admin');
        $this->userRepository = $userRepository;
        $this->subscriptionPackageRepository = $subscriptionPackageRepository;
        $this->cityRepository = $cityRepository;
        $this->userSubscriptionRepository = $userSubscriptionRepository;
        $this->userRepository->setFromWeb(true);
        $this->cityRepository->setFromWeb(true);
        $this->subscriptionPackageRepository->setFromWeb(true);
        $this->userSubscriptionRepository->setFromWeb(true);
        $this->userSubscriptionRepository->setFromAdmin(true);
        $this->breadcrumbTitle = "Delivery Companies";
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
        $this->breadcrumbs[route('admin.dashboard.delivery-companies.index')] = ['icon' => 'fa fa-fw fa-building', 'title' => 'Manage delivery companies'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('admin.delivery-companies.index');
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
            ['db' => 'is_id_card_verified', 'dt' => 'is_id_card_verified'],
        ];
        $type = 'company';
        $store = $this->userRepository->adminDataTable($columns, $type);
        // dd($store);
        return response($store);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {

        $heading = (($id > 0) ? 'Edit Delivery Company' : 'Add Delivery Company');
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => $heading];
        $this->cityRepository->setSelect([
            'id',
            'name'
        ]);
        $this->cityRepository->setPaginate(0);
        $cities = $this->cityRepository->all(0);

        $this->subscriptionPackageRepository->setSelect([
            'id',
            'name',
        ]);
        $this->subscriptionPackageRepository->setPaginate(0);
        $packages = $this->subscriptionPackageRepository->all();
        $this->userRepository->setRelations(['city', 'subscription']);
        $user = $this->userRepository->get($id);

        $subscriptionId = 0;
        if ($id > 0) {
            $userSubscription = $user->subscription;
            if (isset($userSubscription->package)) {
                if (!$userSubscription->is_expired) {
                    $subscriptionId = $userSubscription->package['id'];
                }
            }
            if (is_null($user)) {
                return redirect(route('admin.stores.index'))->with('err', 'The selected store no longer exists.');
            }
        }
        return view('admin.delivery-companies.edit', [
            'method' => 'PUT',
            'action' => route('admin.dashboard.delivery-companies.update', $id),
            'heading' => $heading,
            'user' => $user,
            'cities' => $cities,
            'packages' => $packages,
            'userSubscriptionId' => $subscriptionId
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        DB::beginTransaction();
        try {
            $userDto = UserRegisterDto::fromRequest($request);
            $user = $this->userRepository->save($userDto);

//            if ($userDto->package_id > 0) {
//                $package = $this->subscriptionPackageRepository->get($userDto->package_id);
//                $newUserSubscriptions = collect([
//                    'user_subscription_package_id' => $userDto->package_id,
//                    'user_id' => $user->id,
//                    'package' => $package,
//                    'is_expired' => false,
//                    'payment_method' => 'admin',
//                    'aed_price' => $package->price,
//                    'currency' => 'AED',
//                ]);
//                $userSubscriptionDto = UserSubscriptionSaveDto::fromCollection($newUserSubscriptions);
//                $this->userSubscriptionRepository->save($userSubscriptionDto);
//            } else {
//                if ($request->get('remove_package', 0)) {
//                    $this->userSubscriptionRepository->removePackage($user);
//                }
//            }
            DB::commit();
            if ($id == 0) {
                return redirect(route('admin.dashboard.delivery-companies.index'))->with('status', 'Company added successfully');
            } else {
                return redirect(route('admin.dashboard.delivery-companies.index'))->with('status', 'Company updated successfully');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('err', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $this->userRepository->destroy($id);
            return response(['msg' => 'Deleted successfully']);
        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()], 400);
        }
    }


}
