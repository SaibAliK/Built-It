<?php

namespace App\Http\Controllers\Api;

use File;
use Exception;
use App\Traits\FCM;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Http\Requests\User\UserRequest;
use Illuminate\Support\Facades\Response;
use App\Http\Repositories\UserRepository;
use App\Http\Repositories\OrderRepository;
// use App\Http\Repositories\CouponRepository;
use App\Http\Repositories\CheckOutRepository;
use App\Http\Repositories\OrderDetailRepository;
use App\Http\Repositories\CompanyOrderRepository;
use niklasravnsborg\LaravelPdf\Facades\Pdf as PDF;



class OrdersController extends Controller
{
    use  FCM;
    protected $orderRepository;
    protected $userRepository;
    protected $orderDetailRepository;
    // protected $couponRepository;
    protected $companyOrderRepository;

    public function __construct(OrderRepository $orderRepository, UserRepository $userRepository, CompanyOrderRepository $companyOrderRepository)
    {
        parent::__construct();
        $this->middleware('jwt.verify');
        $this->orderRepository        = $orderRepository;
        $this->userRepository         = $userRepository;
        $this->orderDetailRepository  = new OrderDetailRepository();
        $this->checkOutRepository     = new CheckOutRepository();
        // $this->couponRepository       = new CouponRepository();
        $this->checkOutRepository     = new CheckOutRepository();
        $this->companyOrderRepository = new CompanyOrderRepository();
    }
    public function index($slug = null)
    {

        $this->orderRepository->setPaginate(4);
        $this->orderRepository->getUser();
        $orders = $this->orderRepository->listing($slug);

        if (!empty($orders)) {
            return responseBuilder()->success(__('List All Orders'), $orders);
        }
        return responseBuilder()->error(__('Order Not Found '));
    }

    public function get($id, Request $request)
    {
        $request['id'] = $id;
        $this->validate($request, [
            'id' => 'required|exists:orders,id',
        ]);
        try {

            $order = $this->orderRepository->get($id, true);
            if (NULL != $order) {
                // if ($order->status == 'completed') {
                //     $order_pdf = $this->orderRepository->printPdf($id);

                //     if (str_contains($order_pdf, 'pdf')) {
                //         $order->invoice = $order_pdf;
                //     }
                // }

                return responseBuilder()->success(__('Order Get By Id '), [$order]);
            }
            return responseBuilder()->error(__('Order Not Found '));
        } catch (\Exception $e) {
            return responseBuilder()->error(__($e->getMessage()));
        }
    }

    public function sendPdf($id)
    {
        try {
            $order_pdf = $this->orderRepository->sendpdf($id);
            return responseBuilder()->success(__('pdf send'));
        } catch (\Exception $e) {
            return responseBuilder()->error(__('comething went wrong'));
        }
    }

    public function reject(Request $request)
    {
        $order         = $this->orderRepository->getByParam($request->order_id);
        $request['orderUserId'] = $order->user_id;
        $request['total']       = $order->total;
        $this->orderRepository->cancel($request, null);
        return responseBuilder()->success(__('Order Rejected Successfully.'));
    }

    public function accept(Request $request)
    {

        $order = $this->orderRepository->getByParam($request->order_id);
        $request['orderUserId'] = $order->user_id;
        $request['total'] = $order->total;
        $this->orderRepository->accept($request);
        return responseBuilder()->success(__('Order Is Accepted.'));
    }
    public function shipped(Request $request)
    {
        $order         = $this->orderRepository->getByParam($request->order_id);
        $request['orderUserId'] = $order->user_id;
        $this->orderRepository->shipped($request);
        return responseBuilder()->success(__('Order Is In Progress.'));
    }
    public function complete(Request $request)
    {
        $order = $this->orderRepository->getByParam($request->order_id);
        $request['orderUserId'] = $order->user_id;
        $request['total'] = $order->total;
        $this->orderRepository->complete($request);

        return responseBuilder()->success(__('Order Is Completed.'));
    }


