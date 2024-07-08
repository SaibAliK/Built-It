<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository\Repository;
use App\Models\City;

//use App\Models\CompanyOrder;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Http\Dtos\SendEmailDto;
use App\Jobs\SendMail;
use App\Models\Reason;
use App\Traits\EMails;
use Exception;
use http\Env\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use ArPHP\I18N\Arabic;
use Illuminate\Support\Facades\File;
use Response;


class OrderRepository extends Repository
{
    use EMails;

    protected OrderDetailRepository $orderDetailRepository;
    protected OrderDetailItemRepository $orderDetailItemRepository;
    protected UserRepository $userRepository;
    protected AddressRepository $addressRepository;
    protected ProductRepository $productRepository;
    protected CompanyOrderRepository  $orderCompanyRepository;


    public function __construct()
    {
        $this->setModel(new Order());
        $this->orderDetailRepository = new OrderDetailRepository();
        $this->userRepository = new UserRepository();
        $this->productRepository = new ProductRepository;
        $this->orderDetailItemRepository = new OrderDetailItemRepository;
        $this->addressRepository = new AddressRepository;
        $this->orderCompanyRepository = new CompanyOrderRepository;
    }

    public function save($request = null, $orderNumber = null, $cartData = null)
    {
        $user = $this->getUser();
        $address = $this->addressRepository->getModel()->with('city')->where([['id', $request->get('selected_address')], ['user_id', $user->id]])->withTrashed()->first();
        $status = "pending";

        return $this->getQuery()->create([
            'user_id' => $user->id,
            'full_name' => $user->user_name,
            'address' => $address,
            'order_number' => $orderNumber,
            'payment_status' => 'pending', // only confirm for paypal
            'status' => $status,
            'order_notes' => $request->order_notes,
            'image' => $cartData['list'][0]->images ?? '',
            'payment_method' => $request->payment_method,
            'vat_percentage' => config('settings.value_added_tax'),
            'discount_percentage' => $cartData['price_object']->discountPercentage,
            'shipping' => $cartData['price_object']->shipping->aed->amount,
            'subtotal' => $cartData['price_object']->subtotal->aed->amount,
            'discount' => $cartData['price_object']->discount->aed->amount,
            'vat' => $cartData['price_object']->vat->aed->amount,
            'total' => $cartData['price_object']->total->aed->amount
        ]);
    }

    public function setPrices($order)
    {
        $route = Route::current();
        $city_name = [];
        if (auth()->user()->isUser()) {
            if (isset($order->address->city_id)) {
                $city_name = City::find($order->address->city_id);
                if ($city_name) {
                    $city_name = $city_name->name;
                }
            }
        } else {
            if (isset($order->order->address->city_id)) {
                $city_name = City::find($order->order->address->city_id);
                if ($city_name) {
                    $city_name = $city_name->name;
                }
            }
        }


        if (auth()->user()->isUser() || auth()->user()->isSupplier()) {
            $order->original_discount = $order->discount;
            $order->original_shipping = $order->shipping;
            $order->package_total = getPriceObject(round($order->total - $order->vat));
            $order->total = getPriceObject($order->total);
            $order->vat = getPriceObject($order->vat);
            $order->shipping = getPriceObject($order->shipping);
            $order->discount = getPriceObject($order->discount);
            $order->subtotal = getPriceObject($order->subtotal);
            $order->shiping_price = getPriceObject($order->shipping);
        }


        if ($route->action['as'] == "front.dashboard.order.detail") {
            if (auth()->user()->isUser()) {
                $order->address->city_id = $city_name ?? '';
            } else {
                $order->order->address->city_id = $city_name ?? '';
            }
        }


        $storeStatuses = [];
        if (auth()->user()->isUser()) {
            foreach ($order->orderDetails as $orderDetail) {
                $storeStatuses[$orderDetail->store_id] = $orderDetail->status;
            }
        }

        if (auth()->user()->isUser() || auth()->user()->isSupplier()) {
            foreach ($order->orderItems as $orderItem) {
                if (isset($storeStatuses)) {
                    if (count($storeStatuses) > 0) {
                        $orderItem->status = $storeStatuses[$orderItem->store_id];
                    }
                }
                $orderItem->original_discount = $orderItem->discount;
                $orderItem->original_shipping = $orderItem->shipping;
                $orderItem->total = getPriceObject($orderItem->total);
                $orderItem->price = getPriceObject($orderItem->price);
                $orderItem->shipping = getPriceObject($orderItem->shipping);
                $orderItem->discount = getPriceObject($orderItem->discount);
                $orderItem->subtotal = getPriceObject($orderItem->subtotal);
            }
        }

        if (!auth()->user()->isUser()) {

            if (auth()->user()->isSupplier()) {
                $order->address = $order->order->address;
                $order->payment_method = $order->order->payment_method;
                $order->discount_percentage = $order->order->discount_percentage;
                $order->order->total = getPriceObject($order->order->total);
                $order->order->vat = getPriceObject($order->order->vat);
                $order->order->shipping = getPriceObject($order->order->shipping);
                $order->order->discount = getPriceObject($order->order->discount);
                $order->order->subtotal = getPriceObject($order->order->subtotal);

                if (isset($order->companyOrderDetails[0])) {
                    $order->company_status = $order->companyOrderDetails[0]->pivot->status;
                }
            }
        }
        return $order;
    }

