<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Dtos\UserRegisterDto;
use App\Http\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Repositories\CityRepository;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    protected $redirectTo = '/verification';
    protected UserRepository $userRepository;
    protected CityRepository $cityRepository;

    public function __construct(UserRepository $userRepository, CityRepository $cityRepository)
    {
        $this->middleware('guest');
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->userRepository->setFromWeb(true);
        $this->cityRepository = $cityRepository;
        $this->breadcrumbs[route('front.index')] = ['title' => __('Home')];
    }
    public function showRegistrationPage()
    {
        $this->breadcrumbTitle = __('Create An Account');
        $this->breadcrumbs['javascript: {};'] = ['title' => __('Create An Account')];
        $this->cityRepository->setSelect([
            'id',
            'name',
            'parent_id'
        ]);
        $cities = $this->cityRepository->setPaginate(0);
        $cities = $this->cityRepository->all();
        return view('front.auth.registration', [
            'cities' => $cities,
        ]);
    }
    public function showRegistrationForm($type)
    {

        if ($type == 'user') {
            $this->breadcrumbTitle = __('Register As A User');
            $this->breadcrumbs['javascript: {};'] = ['title' => __('Register As A User')];
        } elseif ($type == 'supplier') {
            $this->breadcrumbTitle = __('Register As A Supplier');
            $this->breadcrumbs['javascript: {};'] = ['title' => __('Register As A Supplier')];
        } else {
            $this->breadcrumbTitle = __('Register As A Delivery Company');
            $this->breadcrumbs['javascript: {};'] = ['title' => __('Register As A Delivery Company')];
        }
        $cities = $this->cityRepository->all(0);
        return view('front.auth.register', [
            'cities' => $cities,
            'type' => $type
        ]);
    }

    public function register(UserRequest $request)
    {
        try {
            $registerData = UserRegisterDto::fromRequest($request);
            $user = $this->userRepository->save($registerData);
            Auth::guard()->login($user);
            if (!$user->isVerified()) {
                $this->userRepository->sendEmailVerification('verification', $user->email);
            }

            $user = $user->getFormattedModel(true, true);
            session()->flash('status', __('Registration successful.'));
            return redirect(route('front.dashboard.index'));
        } catch (\Exception $e) {
            return redirect()->back()->with('err', $e->getMessage())->withInput();
        }
    }
}
