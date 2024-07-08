<?php

namespace App\Http\Controllers\Front;

use App\Http\Repositories\CartRepository;
use App\Http\Repositories\CheckOutRepository;
use App\Http\Repositories\CityRepository;
use App\Http\Repositories\AddressRepository;
use App\Http\Requests\CheckOutRequest;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CheckOutController extends Controller
{
    protected CartRepository $cartRepository;
    protected AddressRepository $addressRepository;
    protected CheckOutRepository $checkOutRepository;
    protected CityRepository $cityRepository;

    public function __construct()
    {
        parent::__construct();
        $this->breadcrumbTitle = __('Checkout');
        $this->breadcrumbs[0] = ['url' => route('front.index'), 'title' => __('Home')];
        $this->breadcrumbs[1] = ['url' => '', 'title' => __('Checkout')];
        $this->cartRepository = new CartRepository();
        $this->checkOutRepository = new CheckOutRepository();
        $this->cityRepository = new CityRepository();
        $this->addressRepository = new AddressRepository();
    }

    public function index()
    {
        $ss_data = session()->get('user_location_data');
        $data = $this->addressRepository->getModel();
        $carts = $this->cartRepository->all();
        $cities = $this->cityRepository->all();
        $area_ids = [];

        foreach ($carts['list']->first()->store->coveredAreas as $areas) {
            $area_ids[] = $areas->id;
        }
        $address = $this->addressRepository->getAddressCheckout($area_ids);

        if ($this->cartRepository->isEmpty()) {
            return redirect()->route('front.dashboard.cart.index')->with('error', 'Kindly Add Product First For Checkout.');
        }
        return view("front.checkout.checkout", ['store_id' => $carts['list']->first()->store->id, 'data' => $data, 'id' => 0, 'cart' => $carts, 'address' => $address, 'cities' => $cities]);
    }

    public function save(CheckOutRequest $request)
    {
        try {
            $response = $this->checkOutRepository->save($request);

            if (empty($response)) {
                return redirect()->back()->with('err', __('One Or More product(s) Were Not Available And Removed From Your Cart, Please Try Again.'));
            }

            if ($request->get('payment_method') == "paypal" && !empty($response)) {
                if ($response == "address") {
                    return redirect()->back()->with('err', __('Kindly Add At least One Address to Ship .'));
                }

                if ($response) {
                    return redirect($response);
                } else {
                    return redirect(route('front.dashboard.cart.index'))->with('err', __('An Unknown Error Occurred, Try Later.'));
                }
            }
            if (!empty($response) && $response == "wallet") {
                return redirect($response)->with('status', __('Wallet amount is less then the total.'));
            }

            if (!empty($response) && $request->get('payment_method') != "paypal") {
                return redirect($response)->with('status', __('Your Order Has Been Placed.'));
            }
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect(route('front.dashboard.checkout.index'))->with('err', $e->getMessage());
        }
    }
}
