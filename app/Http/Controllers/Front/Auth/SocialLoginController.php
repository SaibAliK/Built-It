<?php

namespace App\Http\Controllers\Front\Auth;
use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Tymon\JWTAuth\Facades\JWTAuth;

class SocialLoginController extends Controller
{
//    use RegistersUsers;
    protected $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository;
    }

    public function redirect($service)
    {
        session()->put('user_locale',app()->getLocale());
        return Socialite::driver($service)->redirect();
    }

    public function callback(Request $request, $service)
    {
        if (!$request->has('code') || $request->has('denied')) {
            return redirect(route('front.auth.login'))->with('err', __('something went wrong'));
        }
        try {
            $user = Socialite::driver($service)->user();
        } catch (InvalidStateException $e) {
            $user = Socialite::driver($service)->stateless()->user();
        }

        $requestData = array();
        if ($service == "google") {
            $requestData["google_id"] = $user->id;
            $requestData["email"] = $user->email;
            $requestData["first_name"] = $user->user['given_name'];
            $requestData["last_name"] = '';
        } elseif ($service == "facebook") {
            $requestData["facebook_id"] = $user->id;
            $requestData["email"] = $user->email;
            $fullname = explode(' ', $user->name);
            $requestData["first_name"] = $fullname[0];
            $requestData["last_name"] = $fullname[1];
        }
        $userdata = $this->userSocialLogin($requestData);
        if (!$userdata) {
            return redirect(route('front.auth.register', ['platform' => $service, 'id' => $user->id, 'email' => $requestData['email'], 'full_name' => $requestData['first_name']." ".$requestData['last_name']]));
        }else{
            return redirect(route( 'front.dashboard.index'));
        }
    }
    public function userSocialLogin($requestData)
    {
        $socialLoginColumn = '';
        if (!empty($requestData['facebook_id'])) {
            $socialLoginColumn = 'facebook_id';
        }
        if (!empty($requestData['google_id'])) {
            $socialLoginColumn = 'google_id';
        }
        $userExists = $this->userRepository->getModel()->where([$socialLoginColumn => $requestData[$socialLoginColumn]])->first();
        if (!$userExists){
            if (isset($requestData['email']) && !empty($requestData['email'])){
                $userExists = $this->userRepository->get(0,$requestData['email']);
            }
        }

        if ($userExists) {
            $userExists->update([$socialLoginColumn => $requestData[$socialLoginColumn]]);
            Auth::loginUsingId($userExists->id);
            $user = $userExists->getFormattedModel(true,true);
            session()->flash(__('status'), __('Login Successful'));
            return $user;
        }
        return false;
    }
}
