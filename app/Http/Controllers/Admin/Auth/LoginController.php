<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /*
      |--------------------------------------------------------------------------
      | Login Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles authenticating users for the application and
      | redirecting them to your home screen. The controller uses a trait
      | to conveniently provide its functionality to your applications.
      |
     */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $modules;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct('adminData', 'admin');
        $this->middleware('guest:admin', ['except' => 'logout']);
    }

    public function redirectTo()
    {
        return route('admin.dashboard.index');
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (auth('admin')->attempt($credentials, $request->get('remember'))) {
            $admin = auth('admin')->user();
            if (!$admin->isActive()) {
                auth('admin')->logout();
                throw ValidationException::withMessages([
                    'email' => 'Your account is blocked',
                ]);
            }
            $admin['token'] = $admin->createToken('auth_token')->plainTextToken;
            session()->put('ADMIN_DATA', $admin->toArray());
            return redirect()->intended(route('admin.dashboard.index'));
        }
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function logout(Request $request)
    {
        $admin = session('ADMIN_DATA');
        if (isset($admin['token'])) {
            $tokenId = explode('|', $admin['token'])[0];
            auth('admin')->user()->tokens()->where('id', $tokenId)->delete();
        }
        auth('admin')->logout();
        session()->forget('ADMIN_DATA');
        return redirect(route('admin.auth.login.show-login-form'));
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string|min:6',
        ]);
    }

}
