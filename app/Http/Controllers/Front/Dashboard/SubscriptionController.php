<?php

namespace App\Http\Controllers\Front\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Dtos\UserSubscriptionSaveDto;
use App\Http\Repositories\SubscriptionPackageRepository;
use App\Http\Repositories\UserSubscriptionRepository;
use App\Models\UserFeaturedSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{

    public SubscriptionPackageRepository $subscriptionPackageRepository;
    public UserSubscriptionRepository $UserSubscriptionRepository;
    public function __construct()
    {
        parent::__construct();
        $this->UserSubscriptionRepository = new UserSubscriptionRepository();
        $this->subscriptionPackageRepository = new SubscriptionPackageRepository();
        $this->breadcrumbs[route('front.index')] = ['title' => __('Home')];
    }

    public function subscriptionType()
    {
        $revenue_stream = config('settings.revenue_stream');
        if ($revenue_stream == 'commission') {
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

    public function index()
    {
        $this->breadcrumbTitle = __('Subscription package');
        $this->breadcrumbs['javascript:{};'] = ['title' => __('Subscription package')];
        $this->subscriptionPackageRepository->setPaginate(0);
        $this->subscriptionPackageRepository->setSelect(['id', 'name', 'duration', 'duration_type', 'price', 'description', 'is_free']);
        $packages = $this->subscriptionPackageRepository->all();
        $user = auth()->user();
        if ($user->hasSubscriptions()) {
            $userSubscription = $user->subscription()->first();
            if (isset($userSubscription->package)) {
                if (!$userSubscription->is_expired) {
                    $subscriptionId = $userSubscription->package['id'];
                    return view('front.dashboard.subscription.dashboard', [
                        'packages' => $packages,
                        'subscriptionId' => $subscriptionId
                    ]);
                }
            }
        }
        return view('front.dashboard.subscription.index', [
            'packages' => $packages,
        ]);
    }

    public function featuredPackages(Request $request)
    {
        if (!isset($request->all_pkg)) {
            $request->merge(['all_pkg' => true]);
        }

        $subscriptionType = $request->input('type');
        $this->breadcrumbTitle = __('Feature Packages');
        $this->breadcrumbs[1] = ['url' => '', 'title' => __('Feature Packages')];
        $view = 'front.dashboard.subscription.featured-package';


        $subscriptionId = '';
        if ($this->user && $subscription = $this->UserSubscriptionRepository->getViewParams($this->user->id)) {
            if ($subscription->is_expired == false) {
                $subscriptionId = $subscription->package['id'];
            }
        }
        $this->subscriptionPackageRepository->setPaginate(8);
        $packages = $this->subscriptionPackageRepository->all($type = $subscriptionType, null);

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
        $buyPackages = paginate($buyPackages, $request->url(), 8);

        return view($view, [
            'packages' => $packages,
            'type' => $subscriptionType,
            'subscriptionId' => $subscriptionId,
            'buyPackages' => $buyPackages
        ]);

    }
}
