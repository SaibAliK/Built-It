<?php

namespace App\Http\Controllers\Api;


use App\Http\Dtos\UserPasswordChangeDto;
use App\Http\Dtos\UserProfileUpdateDto;
use App\Http\Dtos\UserRegisterDto;
use App\Http\Repositories\FcmRepository;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;


class UsersController extends Controller
{
    public $userRepository, $fcmRepository;

    public function __construct(UserRepository $userRepository, FcmRepository $fcmRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->fcmRepository = $fcmRepository;
    }

    public function login(UserRequest $request)
    {
        try {
            $credentials = $request->only(['email', 'password']);
            $token = JWTAuth::attempt($credentials);
            if ($token) {
                $user = JWTAuth::setToken($token)->toUser();
                $user->checkIfActive();
                if ($request->filled('fcm_token')) {
                    $this->fcmRepository->checkAndSave($request->get('fcm_token'), $user->id);
                }
                return responseBuilder()->success(__('Login successful'), ['user' => $user->getFormattedModel(true, false)]);
            }
            throw new Exception(__('Credentials does not match our records'));
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function social_login(UserRequest $request)
    {
        try {
            return responseBuilder()->success(__('Login successful'), ['user' => $this->userRepository->socialLogin($request)]);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function register(UserRequest $request)
    {
        try {
            $userDto = UserRegisterDto::fromRequest($request);
            $user = $this->userRepository->save($userDto);
            $email = '';
            if (!$user->isVerified()) {
                $email =   $this->userRepository->sendEmailVerification('verification', $user->email);
            }
            $this->fcmRepository->checkAndSave($userDto->fcm_token, $user->id);
            return responseBuilder()->success(__('User Register Successfully'), ['code'=>$email,'user' => $user->getFormattedModel(true, false)]);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }

    }

    public function verifyEmail(UserRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->emailVerification($request->get('verification_code'));
            if ($user) {
                DB::commit();
                return responseBuilder()->success(__('Email verified'), ['user' => $user]);
            }
            throw new Exception(__('Verification code does not match'));
        } catch (Exception $e) {
            DB::rollBack();
            return responseBuilder()->error($e->getMessage());
        }

    }

    public function logout(UserRequest $request)
    {
        $user = auth()->user();
        $this->fcmRepository->delete($user->id, $request->get('fcm_token'));
        return responseBuilder()->success(__('Logout successful'));
    }

    public function resendVerificationCode()
    {
        try {
            $email = $this->userRepository->sendEmailVerification();
            if ($email) {
                return responseBuilder()->success(__('Verification code resent successfully.'), ['code' => $email]);
            }
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }

    }

    public function forgotPassword(UserRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = $this->userRepository->sendEmailVerification('forgot_password', $request->get('email'));
            if ($user) {
                DB::commit();
                return responseBuilder()->success(__('Password reset code has been sent'));
            }
        } catch (Exception $e) {
            DB::rollBack();
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function resetPassword(UserRequest $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'verification_code' => ['required'],
                'email' => ['required', 'email'],
                'password' => ['required', 'confirmed'],
            ]);
            $this->userRepository->resetPassword($request->get('verification_code'), $request->get('email'), $request->get('password'));
            DB::commit();
            return responseBuilder()->success(__('Password reset successfully.'));
        } catch (Exception $e) {
            DB::rollBack();
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function changePassword(UserRequest $request)
    {
        DB::beginTransaction();
        try {
            $passwordDto = UserPasswordChangeDto::fromRequest($request);
            $this->userRepository->changeUserPassword($passwordDto);
            DB::commit();
            return responseBuilder()->success(__('Password successfully updated'));
        } catch (Exception $e) {
            DB::rollBack();
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function editProfile()
    {
        $user = auth()->user();
        return responseBuilder()->success(__('Logged in user'), ['user' => $user->getFormattedModel(true, false)]);
    }

    public function updateProfile(UserRequest $request)
    {
        try {
            $request->merge(['user_id' => auth()->id()]);
            $userDto = UserProfileUpdateDto::fromRequest($request);
            $user = $this->userRepository->save($userDto);
            return responseBuilder()->success(__('Profile Updated Successfully'), ['user' => $user->getFormattedModel(true)]);
        } catch (Exception $e) {
            return responseBuilder()->error(__('Something Went Wrong'));
        }
    }
}