    public function createOrder(Request $request)
    {
        $this->validate(request(), [
            'payment_method'   => 'required|in:cod,paypal',
            'selected_address' => 'required|exists:addresses,id',
            'paymentID'        => 'required_if:payment_method,paypal',
            'payerID'          => 'required_if:payment_method,paypal'
        ]);

        $order  = $this->createOrderTrait($request);

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
            return responseBuilder()->error(__('Payment is not equal to Cart total'));
        }
        return responseBuilder()->success(__('success'), $order->toArray());
    }
    public function getAllCompanies(Request $request)
    {
        $companies =  $this->userRepository->all(null, null, 'delivery_company', $request);
        return responseBuilder()->success(__('Get All Companies'), ['companies' => $companies]);
    }

    //for admin to get the total.
    public function OrdersTotalAmount(Request $request)
    {

        $query = Order::query();
        if ($request->fromDate != '' && $request->toDate != '') {
            $date = [];
            $fromDate = Carbon::createFromFormat('m/d/Y', $request->fromDate);
            $fromDate = $fromDate->hour(0)->minute(0)->second(0)->timestamp;
            $toDate = Carbon::createFromFormat('m/d/Y', $request->toDate);
            $toDate = $toDate->hour(23)->minute(59)->second(59)->timestamp;
            array_push($date, $fromDate);
            array_push($date, $toDate);
            $query->whereBetween('created_at', $date);
        }
        if ($request->toDate != '' && $request->fromDate == '') {
            $toDate = Carbon::createFromFormat('m/d/Y', $request->toDate);
            $uBetween = [0, $toDate->hour(23)->minute(59)->second(59)->timestamp];
            $query->whereBetween('created_at', $uBetween);
        }
        if ($request->toDate == '' && $request->fromDate != '') {
            $fromDate = Carbon::createFromFormat('m/d/Y', $request->fromDate);
            $uBetween = [$fromDate->hour(0)->minute(0)->second(0)->timestamp, Carbon::today()->unix()];
            $query->whereBetween('created_at', $uBetween);
        }

        if ($request->payment_method != '') {
            $query->where('payment_method', '=', $request->payment_method);
        }
        if ($request->order_status != '') {
            $query->where('order_status', '=', $request->order_status);
        }
        if ($request->order_number != '') {
            $query->where('order_number', 'LIKE', '%' . $request->order_number . '%');
        }
        if ($request->first_name != '') {
            $query->where('user_id', '=', $request->first_name);
        }
        if ($request->last_name != '') {
            $query->where('last_name', 'LIKE', "%{$request->last_name}%");;
        }

        if ($request->store_id != '') {
            $store_id = $request->store_id;
            $query->whereHas('orderDetails', function ($orderDetail) use ($store_id) {
                $orderDetail->where('store_id', $store_id);
            });
        }
        $min_amount = $request->min_amount;
        $max_amount = $request->max_amount;
        if ($min_amount != '' && $max_amount != '') {
            $range = [];
            $range[0] = $min_amount;
            $range[1] = $max_amount;
            $query->whereBetween('total', $range);
        }
        if ($min_amount != '' && $max_amount == '') {
            $query->where('total', '>=', $min_amount);
        }
        if ($max_amount != '' && $min_amount == '') {
            $query->where('total', '<=', $max_amount);
        }
        $orders = $query->get();
        $orderAmount = [];

        foreach ($orders as $order) {
            array_push($orderAmount, $order->total);
        }

        $number =  array_sum($orderAmount);
        if ($number < 1000000) {
            // Anything less than a million
            $format = number_format($number, 2);
        } else if ($number < 1000000000) {
            // Anything less than a billion
            $format = number_format($number / 1000000, 2) . 'M';
        } else {
            // At least a billion
            $format = number_format($number / 1000000000, 2) . 'B';
        }

        return response()->json($format, 200);
        //        return $orderAmount;

    }

    public function printPdf($id)
    {
        try {

            $return_path['url'] = $this->orderRepository->PrintOrder([],$id);

            return responseBuilder()->success('Order Invoice Path', $return_path);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function save(Request $request)
    {

        $this->validate(request(), [
            'payment_method'   => 'required|in:cod,paypal',
            'selected_address' => 'required|exists:addresses,id',
            'paymentID'        => 'required_if:payment_method,paypal',
            'payerID'          => 'required_if:payment_method,paypal'
        ]);

        try {
            $response = $this->checkOutRepository->saveApi($request);

            if (empty($response)) {
                return responseBuilder()->error(__('One Or More product(s) Were Not Available And Removed From Your Cart, Please Try Again.'));
            }
            // dd($response);
            if ($request->get('payment_method') == "paypal" && !empty($response)) {
                if ($response == false) {
                    return responseBuilder()->error(__('Something Going Wrong.'));
                }
                if ($response) {
                    return  responseBuilder()->success('Order sucessfuly placed');
                } else {
                    return responseBuilder()->error(__('Error'));
                }
            }
            if ($request->get('payment_method') == "cod") {
                if ($response == false) {
                    return responseBuilder()->error(__('Something Going Wrong.'));
                }
                if ($response) {
                    return  responseBuilder()->success('Order sucessfuly placed');
                } else {
                    return responseBuilder()->error(__('Error'));
                }
            }
        } catch (\Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    // public function couponValidate(CouponRequest $request)
    // {
    //     try {

    //         $data = $this->couponRepository->addUserCoupon($request->get('code'));
    //         if ($data == "expired") {
    //             return responseBuilder()->error(__('Coupon is Expired.'));
    //         }
    //         if ($data == "finished") {
    //             return responseBuilder()->error(__('Coupon is Expired Or Finished.'));
    //         }
    //         return responseBuilder()->success(__('Coupon is Successfully Added.'));
    //     } catch (\Exception $e) {
    //         return responseBuilder()->error('Something Went Wrong.');
    //     }
    // }


    // public function removeCoupon()
    // {

    //     $result = $this->couponRepository->destroy(null);
    //     if (!empty($result)) {
    //         return responseBuilder()->success(__('Coupon is Successfully Removed.'));
    //     }
    // }

    public function update($id, $slug, Request $request)
    {
        try {

            $request->merge(['order_id' => $id]);
            $orderUserId = $this->orderRepository->getQuery()->where('id', $id)->first()->user_id;
            $request->merge(['orderUserId' => $orderUserId]);
           
            if ($slug == 'cancel' || $slug == 'reject')
                $this->orderRepository->cancel($request);
            elseif ($slug == 'accept')
                $this->orderRepository->accept($request);
            elseif ($slug == 'delivered')
                $this->orderRepository->shippedByRider($request->order_detail_id);
            elseif ($slug == 'complete')
                $this->orderRepository->complete($request);

            return responseBuilder()->success('Order Updated Successfully');
        } catch (Exception $e) {
            return  responseBuilder()->error($e->getMessage());
        }
    }

    public function assign(Request $request)
    {
        try {

            $this->companyOrderRepository->assign($request);
            $this->orderDetailRepository->getQuery()->where('id', $request->order_detail_id)->update(['status' => 'shipped']);
            $this->orderRepository->getQuery()->where('id', $request->order_id)->update(['status' => 'shipped']);
            return responseBuilder()->success('Order assigned Successfully.');
        } catch (\Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }
}
