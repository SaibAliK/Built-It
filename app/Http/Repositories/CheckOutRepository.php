<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository\Repository;
// use App\Http\Repositories\CouponRepository;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Traits\MyTechnologyPayPal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Common\PayPalModel;
use Exception;


class CheckOutRepository extends Repository
{
    use MyTechnologyPayPal;

    public CartRepository $cartRepository;
    public ProductRepository $productRepository;
    public AddressRepository $addressRepository;
    public OrderRepository $orderRepository;
    // protected CouponRepository $couponRepository;
    public OrderDetailRepository $orderDetailRepository;
    public OrderDetailItemRepository $orderDetailItemRepository;

    public function __construct()
    {
        $this->cartRepository = new CartRepository();
        $this->productRepository = new ProductRepository();
        $this->addressRepository = new AddressRepository();
        $this->orderRepository = new OrderRepository();
        // $this->couponRepository = new CouponRepository();
        $this->orderDetailRepository = new OrderDetailRepository();
        $this->orderDetailItemRepository = new OrderDetailItemRepository();
    }

    public function address()
    {
        $user = $this->getUser();
        $billing = $this->addressRepository->get();
        if ($billing) {
            return $this->addressRepository->get();
        } else {
            $billing->id = 0;
            return $billing;
        }
    }

