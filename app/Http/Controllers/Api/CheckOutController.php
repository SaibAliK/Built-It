<?php

namespace App\Http\Controllers\Api;

use App\Http\Repositories\CartRepository;
use App\Http\Repositories\CheckOutRepository;
use App\Http\Repositories\AddressRepository;
use App\Http\Requests\CheckOutRequest;
use Illuminate\Http\Request;
use Exception;
use App\Http\Controllers\Controller;

class CheckOutController extends Controller
{
    protected CartRepository $cartRepository;
    protected AddressRepository $addressRepository;
    protected CheckOutRepository $checkOutRepository;

    public function __construct()
    {
        $this->cartRepository = new CartRepository();
        $this->checkOutRepository = new CheckOutRepository();
        $this->addressRepository = new AddressRepository();
    }

    public function index()
    {
        $address = $this->addressRepository->all([]);
        $carts = $this->cartRepository->all();
        return responseBuilder()->success(__('success'), ['carts' => $carts, 'address' => $address]);
    }

    public function save(CheckOutRequest $request)
    {
        try {
            $response = $this->checkOutRepository->save($request);
            if (empty($response)) {
                return responseBuilder()->error(__('One Or More product(s) Were Not Available And Removed From Your Cart, Please Try Again.'));
            }
            if ($request->get('payment_method') == "paypal" && !empty($response)) {
                if ($response == "address") {
                    return responseBuilder()->error(__('Kindly Add At least One Address to Ship .'));
                }

                if ($response) {
                    return responseBuilder()->success(__('success'), [__('Your Order Has Been Placed.')]);
                } else {
                    return responseBuilder()->error(__('Error'));
                }
            }
            if (!empty($response) && $request->get('payment_method') != "paypal") {
                return responseBuilder()->success(__('success'), ["payment-url" => __('Your Order Has Been Placed.')]);
            }
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function paymentResponse(Request $request)
    {
        $response = $this->checkOutRepository->paymentResponse($request);
        if ($response) {
            return responseBuilder()->success(__('Order is Placed.'));
        } else {
            return responseBuilder()->error(__('Something Went wrong'));
        }
    }

    public function buyNow(Request $request)
    {
        $this->validate($request, ['product_id' => 'required', 'quantity' => 'required', 'payment_method' => 'required', 'payment_id' => 'required_if:payment_method,paypal']);
        $order = $this->buyNowTrait($request);

        if ($order == 'payment error') {
            return responseBuilder()->error(__('Payment Not Approved'));
        }
        if ($order == 'billing') {
            return responseBuilder()->error(__('Billing Address Required'));
        }
        if ($order == 'shipping') {
            return responseBuilder()->error(__('shipping Address Required'));
        }
        if ($order == 'payment incorrect') {

            return responseBuilder()->error(__('Payment is not equal to product price and quantity'));

        }
        return responseBuilder()->success(__('success'), $order->toArray());

    }

    public function totalPrice(Request $request)
    {
        $this->validate($request, ['slug' => 'required', 'quantity' => 'required']);
        $total = $this->calculateTotalPrice($request);
        $data['total'] = $total;
        return responseBuilder()->success(__('Total Price'), $data);
    }
}
