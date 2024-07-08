<?php

namespace App\Http\Controllers\Front\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Dtos\UserFeaturedSubscriptionSaveDto;
use App\Http\Dtos\UserSubscriptionPaymentDto;
use App\Http\Dtos\UserSubscriptionPaymentResponseDto;
use App\Http\Dtos\UserSubscriptionSaveDto;
use App\Http\Repositories\SubscriptionPackageRepository;
use App\Http\Repositories\UserFeaturedSubscriptionRepository;
use App\Http\Repositories\UserSubscriptionRepository;
use App\Http\Requests\UserSubscriptionRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserSubscriptionController extends Controller
{

    public SubscriptionPackageRepository $subscriptionPackageRepository;
    public UserSubscriptionRepository $UserSubscriptionRepository;
    public UserFeaturedSubscriptionRepository $UserFeaturedSubscriptionRepository;


    public function __construct(UserSubscriptionRepository $UserSubscriptionRepository, SubscriptionPackageRepository $subscriptionPackageRepository)
    {
        parent::__construct();
        $this->UserSubscriptionRepository = $UserSubscriptionRepository;
        $this->subscriptionPackageRepository = $subscriptionPackageRepository;
        $this->UserFeaturedSubscriptionRepository = new UserFeaturedSubscriptionRepository();
    }

    public function checkStream(Request $request)
    {
        if ($request->type == 'commission') {
            $newUserSubscriptionCollection = collect([
                'user_subscription_package_id' => 0,
                'user_id' => $this->user->id,
                'type' => 'commission',
                'is_expired' => false,
                'payment_method' => 'admin',
                'aed_price' => 0,
                'currency' => 'AED',
                'commission' => config('settings.commission'),
            ]);
            $newUserSubscriptionDto = UserSubscriptionSaveDto::fromCollection($newUserSubscriptionCollection);
            $this->UserSubscriptionRepository->save($newUserSubscriptionDto);
            DB::commit();
            return redirect()->route('front.dashboard.index')->with('status', __('Subscription Package Successfully Purchased'));
        } else {
            return redirect()->route('front.dashboard.packages.index');
        }
    }

    public function subscribe(UserSubscriptionRequest $request)
    {
        DB::beginTransaction();
        try {
            $package = $this->subscriptionPackageRepository->get($request->get('package_id'));
            if ($package->isFree()) {
                $newUserSubscriptionCollection = collect([
                    'user_subscription_package_id' => 0,
                    'user_id' => $this->user->id,
                    'type' => $request->type,
                    'package' => $package,
                    'is_expired' => false,
                    'payment_method' => 'free',
                    'aed_price' => $package->price,
                    'currency' => 'SAR',
                    'commission' => '0',
                ]);
                $newUserSubscriptionDto = UserSubscriptionSaveDto::fromCollection($newUserSubscriptionCollection);
                $this->UserSubscriptionRepository->save($newUserSubscriptionDto);
                DB::commit();
                return redirect()->route('front.dashboard.index')->with('status', __('Subscription Package Successfully Purchased'));
            } else {
                $newPayment = collect([
                    'package_id' => $package->id,
                    'name' => $package->name['en'],
                    'price' => $package->price,
                    'type' => $request->type,
                    'commission' => '0',
                ]);
                $SubscriptionPaymentDetails = UserSubscriptionPaymentDto::fromCollection($newPayment);
                $url = $this->UserSubscriptionRepository->subscribeToPackage($SubscriptionPaymentDetails);
                DB::commit();
                return redirect($url);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect(route('front.dashboard.packages.index'))->with('err', $e->getMessage());
        }
    }

    public function paymentResponse(Request $request)
    {
        DB::beginTransaction();
        try {
            $SubscriptionPaymentDetails = UserSubscriptionPaymentResponseDto::fromRequest($request);
            $subscription = $this->UserSubscriptionRepository->paymentResponse($SubscriptionPaymentDetails);
            DB::commit();
            return redirect(route('front.dashboard.index'))->with('status', __('Subscription Package Successfully Purchased'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect(route('front.dashboard.packages.index'))->with('err', $e->getMessage());
        }
    }

    public function featuredSubscribe(UserSubscriptionRequest $request)
    {
        DB::beginTransaction();
        try {
            $package = $this->subscriptionPackageRepository->get($request->get('package_id'));
            if ($package->isFree()) {
                $newUserFeaturedSubscriptionCollection = collect([
                    'user_subscription_package_id' => 0,
                    'user_id' => $this->user->id,
                    'package' => $package,
                    'is_expired' => false,
                    'payment_method' => 'free',
                    'aed_price' => $package->price,
                    'currency' => 'SAR',
                ]);
                $newUserFeaturedSubscriptionDto = UserFeaturedSubscriptionSaveDto::fromCollection($newUserFeaturedSubscriptionCollection);
                $this->UserFeaturedSubscriptionRepository->save($newUserFeaturedSubscriptionDto);
                DB::commit();
                return redirect()->route('front.dashboard.featured.packages.index', ['type' => 'featured'])->with('status', __('Subscription Package Successfully Purchased'));
            } else {
                $newPayment = collect([
                    'package_id' => $package->id,
                    'name' => $package->name['en'],
                    'price' => $package->price,
                ]);
                $SubscriptionPaymentDetails = UserSubscriptionPaymentDto::fromCollection($newPayment);
                $url = $this->UserFeaturedSubscriptionRepository->subscribeToPackage($SubscriptionPaymentDetails);
                DB::commit();
                return redirect($url);
            }
        } catch (Exception $e) {
            DB::rollBack();
            return redirect(route('front.dashboard.featured.packages.index', ['type' => 'featured']))->with('err', $e->getMessage());

        }
    }

    public function featuredPaymentResponse(Request $request)
    {
        DB::beginTransaction();
        try {
            $SubscriptionPaymentDetails = UserSubscriptionPaymentResponseDto::fromRequest($request);
            $subscription = $this->UserFeaturedSubscriptionRepository->paymentResponse($SubscriptionPaymentDetails);
            DB::commit();
            return redirect(route('front.dashboard.featured.packages.index', ['type' => 'featured']))->with('status', __('Featured Package Successfully Purchased'));
        } catch (Exception $e) {
            DB::rollBack();
            return redirect(route('front.dashboard.featured.packages.index', ['type' => 'featured']))->with('err', $e->getMessage());
        }


    }
}