    public function save($request)
    {
        DB::beginTransaction();
        try {
            $user = $this->getUser();

            $productIds = [];
            $cartData = $this->cartRepository->all();
            $cartList = $cartData['list'];
            foreach ($cartList as $cartItem) {
                $productIds[$cartItem->product->id] = $cartItem->product->id;
            }
            if (count($productIds) <= 0) {
                return false;
            }
            $products = Product::whereIn('id', $productIds)->with($this->getRelations())->with('store')->get();
            if (count($products) <= 0) {
                $error = __('product is not exist');
                return false;
            }
            $supplier_name = $products[0]->store->supplier_name['en'];

            if (count($products) <= 0) {
                return false;
            }

            if (count($productIds) != count($products)) {
                $error = __('One Or More product(s) Were Not Available And Removed From Your Cart, Please Try Again');
                return false;
            }

            DB::commit();
            $this->initPayPal();
            if ($request->payment_method == 'paypal') {
                $payerId = $request->payerID;
                $paymentID = $request->paymentID;
                $payment = Payment::get($paymentID, $this->apiContext);
                $execution = new PaymentExecution();
                $execution->setPayerId($payerId);
                $result = $payment->execute($execution, $this->apiContext);
                if ($result->getState() == 'approved') {
                    $order = $this->placeOrder($request);
                    try {
                        DB::beginTransaction();
                        $paymentData = $payment->toArray();
                        $order->update([
                            'user_id' => $user->id,
                            'payment_status' => 'confirmed',
                            'payment_id' => (isset($paymentData['id'])) ? $paymentData['id'] : '',
                            'payer_status' => (isset($paymentData['payer']['status'])) ? $paymentData['payer']['status'] : '',
                            'payer_email' => (isset($paymentData['payer']['payer_info']['email'])) ? $paymentData['payer']['payer_info']['email'] : '',
                            'first_name' => (isset($paymentData['payer']['payer_info']['first_name'])) ? $paymentData['payer']['payer_info']['first_name'] : '',
                            'last_name' => (isset($paymentData['payer']['payer_info']['last_name'])) ? $paymentData['payer']['payer_info']['last_name'] : '',
                            'payer_id' => (isset($paymentData['payer']['payer_info']['payer_id'])) ? $paymentData['payer']['payer_info']['payer_id'] : '',
                            'charges' => (isset($paymentData['transactions'][0]['amount']['total'])) ? $paymentData['transactions'][0]['amount']['total'] : 0,
                            'currency' => (isset($paymentData['transactions'][0]['amount']['currency'])) ? $paymentData['transactions'][0]['amount']['currency'] : 'USD',
                            'transaction_details' => (isset($paymentData['transactions'][0]['amount']['details'])) ? json_encode($paymentData['transactions'][0]['amount']['details']) : '',
                            'paypal_response' => $payment->toJSON(),
                            'payment_method' => 'paypal',
                        ]);
                        DB::commit();
                        sendNotification([
                            'sender_id' => $this->getUser()->id,
                            'receiver_id' => $this->getUser()->id,
                            'extras->order_id' => $order->id,
                            'extras->display_name' => $supplier_name,
                            'extras->app_logo' => url('images/Favicon.png'),
                            'title->en' => 'Order Placed',
                            'title->ar' => 'تم الطلب',
                            'description->en' => 'Your order has been placed',
                            'description->ar' => 'لقد تم وضع طلبك',
                            'action' => 'ORDER'
                        ]);
                        return route('front.dashboard.order.index', ['status' => 'all', 'type' => 'product']);
                    } catch (\Exception $e) {
                        DB::rollBack();
                        return false;
                    }
                } else {
                    set_alert('danger', __('Payment failed! Your order is canceled.'));
                    return false;
                }
            } else {
                $order = $this->placeOrder($request);
                if ($order) {
                    sendNotification([
                        'sender_id' => $this->getUser()->id,
                        'receiver_id' => $this->getUser()->id,
                        'extras->order_id' => $order->id,
                        'extras->display_name' => $supplier_name,
                        'extras->app_logo' => url($this->getUser()->image_url),
                        'title->en' => 'Order Placed',
                        'title->ar' => __('Order Placed'),
                        'description->en' => 'Your order has been placed',
                        'description->ar' => __('Your order has been placed'),
                        'action' => 'ORDER'
                    ]);
                    // $user->update([
                    //     'coupon' => null
                    // ]);
                    session()->forget('cart');
                    return route('front.dashboard.order.index', ['status' => 'all']);
                }
                return null;
            }

        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function placeOrder($request = null, $orderType = null, $gymCart = null)
    {
        if (empty($request->get('selected_address')) || is_null($request->get('selected_address'))) {
            $error = __('Please Select Address');
            return false;
        }

        $user = $this->getUser();
        $cart = Cart::where('user_id', $user->id)->get();

        $storeProductDetail = $this->cartRepository->getQuery()->whereHas('product')->with('product')
            ->select('id','user_id','store_id','product_id','price','discounted_price','subtotal','shipping','total','quantity','images','created_at','updated_at','deleted_at',
                DB::RAW('count(store_id) as count'),
                DB::RAW('SUM(total) as total_sum'),
                DB::RAW('SUM(subtotal) as subtotal_sum'),
                DB::RAW('SUM(shipping) as shipping_sum'))
            ->where('user_id', $user->id)->groupby('store_id')->get();
        $cart_count = count($cart) ?? 0;
        $carts = $this->cartRepository->getQuery()->whereHas('product')->with('product')->where('user_id', $user->id)->get();

        $cartData = $this->cartRepository->all();

        $orderNumber = Str::random(6);
        
        $order = $this->orderRepository->save($request, $orderNumber, $cartData);

        $count = 0;
        $discounted_total = 0;
        $total_discount = 0;

        foreach ($storeProductDetail as $productDetail) {
            $count++;
            $orderDetail = $this->orderDetailRepository->save($productDetail, $order, $count);
            foreach ($carts as $cartKey => $cartValue) {
                $discount_price = 0;
                if (!empty($orderDetail->store_id) && !empty($cartValue->store_id) && $orderDetail->store_id == $cartValue->store_id) {
                    $vat_value = $cartValue->subtotal * (config('settings.value_added_tax') / 100);
                    $discounted_total = $cartValue->subtotal + $cartValue->shipping;
                    $this->orderDetailItemRepository->save($cartValue, $orderDetail, $order, $discount_price, $discounted_total);
                }
            }
        }

        $userId = ['user_id' => $user->id];
        $this->cartRepository->empty($userId, true);
        session()->forget('cart');

        return $order;
    }

    public function paymentResponse($request)
    {
        $user = $this->getUser();
        $payerId = $request->getId();
        $token = $request->getToken();
        $this->initPayPal();
        $payment = Payment::get($payerId, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
        $result = $payment->execute($execution, $this->apiContext);
        if ($result->getState() == 'approved') {

            $order = $this->placeOrder($request);
            try {
                DB::beginTransaction();
                $paymentData = $payment->toArray();
                $order->update([
                    'user_id' => $user->id,
                    'payment_status' => 'confirmed',
                    'status' => 'confirmed',
                    'payment_id' => (isset($paymentData['id'])) ? $paymentData['id'] : '',
                    'payer_status' => (isset($paymentData['payer']['status'])) ? $paymentData['payer']['status'] : '',
                    'payer_email' => (isset($paymentData['payer']['payer_info']['email'])) ? $paymentData['payer']['payer_info']['email'] : '',
                    'first_name' => (isset($paymentData['payer']['payer_info']['first_name'])) ? $paymentData['payer']['payer_info']['first_name'] : '',
                    'last_name' => (isset($paymentData['payer']['payer_info']['last_name'])) ? $paymentData['payer']['payer_info']['last_name'] : '',
                    'payer_id' => (isset($paymentData['payer']['payer_info']['payer_id'])) ? $paymentData['payer']['payer_info']['payer_id'] : '',
                    'charges' => (isset($paymentData['transactions'][0]['amount']['total'])) ? $paymentData['transactions'][0]['amount']['total'] : 0,
                    'currency' => (isset($paymentData['transactions'][0]['amount']['currency'])) ? $paymentData['transactions'][0]['amount']['currency'] : 'USD',
                    'transaction_details' => (isset($paymentData['transactions'][0]['amount']['details'])) ? json_encode($paymentData['transactions'][0]['amount']['details']) : '',
                    'paypal_response' => $payment->toJSON(),
                    'payment_method' => 'paypal',
                ]);

                DB::commit();
                $userId = ['user_id' => $user->id];
                $this->cartRepository->delete($userId, true);
                session()->forget('cart');
                return true;
            } catch (\Exception $e) {
                DB::rollBack();
                return false;
            }
        } else {
            session()->forget('paypal_payment_id');
            set_alert('danger', __('Payment failed! Your order is canceled.'));
            return false;
        }
    }

}
