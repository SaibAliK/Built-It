<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;

//use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Http\Repositories\UserRepository;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset requests
      | and uses a simple trait to include this behavior. You're free to
      | explore this trait and override any methods you wish to tweak.
      |
     */
    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    public function redirectTo()
    {
        return route('front.auth.login');
    }

    public function showResetForm()
    {
        $this->breadcrumbTitle = __('Reset Password');
        $this->breadcrumbs[0] = ['url' => route('front.index'), 'title' => __('Home')];
        $this->breadcrumbs[1]  = ['url' => '', 'title' => __('Reset Password')];
        return view('front.auth.reset-password', ['email' => session('email'),'code' => session('code')]);
    }

    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Handle an incoming new password request.
     *
     * @param Request $request
     * @return RedirectResponse
     *
     * @throws ValidationException
     */
    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'verification_code' => ['required'],
                'password' => ['required', 'confirmed']
            ]);
            $this->userRepository->resetPassword($request->get('verification_code'), null, $request->get('password'));
            return redirect(route('front.auth.login'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput($request->only('email'))->withErrors(['verification_code' => __($e->getMessage())]);
        }
    }

    protected function rules()
    {
        return [
            'verification_code' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }

    public function broker()
    {
        return Password::broker();
    }
}
