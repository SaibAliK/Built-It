<?php

namespace App\Http\Controllers\Front\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Repositories\OrderDetailRepository;
use App\Http\Repositories\OrderRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Repositories\CompanyOrderRepository;
use App\Models\City;
// use App\Models\Reason;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use ArPHP\I18N\Arabic;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Response;

class OrderController extends Controller
{
    protected OrderDetailRepository $orderDetailRepository;
    protected UserRepository $userRepository;
    protected OrderRepository $orderRepository;
    protected CompanyOrderRepository $companyOrderRepository;

    public function __construct()
    {
        parent::__construct();
        $this->breadcrumbs[route('front.dashboard.index')] = ['title' => __('Home')];
        $this->orderRepository = new OrderRepository();
        $this->userRepository = new UserRepository();
        $this->orderDetailRepository = new OrderDetailRepository();
        $this->companyOrderRepository = new CompanyOrderRepository();
    }

    public function index(Request $request)
    {
        $this->orderRepository->getUser();
        if ($this->user->isUser()) {
            $this->breadcrumbTitle = __('My Orders');
            $this->breadcrumbs['javascript:{};'] = ['title' => __('My Orders')];
        } else {
            $this->breadcrumbTitle = __('My Orders');
            $this->breadcrumbs['javascript:{};'] = ['title' => __('My Orders')];
        }

        $this->orderRepository->setPaginate(5);
        $orders = $this->orderRepository->listing($request->status);
        $user = $this->user;

        return view('front.order.order', ['orders' => $orders, 'user' => $user]);
    }

    public function get($id)
    {
        try {
            $this->orderRepository->getUser();
            $this->breadcrumbTitle = __('My Orders');

            $delivery_companies = '';
            $riders = '';

            if ($this->user->isSupplier())
                $delivery_companies = $this->userRepository->typeUsers('company');

            if ($this->user->isCompany())
                $riders = $this->userRepository->typeUsers('rider');

            if ($this->user->isUser()) {
                $this->breadcrumbs[route('front.dashboard.order.index', ['status' => 'all'])] = ['title' => __('My Orders')];
                $this->breadcrumbs['javascript:{};'] = ['title' => __('Order Detail')];
            } else {
                $this->breadcrumbs[route('front.dashboard.order.index', ['status' => 'all'])] = ['title' => __('Manage Orders')];
                $this->breadcrumbs['javascript:{};'] = ['title' => __('Order Detail')];
            }

            $order = $this->orderRepository->get($id);
            $user = $this->user;

            // $reasons = Reason::where('reason_type', $user->user_type)->get();
            return view('front.order.order-detail', ['riders'=>$riders,'order' => $order, 'user' => $user, 'address' => $order->address, 'reasons' => [], 'delivery_companies' => $delivery_companies]);
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('err', $e->getMessage());
        }
    }

    public function cancelled(Request $request)
    {
        $this->orderRepository->getUser();
        try {
            if (!$this->user->user_type == 'driver') {
                if ($request->cancel_reason == '' || $request->cancel_reason == null) {
                    throw new Exception(__('Please Select Cancel Reason'));
                }
            }

            $this->orderRepository->cancel($request);
            return redirect()->route('front.dashboard.order.index', ['status' => 'all'])->with('status', __('Order Cancelled Successfully'));
        } catch (Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());
        }
    }

    public function assign(Request $request)
    {
        try {
            if ($request->delivery_company_id == '' || $request->delivery_company_id == null) {
                        throw new Exception(__('Please Select Driver Reason'));
            }

            $this->companyOrderRepository->assign($request);
            $this->companyOrderRepository->getQuery()->where('order_detail_id', $request->order_detail_id)->update(['status' => 'shipped']);
            $this->orderDetailRepository->getQuery()->where('id', $request->order_detail_id)->update(['status' => 'shipped']);
            $this->orderRepository->getQuery()->where('id', $request->order_id)->update(['status' => 'shipped']);
            return redirect()->route('front.dashboard.order.index', ['status' => 'all'])->with('status', __('Order assigned Successfully.'));
        } catch (\Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());
        }
    }

    public function accept(Request $request)
    {
        try {
            $this->orderRepository->accept($request);
            return redirect()->back()->with('status', __('Order Is Accepted'));
        } catch (Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());
        }
    }

    public function delivered(Request $request)
    {
        try {
            $this->orderRepository->getUser();
            $this->orderRepository->delivered($request);
            return redirect()->back()->with('status', __('Order Is Delivered'));
        } catch (Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());
        }
    }

    public function complete(Request $request)
    {
        try {
            $this->orderRepository->getUser();
            $this->orderRepository->complete($request);
            return redirect()->back()->with('status', __('Order Is Completed'));
        } catch (Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());
        }
    }

    public function shareInvoiceToEmail(Request $request, $id)
    {
        try {
            $this->orderRepository->getUser();
            $this->orderRepository->shareInvoiceToEmail(null, $id);
            return redirect()->back()->with('status', __('Invoice Share'));
        } catch (Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());

        }
    }

    public function printPdf(Request $request, $id)
    {
        try {
            $this->orderRepository->getUser();

            $order_pdf = $this->orderRepository->PrintOrder($request, $id);

            if (str_contains($order_pdf, 'pdf'))
                return redirect(url($order_pdf));
            else
                return redirect()->back()->with('err', __('Something went wrong.'));
        } catch (Exception $e) {
            return redirect()->back()->with('err', __($e->getMessage()));
        }
    }

    public function sendPDF($id)
    {
        $order_pdf = $this->orderRepository->sendpdf($id);
        return redirect()->back()->with('status', __('Order Invoice Send To You Email.'));
    }

    public function update($id, $slug, Request $request)
    {
        try {

            $request->merge(['order_id' => $id]);
            $request->merge(['order_detail_id' => $request->order_detail_id]);
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

            return redirect()->route('front.dashboard.order.index', ['status' => 'all'])->with('status', __('Order Updated Successfully.'));
        } catch (Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->with('err', __($e->getMessage()));
        }
    }

    public function show($id)
    {
        try {

            $this->breadcrumbTitle = __('My Order');
            $this->breadcrumbs['javascript: {};'] = ['title' => __('My Order')];

            $order = $this->orderRepository->get($id, true);

            $this->userRepository->setSelect(['supplier_name', 'image', 'address', 'id']);

            $delivery_companies = '';

            $riders = '';

            if (auth()->user()->isSupplier())
                $delivery_companies = $this->userRepository->typeUsers('company');

            if (auth()->user()->isCompany())
                $riders = $this->userRepository->typeUsers('rider');

            if (NULL != $order)


                return view('front.order.order-detail', compact('order', 'delivery_companies', 'riders'));

            return redirect()->back()->with('err', __('Order Not Found'));
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return redirect()->back()->with('err', __($e->getMessage()));
        }
    }

}
