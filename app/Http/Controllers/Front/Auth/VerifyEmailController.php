<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\UserRequest;
use Exception;

class VerifyEmailController extends Controller
{
    public $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->userRepository->setFromWeb(true);
        parent::__construct();
    }

    public function emailVerificationForm()
    {
        $this->breadcrumbTitle = __('Email Verification');
        $this->breadcrumbs[route('front.index')] = ['title' => __('Home')];
        $this->breadcrumbs['javascript: {};'] = ['title' => __('Email Verification')];
        if (auth()->user()->isVerified()) {
            return redirect(route('front.dashboard.index'))->with('err', __('Your Account is Already Verified'));
        }
        return view('front.auth.email-verify');
    }

    public function emailVerificationResend()
    {
        try {
            $email = $this->userRepository->sendEmailVerification();

            if ($email) {
                return responseBuilder()->success(__('Verification code resent successfully.'),['code'=>$email]);
            }
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function emailVerificationPost(UserRequest $request)
    {
        try {
            $revenue_stream = config('settings.revenue_stream');
            $user = $this->userRepository->emailVerification($request->get('verification_code'));
            if ($user) {
                if ($user->isSupplier()) {
                    if ($revenue_stream == "commission") {
                        return redirect(route('front.dashboard.subscription.type'))->with('status', __('Email Verified'));
                    } else {
                        return redirect(route('front.dashboard.packages.index'))->with('status', __('Email Verified'));
                    }
                } else {
                    return redirect()->route('front.dashboard.index')->with('status', __('Email Verified'));
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());
        }
    }
}
