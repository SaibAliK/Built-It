<?php

namespace App\Http\Controllers\Admin;

use App\Http\Dtos\UserRegisterDto;
use App\Http\Dtos\UserSubscriptionSaveDto;
use App\Http\Repositories\CityRepository;
use App\Http\Repositories\SubscriptionPackageRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Repositories\UserSubscriptionRepository;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\DB;

class SuppliersController extends Controller
{
    protected UserSubscriptionRepository $userSubscriptionRepository;
    protected SubscriptionPackageRepository $subscriptionPackageRepository;
    protected CityRepository $cityRepository;
    protected UserRepository $userRepository;

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
        $this->breadcrumbTitle = "Suppliers";
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
        $this->breadcrumbs[route('admin.dashboard.suppliers.index')] = ['icon' => 'fa fa-fw fa-building', 'title' => 'Manage suppliers'];
    }

    public function index()
    {
        return view('admin.suppliers.index');
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
        $type = 'supplier';
        $store = $this->userRepository->adminDataTable($columns, $type);
        return response($store);
    }

    public function edit($id)
    {
        $heading = (($id > 0) ? 'Edit Store' : 'Add Store');
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

        return view('admin.suppliers.edit', [
            'method' => 'PUT',
            'action' => route('admin.dashboard.suppliers.update', $id),
            'heading' => $heading,
            'user' => $user,
            'cities' => $cities,
            'packages' => $packages,
            'userSubscriptionId' => $subscriptionId
        ]);
    }

    public function update(UserRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $userDto = UserRegisterDto::fromRequest($request);
            $user = $this->userRepository->save($userDto);
            if ($request->type != 'commission') {
                if ($userDto->package_id > 0) {
                    $package = $this->subscriptionPackageRepository->get($userDto->package_id);
                    $newUserSubscriptions = collect([
                        'user_subscription_package_id' => $userDto->package_id,
                        'user_id' => $user->id,
                        'type' => $request->type,
                        'package' => $package,
                        'is_expired' => false,
                        'payment_method' => 'admin',
                        'aed_price' => $package->price,
                        'currency' => 'AED',
                        'commission' => 0,
                    ]);
                } else {
                    if ($request->get('remove_package', 0)) {
                        $this->userSubscriptionRepository->removePackage($user);
                    }
                }
            } else {
                $newUserSubscriptions = collect([
                    'user_subscription_package_id' => $userDto->package_id,
                    'user_id' => $user->id,
                    'type' => $request->type,
                    'package' => null,
                    'is_expired' => false,
                    'payment_method' => 'admin',
                    'aed_price' => 0,
                    'currency' => 'AED',
                    'commission' => config('settings.commission'),
                ]);
            }
            $userSubscriptionDto = UserSubscriptionSaveDto::fromCollection($newUserSubscriptions);
            $this->userSubscriptionRepository->save($userSubscriptionDto);
            DB::commit();
            if ($id == 0) {
                return redirect(route('admin.dashboard.suppliers.index'))->with('status', 'Supplier added successfully');
            } else {
                return redirect(route('admin.dashboard.suppliers.index'))->with('status', 'Supplier updated successfully');
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('err', $e->getMessage());
        }

    }

    public function destroy($id)
    {
        try {
            $this->userRepository->destroy($id);
            return response(['msg' => 'Deleted successfully']);
        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()], 400);
        }
    }

    public function idCardVerify($id)
    {
        try {
            $user = $this->userRepository->verifyIdCard($id);

            if ($user->isSupplier()) {
                return redirect(route('admin.dashboard.suppliers.index'))->with('status', 'Id card image is Verified.');
            } elseif ($user->isCompany()) {
                return redirect(route('admin.dashboard.delivery-companies.index'))->with('status', 'Id card image is Verified.');
            }
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('err', $e->getMessage());
        }

    }
}
