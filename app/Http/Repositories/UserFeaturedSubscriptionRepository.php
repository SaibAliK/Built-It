<?php

namespace App\Http\Repositories;

use App\Http\Dtos\UserFeaturedSubscriptionSaveDto;
use App\Http\Dtos\UserSubscriptionPaymentDto;
use App\Http\Dtos\UserSubscriptionSaveDto;
use App\Http\Repositories\BaseRepository\Repository;
use App\Jobs\ExpireFeaturedSubscription;
use App\Jobs\SubscriptionExpiry;
use App\Jobs\SubscriptionExpiryReminder;
use App\Models\UserFeaturedSubscription;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use App\Events\newNotifications;
use App\Http\Dtos\UserSubscriptionPaymentResponseDto;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserSubscription;
use App\Traits\FCM;
use App\Traits\MyTechnologyPayPal;
use Exception;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use stdClass;

class UserFeaturedSubscriptionRepository extends Repository
{
    protected UserRepository $userRepository;
    use  MyTechnologyPayPal;

    protected SubscriptionPackageRepository $subscriptionPackageRepository;

    public function __construct()
    {
        $this->setModel(new UserFeaturedSubscription());
        $this->userRepository = new UserRepository();
        $this->subscriptionPackageRepository = new SubscriptionPackageRepository();
    }

    public function save(UserFeaturedSubscriptionSaveDto $params)
    {
        $data = $params->except('id')->toArray();
        // $this->expireActiveSubscriptions($params->user_id);
        $userSubscription = $this->getModel()->updateOrCreate(['id' => $params->id], $data);
        $package = $params->package;
        if (!$this->getFromAdmin()) {
            $user = $this->getUser();
        } else {
            $user = $this->userRepository->get($params->user_id);
        }
        // $this->updateUserExpiryDate($user, $userSubscription->created_at, $package->duration_type, $package->duration);
        return $userSubscription;
    }

    public function updateUserExpiryDate($user, $newSubCreatedAt, $durationName, $packageDuration)
    {
        $old_expiry_date = $user->expiry_date;
        if (is_null($old_expiry_date)) {
            $expiry_date = unixConversion($durationName, $packageDuration, $newSubCreatedAt);
        } else {
            $expiry_date = unixConversion($durationName, $packageDuration, $old_expiry_date);
        }
        $user->update(['expiry_date' => $expiry_date]);

        $data['expiry_date'] = Carbon::parse(gmdate("Y-m-d H:i:s", $expiry_date))->addHours(24)->format('Y-m-d');
        $days = Carbon::now()->diffInDays($data['expiry_date']);

        SubscriptionExpiryReminder::dispatch($user->id)->delay(now()->addDays($days - 1)->endOfDay());
        SubscriptionExpiry::dispatch($user->id)->delay(now()->addDays($days)->endOfDay());
    }

    public function expireActiveSubscriptions($id)
    {
        $this->getModel()->where('user_id', $id)->update(['is_expired' => true]);
    }

    public function subscribeToPackage(UserSubscriptionPaymentDto $params)
    {
        $this->initPayPal();
        $this->itemList = new ItemList();
        $paypalItem = new Item();
        $details = new stdClass();
        $paypalItem->setName(Str::limit($params->name, 250))
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice($params->usdPrice);
        $this->itemList->addItem($paypalItem);

        $this->subTotal += $params->usdPrice;
        $details->tax = $params->usdVat;
        $details->subtotal = number_format($this->subTotal, 2, '.', '');
        $this->details = $details;

        $this->subTotal = number_format($this->subTotal + $details->tax, 2, '.', '');
        $returnUrl = route('front.dashboard.featured.subscription.payment-response', [
            'package_id' => $params->package_id,
        ]);

        return $this->doExpressCheckout($returnUrl, $returnUrl);

    }

