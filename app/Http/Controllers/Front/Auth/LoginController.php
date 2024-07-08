<?php

namespace App\Http\Controllers\Front\Auth;


use App\Http\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
    //    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $userRepository;

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
        parent::__construct();
        $this->userRepository = new UserRepository;
        $this->userRepository->setFromWeb(true);
    }
    protected function login(Request $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            if (auth()->attempt($credentials)) {
                $user = auth()->user();
                $user->checkIfActive();
                $user->getFormattedModel(true, true);
                return redirect(route('front.index'));
            } else {
                throw new \Exception(__('credentials did not match'));
            }
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'email' => [$e->getMessage()],
            ]);
        }

    }
    public function logout(Request $request)
    {
        auth()->logout();
        session()->flash(__('status'), __('Logout Successfully!'));
        session()->forget('USER_DATA');
        session()->forget('USER_DATA');
        return redirect('/' . config('app.locale'));
    }
    public function showLoginForm()
    {
        $this->breadcrumbTitle = __('Log In');
        $this->breadcrumbs[route('front.index')] = ['title' => __('Home')];
        $this->breadcrumbs['javascript: {};'] = ['title' => __('Login')];
        return view('front.auth.login');
    }
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }
    public function redirectTo()
    {
        return config('app.locale') . '/dashboard';
    }

}
