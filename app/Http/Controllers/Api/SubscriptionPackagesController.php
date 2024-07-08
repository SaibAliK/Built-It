<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\SubscriptionPackageRepository;
use Illuminate\Http\Request;

class SubscriptionPackagesController extends Controller
{

    protected $subscriptionPackageRepository;

    public function __construct(SubscriptionPackageRepository $subscriptionPackageRepository)
    {
        parent::__construct();
        $this->subscriptionPackageRepository = $subscriptionPackageRepository;
    }

    public function index(Request $request)
    {
        $type = 'subscription';
        if ($request->filled('type')){
            $type = $request->get('type');
        }
        $isFree = null;
        if ($request->filled('is_free')){
            $isFree = $request->get('is_free');
        }
        $this->subscriptionPackageRepository->setPaginate(0);
        $this->subscriptionPackageRepository->setSelect(['id', 'name', 'duration', 'duration_type', 'price', 'description', 'subscription_type', 'is_free']);
        $packages = $this->subscriptionPackageRepository->all($type,$isFree);
        $subscriptionId = 0;
        $user = auth()->user();
        if ($user->hasSubscriptions()) {
            $userSubscription = $user->subscription()->first();
            if (isset($userSubscription->package)) {
                if (!$userSubscription->is_expired) {
                    $subscriptionId = $userSubscription->package['id'];
                }
            }
        }
        return responseBuilder()->success(__('Subscription packages'),['packages'=>$packages, 'user_subscription_id'=> $subscriptionId]);
    }

}
