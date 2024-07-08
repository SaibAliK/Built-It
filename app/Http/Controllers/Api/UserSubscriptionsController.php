<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Dtos\UserFeaturedSubscriptionSaveDto;
use App\Http\Dtos\UserSubscriptionPaymentDto;
use App\Http\Dtos\UserSubscriptionPaymentResponseDto;
use App\Http\Dtos\UserSubscriptionSaveDto;
use App\Http\Repositories\SubscriptionPackageRepository;
use App\Http\Repositories\UserFeaturedSubscriptionRepository;
use App\Http\Repositories\UserSubscriptionRepository;
use App\Http\Requests\UserSubscriptionRequest;
use App\Models\UserFeaturedSubscription;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserSubscriptionsController extends Controller
{

    protected SubscriptionPackageRepository $subscriptionPackageRepository;
    protected UserSubscriptionRepository $userSubscriptionRepository;
    protected UserFeaturedSubscriptionRepository $userFeaturedSubscriptionRepository;
    protected UserSubscriptionRepository $subscriptionRepository;

    public function __construct()
    {
        $this->userSubscriptionRepository = new UserSubscriptionRepository();
        $this->subscriptionPackageRepository = new SubscriptionPackageRepository();
        $this->userFeaturedSubscriptionRepository = new UserFeaturedSubscriptionRepository();
        $this->userSubscriptionRepository = new UserSubscriptionRepository();
    }

    public function paymentResponse(UserSubscriptionRequest $request)
    {
        DB::beginTransaction();
        try {
            $package = $this->subscriptionPackageRepository->get($request->get('package_id'));
            if ($package->isFree()) {
                $newUserSubscriptionCollection = collect([
                    'user_subscription_package_id' => 0,
                    'user_id' => auth()->id(),
                    'package' => $package,
                    'is_expired' => false,
                    'payment_method' => 'free',
                    'aed_price' => $package->price,
                    'currency' => 'AED',
                ]);
                $newUserSubscriptionDto = UserSubscriptionSaveDto::fromCollection($newUserSubscriptionCollection);;
                $this->userSubscriptionRepository->save($newUserSubscriptionDto);
                DB::commit();
                return responseBuilder()->success(__('Subscription Package Successfully Purchased'));
            }
            $SubscriptionPaymentDetails = UserSubscriptionPaymentResponseDto::fromRequest($request);
            $subscription = $this->userSubscriptionRepository->paymentResponse($SubscriptionPaymentDetails);
            DB::commit();
            return responseBuilder()->success(__('Subscription Package Successfully Purchased'));
        } catch (Exception $e) {
            DB::rollBack();
            return responseBuilder()->error($e->getMessage());
        }

    }

    public function featuredPaymentResponse(UserSubscriptionRequest $request)
    {
        DB::beginTransaction();
        try {
            $package = $this->subscriptionPackageRepository->get($request->get('package_id'));
            if ($package->isFree()) {
                $newUserFeaturedSubscriptionCollection = collect([
                    'user_subscription_package_id' => 0,
                    'user_id' => auth()->id(),
                    'package' => $package,
                    'is_expired' => false,
                    'payment_method' => 'free',
                    'aed_price' => $package->price,
                    'currency' => 'AED',
                ]);
                $newUserFeaturedSubscriptionDto = UserFeaturedSubscriptionSaveDto::fromCollection($newUserFeaturedSubscriptionCollection);
                $this->userFeaturedSubscriptionRepository->save($newUserFeaturedSubscriptionDto);
                DB::commit();
                return responseBuilder()->success(__('Featured Package Successfully Purchased'));
            } else {
                //          $newPayment = collect([
                //                    'package_id' => $package->id,
                //                    'name' => $package->name['en'],
                //                    'price' => $package->price,
                //                ]);
                //                dd($request->all());
            }
            $SubscriptionPaymentDetails = UserSubscriptionPaymentResponseDto::fromRequest($request);
            $url = $this->userFeaturedSubscriptionRepository->paymentResponse($SubscriptionPaymentDetails);
            DB::commit();
            return responseBuilder()->success(__('Featured Package Successfully Purchased'));
        } catch (Exception $e) {
            DB::rollBack();
            return responseBuilder()->error($e->getMessage());
        }

    }

    public function getUserFeaturedSubscriptions()
    {
        try {
            $subscriptions = $this->userFeaturedSubscriptionRepository->all();
            $subscriptions = $subscriptions->map(function ($item) {
                return $item->getFormattedModel();
            });
            if ($subscriptions) {
                return responseBuilder()->success(__('User Subscriptions.'), $subscriptions);
            } else {
                return responseBuilder()->error(__('Something went wrong'));
            }
        } catch (\Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }

    }

    public function allFeaturedPack(Request $request)
    {
        try {
            $this->subscriptionPackageRepository->setPaginate(6);
            $packages = $this->subscriptionPackageRepository->all($type = 'featured', null);

            if ($packages) {
                return responseBuilder()->success(__('User Featured Packages.'), $packages);
            } else {
                return responseBuilder()->error(__('No Featured Package Found'));
            }
        } catch (\Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }

    }

    public function buyFeaturedPackage(Request $request)
    {
        try {

            $BuyPackage = [];
            $buyPackages = [];

            $userFeaturedPackage = UserFeaturedSubscription::where('is_expired', 0)->where('product_id', 0)->where('user_id', auth()->user()->id)->get();

            $user = auth()->user();
            foreach ($userFeaturedPackage as $item) {
                $item['purchase_count'] = $user->userFeaturedSubscriptionCount($item->package['id']);
            }

            $i = 0;
            foreach ($userFeaturedPackage as $item) {
                if (!in_array($item->package['id'], $BuyPackage)) {
                    $BuyPackage[$i] = $item->package['id'];
                    $buyPackages[$i] = $item;
                }
                $i++;
            }
            $buyPackages = paginate($buyPackages, $request->url(), 6);


            if ($buyPackages) {
                return responseBuilder()->success(__('All Purchase Featured Packages.'), $buyPackages);
            } else {
                return responseBuilder()->error(__('No Featured Package Found'));
            }
        } catch (\Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }

    }
}