    public function listing($status, $keywords = null, $type = null)
    {

        $query = $this->getModel()->query();

        if (!auth()->user()->isUser()) {
            $query = OrderDetail::query();
            if (!is_null($keywords)) {
                $query->where('order_no', 'like', '%' . $keywords . '%');
            } else {
                $query->with('order', 'user');
            }
        }

        $where = [];
        if (auth()->user()->isUser()) {

            $where = [['user_id', '=', auth()->user()->id]];
        }

        if (auth()->user()->isSupplier()) {
            $where = ['store_id' => auth()->user()->id];
        }

        if (auth()->user()->isCompany()) {

            $query->whereHas('companyOrder', function ($qu) {
                $qu->where('company_id', auth()->user()->id);
            });

        }

        if (auth()->user()->isRider()) {

                $query->whereHas('riderOrder', function ($qu) {
                    $qu->where('rider_id', auth()->user()->id);
                });

        }

        if ($status != 'all') {
            $where['status'] = $status;
        }

        if (auth()->user()->isCompany() || auth()->user()->isRider() || auth()->user()->isSupplier() || auth()->user()->isUser()) {
            $query->with(['orderItems' => function ($q) {
                $q->with(['product' => function ($s) {
                    $s->with('imagesWithTrashed');
                }, 'store' => function ($q) {
                    $q->select(['id', 'supplier_name', 'user_type', 'image', 'address', 'rating']);
                }]);
            }])->withcount('orderItems')->orderBy('updated_at', 'DESC');
        }


        $orders = $query->where($where)->latest()->paginate($this->getPaginate());

        foreach ($orders as $order) {
            $order = $this->setPrices($order);
        }


        return $orders;
    }

    public function get($orderId, $byId = false, $byUser = null)
    {
        try {
            $query = $this->getModel()->query();
            if (!$byId) {
                if ($byUser) {
                    $query->whereHas('orderItems')->with(['orderDetails', 'user', 'orderItems' => function ($q) {
                        $q->with('store', 'product', 'review');
                    }])->where(['id' => $orderId, 'user_id' => $byUser]);
                } else {
                    if (auth()->User()->isSupplier()) {

                        $query = $this->orderDetailRepository->getquery();
                        $query->with(['store', 'order' => function ($q) {
                            $q->with('user');
                        }]);
                    }

                    if (auth()->User()->isUser()) {
                        $query->whereHas('orderItems')->with(['orderDetails' => function ($q) {
                            $q->with(['store', 'review' => function ($q2) {
                                $q2->where('user_id', auth()->User()->id);
                            }]);
                        }, 'user', 'orderItems' => function ($q) {
                            $q->with(['store', 'orderDetail', 'product', 'review' => function ($q2) {
                                $q2->where('user_id', auth()->User()->id);
                            }]);
                        }])->where(['id' => $orderId, 'user_id' => auth()->User()->id]);

                    } elseif (auth()->User()->isSupplier()) {

                        $query->whereHas('orderItems', function ($q) {
                            $q->whereHas('product');
                        })->with(['orderItems', 'orderItems.product', 'store'])->where(['id' => $orderId]);

                    }
                }
                $order = $query->firstorfail();

                if (auth()->User()->isUser()) {
                    $orderDetails = collect($order->orderDetails);
                    $filtered = $orderDetails->filter(function ($value, $key) {
                        if (!is_null($value->review)) {
                            return false;
                        } else {
                            return true;
                        }
                    });
                    if ($filtered->count() > 0) {
                        $order->can_review_store = true;
                    } else {
                        $order->can_review_store = false;
                    }
                }

                $order = $this->setPrices(json_decode($order));

            } else {
                $query->whereHas('orderItems')->with(['orderDetails', 'user', 'orderItems' => function ($q) {
                    $q->with('store');
                }])->where(['id' => $orderId]);
                $order = $query->firstOrFail();
            }
            return $order;
        } catch (Exception $e) {
            dd($e->getMessage());
            throw new Exception($e->getMessage());
        }

    }