    public function paymentResponseforApiFeatured($request)
    {

        DB::beginTransaction();
        try {
            $user    = auth()->user();
            $payerId = $request->get('PayerID');
            $token   = $request->get('token');
            $this->initPayPal();
            if ($this->getFromWeb()) {
                $paymentId = $request->get('paypal_payment_id');
                if (empty($payerId) || empty($token)) {
                    session()->forget('paypal_payment_id');
                    throw new Exception(__('Payment Failed!'));
                }
            } else {
                $paymentId = $request->get('paymentId');
            }

            $package = $this->subscriptionPackageRepository->get($request->feature_package_id);

            throw_if(!$paymentId, new Exception(__('Payment Failed!')));
            throw_if(!$package, new Exception(__('Invalid Package ID')));

            $payment = Payment::get($paymentId, $this->apiContext);
            $execution = new PaymentExecution();
            $execution->setPayerId($payerId);
            $result = $payment->execute($execution, $this->apiContext);
            if ($result->getState() == 'approved') {
                $paymentData = $payment->toArray();

                $data = collect($paymentData);

                $data['feature_package']    = $package;
                $data['feature_package_id'] = $package->id;
                $data['user_id']            = auth()->user()->id;

//                $createPackageDTO = FeaturePackageDto::create($data);
//
//                $package = $this->save($createPackageDTO->toArray());
                DB::commit();
                return $package;
            } else {
                return false;
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    public function paymentResponse(UserSubscriptionPaymentResponseDto $params)
    {
        $user = $this->getUser();
        $payerId = $params->PayerID;
        $token = $params->token;
        $this->initPayPal();
        $paymentId = $params->paymentId;
        if (empty($payerId) || empty($token)) {
            throw new Exception(__('Payment Failed!'));
        }
        $package = $this->subscriptionPackageRepository->get($params->package_id);
        $payment = Payment::get($paymentId, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
        $result = $payment->execute($execution, $this->apiContext);
        if ($result->getState() == 'approved') {
            $paymentData = $payment->toArray();
            $featuredSubscriptionCollection = collect([
                'user_id' => $user->id,
                'package' => $package,
                'is_expired' => false,
                'payment_status' => 'confirmed',
                'payer_id' => (isset($paymentData['payer']['payer_info']['payer_id'])) ? $paymentData['payer']['payer_info']['payer_id'] : '',
                'first_name' => (isset($paymentData['payer']['payer_info']['first_name'])) ? $paymentData['payer']['payer_info']['first_name'] : '',
                'last_name' => (isset($paymentData['payer']['payer_info']['last_name'])) ? $paymentData['payer']['payer_info']['last_name'] : '',
                'payment_id' => (isset($paymentData['id'])) ? $paymentData['id'] : '',
                'payer_email' => (isset($paymentData['payer']['payer_info']['email'])) ? $paymentData['payer']['payer_info']['email'] : '',
                'payer_status' => (isset($paymentData['payer']['status'])) ? $paymentData['payer']['status'] : '',
                'payment_method' => 'paypal',
                'paypal_response' => $paymentData,
                'aed_price' => (isset($paymentData['transactions'][0]['amount']['total'])) ? $paymentData['transactions'][0]['amount']['total'] : 0,
                'currency' => (isset($paymentData['transactions'][0]['amount']['currency'])) ? $paymentData['transactions'][0]['amount']['currency'] : 'USD',
            ]);
            $FeaturedSubscriptionDto = UserFeaturedSubscriptionSaveDto::fromCollection($featuredSubscriptionCollection);
            return $this->save($FeaturedSubscriptionDto);
        } else {
            throw new Exception(__('Payment Failed!'));
        }
    }

    public function getViewParams($id = 0)
    {
        $param = new UserFeaturedSubscription();
        if ($id > 0) {
            $param = $this->getModel()->where('user_id', '=', $id)->orderby('id', 'desc')->first();
        }
        return $param;
    }

    public function get($id = 0)
    {
        $query = $this->getQuery();
        if ($id > 0) {
            $data = $query->findOrFail($id);
        }
        return $data;
    }

    public function all()
    {
        $user = $this->getUser();
        $query = $this->getQuery();
        $query->where('is_expired', 0)->where('product_id', 0)->where('user_id', $user->id);
        return $query->get();
    }

    public function getActive($id)
    {
        return $this->getModel()->where('user_id', '=', $id)->where('is_expired', 0)->first();
    }

    public function buyPackages($request)
    {
        $BuyPackage = [];
        $buyPackages = collect();

        $user = $this->getUser();
        $query = $this->getQuery();
        $query->where('is_expired', 0)->where('product_id', 0)->where('user_id', $user->id);

        $data = $query->get();
        if (count($data) > 0) {
            $i = 0;
            foreach ($data as $item) {
                $item['purchase_count'] = $user->userFeaturedSubscriptionCount($item->subscription_package['id']);
                if (!in_array($item->subscription_package['id'], $BuyPackage)) {
                    $BuyPackage[$i] = $item->subscription_package['id'];
                    $buyPackages->push($item);
                }
                $i++;
            }

            $buyPackages = paginate($buyPackages->values(), $request->url(), 6, true);
        }

        return $buyPackages;
    }

    public function setProduct($id, $product_id)
    {
        $subscription = $this->get($id);
        $subscription->update(['product_id' => $product_id]);
        $expiry_date = unixConversion($subscription->package['duration_type'], $subscription->package['duration'], now()->unix());
        $days = Carbon::now()->diffInDays(Carbon::parse($expiry_date));
        ExpireFeaturedSubscription::dispatch($subscription->id)->delay(now()->addDays($days)->endOfDay());
    }
}
