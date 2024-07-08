<?php

namespace App\Http\Controllers\Front\Dashboard;

use App\Http\Dtos\UserPasswordChangeDto;
use App\Http\Dtos\UserProfileUpdateDto;
use App\Http\Dtos\UserRegisterDto;
use App\Http\Repositories\CityRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Exception;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    protected object $userRepository, $cityRepository;

    public function __construct(UserRepository $userRepository, CityRepository $cityRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->cityRepository = $cityRepository;
        $this->breadcrumbs[route('front.index')] = ['title' => __('Home')];
    }

    public function index()
    {
        $this->breadcrumbTitle = __('Profile');
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => __('Profile')];
        $this->userRepository->setSelect([
            'id',
            'city_id',
            'supplier_name',
            'rating',
            'email',
            'phone',
            'user_type',
            'address',
            'latitude',
            'longitude',
            'image',
            'id_card_images',
            'is_id_card_verified',
            'about',
            'expiry_date',
            'user_name',
        ]);
        if ($this->user->isSupplier()) {
            $this->userRepository->setRelations(['city' => function ($q) {
                $q->select(['id', 'parent_id', 'name']);
            }]);
        }
        $user = $this->userRepository->get(auth()->id());
        $users = $user->getFormattedModel();
//        dd($user->getFormattedModel());
        return view('front.dashboard.profile.index', compact('users'));
    }

    public function edit()
    {
        $this->breadcrumbTitle = __('Edit profile');
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => __('Edit profile')];
        $this->userRepository->setSelect([
            'id',
            'city_id',
            'email',
            'phone',
            'user_type',
            'address',
            'latitude',
            'longitude',
            'image',
            'id_card_images',
            'about',
            'expiry_date',
            'supplier_name',
            'user_name',
        ]);
        if ($this->user->isSupplier()) {
            $this->userRepository->setRelations(['city']);
        }
        $user = $this->userRepository->get($this->user->id);


        $this->cityRepository->setSelect([
            'id',
            'name',
        ]);
        $this->cityRepository->setPaginate(0);
        $cities = $this->cityRepository->all(0);
        return view('front.dashboard.profile.form', ['user' => $user->getFormattedModel(), 'cities' => $cities]);
    }

    public function update(UserRequest $request)
    {
        try {
            $request->merge(['user_id' => $this->user->id]);
            $userDto = UserProfileUpdateDto::fromRequest($request);
            $this->userRepository->save($userDto);
            return redirect(route('front.dashboard.index'))->with('status', __('Profile Updated Successfully'));
        } catch (Exception $e) {
            return redirect()->back()->with('err', __('Something Went Wrong'));
        }
    }

    public function editPassword()
    {
        $this->breadcrumbTitle = __('Settings');
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => __('Settings')];
        return view('front.dashboard.password.form');
    }

    public function updatePassword(UserRequest $request)
    {
        DB::beginTransaction();
        try {
            $passwordDto = UserPasswordChangeDto::fromRequest($request);
            $this->userRepository->changeUserPassword($passwordDto);
            DB::commit();
            return redirect(route('front.dashboard.index'))->with('status', __('Password successfully updated'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('err', $e->getMessage());
        }
    }
}