    public function cancel($request)
    {

        DB::beginTransaction();
        try {


            if (auth()->user()->user_type == 'user' || auth()->user()->user_type == 'supplier') {
                if ($request->cancel_reason == '' || $request->cancel_reason == null) {
                 throw new Exception(__('Please Select Cancel Reason'));
                }
            }

            if (auth()->user()->isSupplier()) {
                //when supplier cancel order
                $this->supplierCancelOrder($orderDetailId = $request->id, $storeId = $request->store_id, $orderId = $request->order_id, $orderUserId = $request->orderUserId, $cancel_reason = $request->cancel_reason);
            }

            if (auth()->user()->isCompany()) {
                //when supplier cancel order
                $this->companyCancelOrder($orderDetailId = $request->order_detail_id, $storeId = $request->store_id, $orderId = $request->order_id, $orderUserId = $request->orderUserId, $cancel_reason = $request->cancel_reason);
            }

            if (auth()->user()->isUser()) {
                // when user cancel order

                $this->userCancelOrder($orderId = $request->id, $storeId = $request->store_id, $cancel_reason = $request->cancel_reason);
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function supplierCancelOrder($orderDetailId = null, $storeId = null, $orderId = null, $orderUserId = null, $cancel_reason = null)
    {
        //Get the Where Query

        $orderQuery = Order::query();
        $orderDetailQuery = $this->orderDetailRepository->getquery();
        $orderDetailItemQuery = $this->orderDetailItemRepository->getquery();
        $productQuery = $this->productRepository->getquery();


        //Update Order Detail Table For Supplier
        $orderDetail = $orderDetailQuery->where(['id' => $orderDetailId, 'store_id' => $storeId])->first();
        $orderDetail->update(['status' => 'cancelled', 'cancel_reason' => $cancel_reason]);

        //Update Order Table for User
        $order = $orderQuery->where(['id' => $orderId])->with('user')->first();
        $user_name = $order->user->user_name;
        $order->update(['status' => 'cancelled', 'cancel_reason' => $cancel_reason]);

        $orderDetail_list = OrderDetail::where('order_id', $orderId)->get();
        $orderDetail_dublicate = OrderDetail::where('order_id', $orderId)->where('id', '!=', $orderDetailId)->get();
        if (count($orderDetail_list) > 1) {
            $status = '';
            $status_array = [];
            foreach ($orderDetail_list as $item) {
                array_push($status_array, $item->status);
            }

            $unique_arr = array_unique($status_array);
            if (count($unique_arr) >= 1 && (in_array('accepted', $unique_arr) || in_array('pending', $unique_arr))) {
                $status = 'in-progress';
            } elseif (count($unique_arr) >= 1 && in_array('completed', $unique_arr)) {
                $status = 'completed';
                foreach ($orderDetail_dublicate as $item) {
                    if ($item->status !== 'completed') {
                        $status = 'in-progress';
                    }
                }
            } else {
                $status = 'in-progress';
            }

            $order->update(['status' => $status, 'cancel_reason' => $cancel_reason]);
        } else {
            $order->update(['status' => 'cancelled', 'cancel_reason' => $cancel_reason]);
        }

        // Update Product in Order
        $orderDetailItem = $orderDetailItemQuery->where(['order_detail_id' => $orderDetailId, 'store_id' => $storeId])->select('product_id', 'quantity')->get();
        foreach ($orderDetailItem as $orderItems) {
            $product = Product::findOrFail($orderItems->product_id);
            $product->update([
                'quantity' => $product->quantity + $orderItems->quantity,
                'sold' => $product->sold - $orderItems->quantity
            ]);
        }

        $order = $this->getQuery()->where(['id' => $orderId])->first();
        $notificationArray = ['extras->order_id' => $order->id,'sender_id' => auth()->user()->id, 'receiver_id' => $orderUserId, 'display_name' => $user_name, 'conversation_id' => $orderId, 'order_no' => $order->order_number, 'title->en' => "Order Cancelled", 'description->en' => "Your Order Has Been Cancelled", 'title->ar' => "تم الغاء الأمر او الطلب", 'description->ar' => "تم إلغاء طلبك"];
        $this->notification($notificationArray);
    }

    public function companyCancelOrder($orderDetailId = null, $storeId = null, $orderId = null, $orderUserId = null, $cancel_reason = null)
    {
        //Get the Where Query
        $orderQuery = Order::query();
        $orderDetailQuery = $this->orderDetailRepository->getquery();

        //Update Order Detail Table For Supplier
        $orderDetail = $orderDetailQuery->where(['id' => $orderDetailId])->first();
        $orderDetail->update(['status' => 'pending']);

        //Update Order Table for User
        $order = $orderQuery->where(['id' => $orderId])->first();
        $order->update(['status' => 'pending']);

        $this->orderCompanyRepository->delete($orderId,$orderDetailId);

    }

    public
    function userCancelOrder($orderId = null, $storeId = null, $cancel_reason = null)
    {


        $orderQuery = Order::query();
        $orderDetailQuery = $this->orderDetailRepository->getquery();
        $orderDetailItemQuery = $this->orderDetailItemRepository->getquery();
        $productQuery = $this->productRepository->getquery();

        //update Order table
        $order = $orderQuery->where(['id' => $orderId])->first();
        $order->update(['status' => 'cancelled', 'cancel_reason' => $cancel_reason]);


        //update order Detail table for Supplier
        $orderDetails = $orderDetailQuery->where('order_id', $orderId)->with('store')->get();
        foreach ($orderDetails as $item) {
            $supplier_name = $item->store->supplier_name['en'];
            $item->update(['status' => 'cancelled', 'cancel_reason' => $cancel_reason]);

            //update Order Detail Item table for product
            $orderItems = $orderDetailItemQuery->where(['order_detail_id' => $item->id, 'store_id' => $item->store_id])->select('product_id', 'quantity')->get();
            foreach ($orderItems as $orderItem) {
                $product = Product::findOrFail($orderItem->product_id);
                $product->update([
                    'quantity' => $product->quantity + $orderItem->quantity,
                    'sold' => $product->sold - $orderItem->quantity
                ]);
            }

            $notificationArray = [
                'sender_id' => auth()->user()->id,
                'receiver_id' => $item->store_id,
                'display_name' => $supplier_name,
                'conversation_id' => $item->id,
                'extras->order_id' => $order->id,
                'order_no' => $item->order_no,
                'title->en' => "Order Cancelled",
                'description->en' => "Your Order Has Been Cancelled",
                'title->ar' => "تم الغاء الأمر او الطلب",
                'description->ar' => "تم إلغاء طلبك"
            ];
            $this->notification($notificationArray);
        }

    }


    public
    function assign($request)
    {
        DB::beginTransaction();
        try {
//            dd("dd");
//            $this->assignOrderToDriver($orderDetailId = $request->orderDetailId, $order_id = $request->order_id, $user_id = $request->orderUserId, $store_id = $request->store_id, $driver_id = $request->company_id);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }


    public
    function accept($request)
    {

        DB::BeginTransaction();
        try {

            $this->orderCompanyRepository->save(0, $request->company_id, $request->order_detail_id, $request->order_id, 'confirmed');
            $this->getModel()->where('id', $request->order_id)->update(['status' => 'confirmed']);
            $this->orderDetailRepository->getquery()->where('order_id', $request->order_id)->where('store_id', $this->getUser()->id)->update(['status' => 'confirmed']);

            sendNotification([
                'sender_id'        => $this->getUser()->id,
                'receiver_id'      => $request->company_id,
                'conversation_id'  => $request->order_id,
                'extras->order_id' => $request->order_id,
                'title->en'        => 'You have Recieved an order',
                'description->en'  => 'Order has been sent to you',
                'title->ar'        => 'تم قبول الطلب من قبل الشركة',
                'description->ar'  => 'تم قبول طلبك من قبل الشركة.',
                'action'           => 'ORDER'
            ]);

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function shippedByRider($orderDetailId)
    {
        DB::beginTransaction();
        try {


            $query = $this->orderCompanyRepository->getQuery()->where(['order_detail_id' => $orderDetailId]);
            $query->update(['status' => 'completed']);

            $companyOrder = $query->where('order_detail_id', $orderDetailId)->first();

            $orderId      = $this->orderDetailRepository->getQuery()->where('id', $orderDetailId)->first()->order_id;
            $query        = $this->orderDetailRepository->getQuery();
            $orderDetails = $query->where('id', $orderDetailId)->update(['status' => 'delivered']);
            $query        = $this->getQuery();
            $query->where('id', $orderId)->update(['status' => 'delivered']);
            $order = $this->getQuery()->where('id', $orderId)->first();

            sendNotification([
                'sender_id' => auth()->user()->id,
                'extras->order_id' => $order->id,
                'extras->image' => $order->image,
                'receiver_id' => $companyOrder->delivery_company_id,
                'title->en' => 'Your order Has Been Delivered.',
                'title->ar' => 'تم استلام طلب جديد',
                'description->en' => 'Your order (#' . $order->order_number . ') has been delivered by the rider. Please visit the order detail page for further details.',
                'description->ar' => 'لقد تلقيت طلبًا جديدًا',
                'action' => 'ORDER'
            ]);

            sendNotification([
                'sender_id' => auth()->user()->id,
                'extras->order_id' => $order->id,
                'extras->image' => $order->image,
                'receiver_id' => $order->supplier_id,
                'title->en' => 'Your order Has Been Delivered.',
                'title->ar' => 'تم استلام طلب جديد',
                'description->en' => 'Your order (#' . $order->order_number . ') has been delivered by the rider. Please visit the order detail page for further details.',
                'description->ar' => 'لقد تلقيت طلبًا جديدًا',
                'action' => 'ORDER'
            ]);

            sendNotification([
                'sender_id' => auth()->user()->id,
                'extras->order_id' => $order->id,
                'extras->image' => $order->image,
                'receiver_id' => $order->user_id,
                'title->en' => 'Your order Has Been Delivered.',
                'title->ar' => 'تم استلام طلب جديد',
                'description->en' => 'Your order (#' . $order->order_number . ') has been delivered by the rider. Please visit the order detail page for further details.',
                'description->ar' => 'لقد تلقيت طلبًا جديدًا',
                'action' => 'ORDER'
            ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollback();
        }
    }

    public
    function supplierAcceptOrder($orderID = null, $orderUserId = null)
    {
        $orderQuery = Order::query();
        $orderDetailQuery = $this->orderDetailRepository->getquery();

        //Update Order Detail Table For Supplier
        $orderDetail = $orderDetailQuery->where(['id' => $orderID])->first();
        $orderDetail->update(['status' => 'accepted']);

        //Update Order Table for User
        $orderDetail = $orderDetailQuery->where(['id' => $orderID])->first();
        $order_id = $orderDetail->order_id;
        $order = $orderQuery->where(['id' => $orderDetail->order_id])->first();

        //new changes in order from multiple store
        $orderDetail_list = OrderDetail::where('order_id', $order_id)->get();
        if (count($orderDetail_list) > 1) {
            $status = '';
            $status_array = [];
            foreach ($orderDetail_list as $item) {
                array_push($status_array, $item->status);
            }
            $unique_arr = array_unique($status_array);

//            if (count($unique_arr) === 1 && (in_array('accepted', $unique_arr) || in_array('pending', $unique_arr))) {
//                $status = 'in-progress';
//            } elseif (count($unique_arr) === 1 && in_array('completed', $unique_arr)) {
//                $status = 'completed';
//            } else {
//                $status = 'in-progress';
//            }

            $status = 'in-progress';

            $order->update(['status' => $status]);
        } else {
            $order->update(['status' => 'accepted']);
        }

        $user_name = $order->user->user_name;

        $orderDetail = $orderDetailQuery->where(['id' => $orderID])->first();
        $order = $orderQuery->where(['id' => $orderDetail->order_id])->first();
        $notificationArray = [
            'sender_id' => auth()->user()->id,
            'receiver_id' => $orderUserId,
            'display_name' => $user_name,
            'conversation_id' => $orderID,
            'order_no' => $order->order_number,
            'title->en' => "Order Accepted",
            'description->en' => "Your order has been accepted.",
            'title->ar' => 'تم قبول الطلب من قبل الشركة',
            'description->ar' => 'تم قبول طلبك من قبل الشركة.'
        ];
        $this->notification($notificationArray);

    }

    public
    function delivered($request)
    {
        DB::beginTransaction();
        try {
            $user = $this->getUser();
            dd("d");
//            $this->driverDeliveredOrder($oderId = $request->id, $userId = $request->orderUserId, $storeId = $request->storeId, $driverId = $request->driverId);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }


    public
    function complete($request)
    {

        DB::BeginTransaction();
        $user = $this->getUser();

        try {

            $order = $this->getModel()->getquery()->where('id', $request->order_id)->first();

            $user->total_earning = $user->total_earning + $order->total;
            $commission = 0;

            if ($order->payment_method == 'cash_on_delivery') {
                $oldBalance              = $user->available_balance;
                if(config('settings.revenue_stream') == 'commission'){
                 $commission =  (config('settings.commission') / 100) * ($order->subtotal + $order->shipping);
                 $newBalance              = $oldBalance - $order->vat - $commission;
                }else{
                $newBalance              = $oldBalance - $order->vat;
                }
                $user->available_balance = $newBalance;
                $user->save();
            } else {
                if(config('settings.revenue_stream') == 'commission'){
                $commission =  (config('settings.commission') / 100) * ($order->subtotal + $order->shipping);
                $totalWithoutVat = $order->total - $order->vat - $commission;
                }else{
                    $totalWithoutVat = $order->total - $order->vat;
                }

                $user->available_balance = $user->available_balance + $totalWithoutVat;
                $user->save();
            }
            $this->orderDetailRepository->getquery()->where('order_id', $request->order_id)->where('store_id', $this->getUser()->id)->update(['status' => 'completed','commission' => $commission]);
            $orderDetails = $this->orderDetailRepository->getquery()->where('order_id', $request->order_id)->where('store_id', $this->getUser()->id)->get();

            $this->completeStatusCheck($request->order_id, $orderDetails, $orderCompleted = true);

            $notificationArray = [
                'sender_id'        => $this->getUser()->id,
                'receiver_id'      => $order->user_id,
                'conversation_id'  => $request->order_id,
                'extras->order_id' => $request->order_id,
                'title->en'        => 'Order Completed',
                'title->ar'        => 'تم اكتمال الطلب',
                'description->en'  => 'Your Order Has Been Completed',
                'description->ar'  => 'تم إكمال طلبك',
                'action'           => 'ORDER'
            ];
            $this->notification($notificationArray);


            $storeArray   = [];
            $productArray = [];


            foreach ($orderDetails as $orderDetail) {
                if ($orderDetail->status == 'completed') {

                    foreach ($orderDetail->orderItems as $orderItem) {

                        $reviewCollectionSupplier = collect([
                            'user_id'     => $order->user_id,
                            'supplier_id' => $orderItem->store_id,
                            'product_id'  => 0,
                            'rating'      => 0.0,
                            'review'      => '',
                            'is_reviewed' => false,
                        ]);

                        $notificationArray =
                            [
                                'sender_id'       => $this->user->id,
                                'receiver_id'     => $order->user_id,
                                'conversation_id' => $orderItem->store_id,
                                'title->en'       => 'Rate & Review',
                                'description->en' => 'Your Order Has Been Completed To Rate Supplier.',
                                'title->ar'       => 'مراجعة و تقييم',
                                'description->ar' => 'تم إكمال طلبك لتقييم المتجر.',
                                'action'          => 'SUPPLIER_REVIEWS'
                            ];

                        $this->notificationStore($notificationArray);

                        array_push($storeArray, $orderItem->store_id);

                        // $reviewSaveDto = ReviewSaveDto::fromCollection($reviewCollectionSupplier);
                        // $this->reviewRepository->save($reviewSaveDto);

                        // $reviewCollectionProduct = collect([
                        //     'user_id'     => $order->user_id,
                        //     'supplier_id' => 0,
                        //     'product_id'  => $orderItem->product_id,
                        //     'rating'      => 0.0,
                        //     'review'      => '',
                        //     'is_reviewed' => false,
                        // ]);

                        $notificationArray = [
                            'sender_id'       => $this->user->id,
                            'receiver_id'     => $order->user_id,
                            'conversation_id' => $orderItem->product_id,
                            'product_id' => $orderItem->product_id,
                            'title->en'       => 'Rate & Review',
                            'description->en' => 'Your Order Has Been Completed To Rate Product.',
                            'title->ar'       => 'مراجعة و تقييم',
                            'description->ar' => 'تم إكمال طلبك لتقييم المنتج.',
                            'action'          => 'PRODUCT_REVIEWS'
                        ];
                        $this->notificationProduct($notificationArray);
                        array_push($productArray, $orderItem->product_id);

                        // $reviewSaveDto = ReviewSaveDto::fromCollection($reviewCollectionProduct);
                        // $this->reviewRepository->save($reviewSaveDto);
                    }
                }
            }
            $this->getModel()->where('id', $request->order_id)->update(['status' => 'completed']);

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }


    public function completeStatusCheck($orderId, $orderDetails, $orderCompleted)
    {

        $updateOrder = [];
        foreach ($orderDetails as $orderDetail) {
            if ($orderDetail->status == 'pending' || $orderDetail->status == 'confirmed') {
                $orderCompleted = false;
            }
        }

        if ($orderCompleted) {
            $updateOrder = ['status' => 'completed'];
        }


        $this->getModel()->where('id', $orderId)->update($updateOrder);
        return true;
    }

    public
    function supplierOrderComplete($orderDetailId = null, $total_amount = null, $storeId = null, $userId = null, $orderId = null)
    {
        $orderQuery = Order::query();
        $orderDetailQuery = $this->orderDetailRepository->getquery();


        //update Order Detail For Supplier
        $orderDetail = $orderDetailQuery->where(['id' => $orderDetailId])->with('store')->first();
        $supplier_name = $orderDetail->store->supplier_name['en'];
        $orderDetail->update(['status' => 'completed']);


        $user = $this->getUser();
        $user->update([
            'total_earning' => $user['total_earning'] + $total_amount
        ]);

        $user = $this->getUser();
        if ($orderDetail->order->payment_method == 'cash_on_delivery') {
            $oldBalance = $user->available_balance;
            $newBalance = null;
            if ($oldBalance != null || $oldBalance != 0) {
                $newBalance = $oldBalance - $orderDetail->vat;
            }
            $user->update([
                'available_balance' => $newBalance
            ]);
        } else {
//            $totalWithoutVat = $orderDetail->total - $orderDetail->vat;
//            $user->update([
//                'available_balance' => $user['available_balance'] + $totalWithoutVat
//            ]);
        }


        //update Order table for user
        $order = $orderQuery->where('id', $orderId)->first();
//        $order->update(['status' => 'completed']);

        $orderDetail_list = OrderDetail::where('order_id', $orderId)->get();
        $orderDetail_dublicate = OrderDetail::where('order_id', $orderId)->where('id', '!=', $orderDetailId)->get();
        if (count($orderDetail_list) > 1) {
            $status = '';
            $status_array = [];
            foreach ($orderDetail_list as $item) {
                array_push($status_array, $item->status);
            }

            $unique_arr = array_unique($status_array);

            if (count($unique_arr) >= 1 && (in_array('accepted', $unique_arr) || in_array('pending', $unique_arr))) {
                $status = 'in-progress';
            } elseif (count($unique_arr) >= 1 && (in_array('completed', $unique_arr) || in_array('cancelled', $unique_arr))) {
                $status = 'completed';

//                if (in_array('completed', $unique_arr)) {
//                    foreach ($orderDetail_dublicate as $item) {
//                        if ($item->status !== 'completed') {
//                            $status = 'in-progress';
//                        }
//                    }
//                }

                if (in_array('cancelled', $unique_arr)) {
                    foreach ($orderDetail_dublicate as $item) {
                        if ($item->status !== 'cancelled') {
                            $status = 'in-progress';
                        }
                    }
                }

            } else {
                $status = 'in-progress';
            }

            $order->update(['status' => $status]);
        } else {
            $order->update(['status' => 'completed']);
        }


        $order = $orderQuery->where('id', $orderId)->first();
        //notification for User
        $notificationArray = ['sender_id' => $this->user->id,
            'receiver_id' => $userId, 'conversation_id' => $orderId,
            'order_no' => $order->order_number,
            'display_name' => $supplier_name,
            'title->en' => "Order Completed",
            'title->ar' => 'تم اكتمال الطلب',
            'description->en' => "Your Order Has Been Completed",
            'description->ar' => 'تم إكمال طلبك'
        ];
        $this->notification($notificationArray);


        $storeArray = [];
        // Review Notification For Store And Product
        foreach ($order->orderItems as $orderItem) {
            $product = $this->productRepository->get($orderItem->product_id);
            if ($orderItem->orderDetail->status == 'completed' && $orderItem->orderDetail->id == $orderDetailId && isset($product)) {
                if (!in_array($orderItem->store_id, $storeArray)) {
                    //rate Store notification
                    $notificationArray = [
                        'sender_id' => $this->user->id,
                        'receiver_id' => $userId,
                        'conversation_id' => $orderItem->store_id,
                        'order_no' => $order->order_number,
                        'display_name' => $supplier_name,
                        'title->en' => __('Rate Store'),
                        'description->en' => "Your Order Has Been Completed To Store",
                        'title->ar' => 'مراجعة و تقييم',
                        'description->ar' => 'تم إكمال طلبك لتقييم المتجر.',
                        'action' => 'STORE_REVIEWS'];
                    $this->notificationStore($notificationArray);
                    array_push($storeArray, $orderItem->store_id);
                }

                // product Rate notification
                $notificationArray = ['sender_id' => $this->user->id,
                    'receiver_id' => $userId,
                    'conversation_id' => $product->slug,
                    'product_id' => $product->id,
                    'display_name' => $supplier_name,
                    'order_no' => $order->order_number,
                    'title->en' => "Rate Product", 'title->ar' => 'مراجعة و تقييم',
                    'description->en' => "Your Order Has Been Completed To Rate Product.",
                    'description->ar' => 'تم إكمال طلبك لتقييم المتجر.', 'action' => 'PRODUCT_REVIEWS'];
                $this->notificationProduct($notificationArray);
            }
        }

        $this->shareInvoiceToEmail($orderDetailId, $orderId);

    }

    public
    function notification($notificationArray)
    {

        sendNotification([
            'sender_id' => $notificationArray['sender_id'],
            'receiver_id' => $notificationArray['receiver_id'],
            'extras->order_id' => $notificationArray['conversation_id'],
            'extras->order_no' => $notificationArray['extras->order_id'],
            'title->en' => $notificationArray['title->en'],
            'title->ar' => $notificationArray['title->ar'],
            'description->en' => $notificationArray['description->en'],
            'description->ar' => $notificationArray['description->ar'],
            'action' => isset($notificationArray['action']) ? $notificationArray['action'] : 'ORDER'
        ]);
    }

    public
    function notificationStore($notificationArray)
    {

        sendNotification([
            'sender_id' => $notificationArray['sender_id'],
            'receiver_id' => $notificationArray['receiver_id'],
            'extras->store_id' => $notificationArray['conversation_id'],
            'title->en' => $notificationArray['title->en'],
            'title->ar' => $notificationArray['title->ar'],
            'description->en' => $notificationArray['description->en'],
            'description->ar' => $notificationArray['description->ar'],
            'action' => isset($notificationArray['action']) ? $notificationArray['action'] : 'ORDER'
        ]);
    }

    public
    function notificationProduct($notificationArray)
    {

        sendNotification([
            'sender_id' => $notificationArray['sender_id'],
            'receiver_id' => $notificationArray['receiver_id'],
            'extras->product_slug' => $notificationArray['conversation_id'],
            'extras->product_id' => $notificationArray['product_id'],
            'title->en' => $notificationArray['title->en'],
            'title->ar' => $notificationArray['title->ar'],
            'description->en' => $notificationArray['description->en'],
            'description->ar' => $notificationArray['description->ar'],
            'action' => isset($notificationArray['action']) ? $notificationArray['action'] : 'ORDER'
        ]);
    }

    public
    function shareInvoiceToEmail($orderDetailId = null, $orderId = null)
    {
        $orderQuery = Order::query();
        $orderDetailQuery = $this->orderDetailRepository->getquery();

        // Send Invoice Email To  User
        if ($orderDetailId == null) {
            $orderDetail = $orderDetailQuery->where(['order_id' => $orderId])->first();
        } else {
            $orderDetail = $orderDetailQuery->where(['id' => $orderDetailId])->first();
        }
        $order = $orderQuery->where('id', $orderId)->first();


        $city = City::where('id', $order->address['city_id'])->first();
        $data['receiver_email'] = $order->user->email;
        $data['receiver_name'] = $order->user->user_name;
        $data['sender_name'] = config('settings.company_name');
        $data['sender_email'] = config('settings.email');
        $data['loggedInUser'] = $order->user;
        $this->user = $order->user;
        $data['orderDetail'] = $orderDetail;
        $data['city_name'] = $city->name['en'];
        $data['order'] = $this->get($orderId, false, $order->user_id);
        $this->sendMail($data, 'emails.user.invoice', 'Order Invoice', $data['receiver_email'], $data['sender_email']);
    }


    public function PrintOrder($request, $id)
    {
        try {

            $order = $this->get($id, false, null);

            if($this->getUser()->user_type == 'supplier'){
                $data['loggedInUser'] = $order->store;
                $data['order'] = $order;
            }else{
                $data['loggedInUser'] = $order->user;
                $data['order'] = $order;
            }

            $pdf = PDF::loadView('front.order.orderPdf', ['order' => $data['order'], 'loggedInUser' => $data['loggedInUser']]);
            $format = '.pdf';
            $path = 'uploads/pdf/';

            File::makeDirectory($path, 0777, true, true);
//            }
            if($this->getUser()->user_type == 'supplier'){
                $fileName = $path . $order->order_no . $format;
            }else{
                $fileName = $path . $order->order_number . $format;
            }

            $pdf->save($fileName);

            $this->getModel()->where('id', '=', $id)->update(['invoice' => $fileName]);
            return $fileName;

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function sendpdf($id)
    {

        $user = $this->getUser();
        $order  = $this->getQuery()->whereHas('orderItems')->with(['orderDetails', 'user', 'orderItems' => function ($q) {
            $q->with(['product', 'store']);
        }])->where(['id' => $id])->first();

        // $order = $this->get($id);
        $data = collect([
            'receiver_name'  => $order->user->user_name,
            'receiver_email' => $order->user->email,
            'subject'        => 'Order Invoice',
            'sender_email'   => config('settings.email'),
            'sender_name'    => config('settings.company_name'),
            'loggedInUser'   => $user,
            'view'           => 'front.dashboard.order.pdf',
            'data'           => [
                'order' => $order,
                'user'  => $user,
            ]
        ]);

        $sendEmailDto = SendEmailDto::fromCollection($data);

        SendMail::dispatch($sendEmailDto);
        return true;
    }

}
