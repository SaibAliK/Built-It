<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveUser;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\UserRequest;
use Exception;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset emails and
      | includes a trait which assists in sending these notifications from
      | your application to your users. Feel free to explore this trait.
      |
     */

//use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $userRepository;
    public function __construct() {
        $this->middleware('guest');
        parent::__construct();
        $this->userRepository = new UserRepository;
        $this->userRepository->setFromWeb(true);
    }

    public function showLinkRequestForm()
    {
        $this->breadcrumbTitle = __('Forgot Password');
        $this->breadcrumbs[route('front.index')] = ['title' => __('Home')];
        $this->breadcrumbs['javascript: {};'] = ['title' => __('Forgot Password')];
        return view('front.auth.forgot-password');
    }

    public function forgotPassword(UserRequest $request)
    {
        try {
            $user = $this->userRepository->sendEmailVerification('forgot_password', $request->get('email'));
            if ($user) {
                session()->put('email', $request->email);
                session(['code' => $user]);
                return redirect(route('front.auth.show.reset.form'))->with('status', __('Password reset code has been sent',['code'=>$user]));
            }
        }catch (Exception $e){
            return redirect()->back()->with('err', $e->getMessage());
        }

    }

    public function forgotPasswordResend(Request $request)
    {
        try {
            $email = $this->userRepository->sendEmailVerification('forgot_password', $request->get('email'));
            if ($email) {
                return  $email;
//                return responseBuilder()->success(__('Password reset code resent successfully.',['code']));
            }
        }catch (Exception $e){
            return responseBuilder()->error($e->getMessage());
        }
    }
}
