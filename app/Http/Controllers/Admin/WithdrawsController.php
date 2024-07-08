<?php

namespace App\Http\Controllers\Admin;

use App\Http\Repositories\AdminRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Repositories\WithDrawRepository;
use App\Models\User;
use App\Models\Withdraw;
use App\Http\Controllers\Controller;
use App\Traits\MyTechnologyPayPal;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;


class WithdrawsController extends Controller
{

    use MyTechnologyPayPal;

    protected AdminRepository $adminRepository;
    protected WithDrawRepository $withDrawRepository;
    protected UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct('adminData', 'admin');
        $this->breadcrumbTitle = 'Payment Requests';
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
        $this->withDrawRepository = new WithDrawRepository;
        $this->adminRepository = new AdminRepository();
        $this->userRepository = new UserRepository();
        $this->userRepository = new UserRepository;
    }

    public function index(Request $request)
    {
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-wallet', 'title' => 'Manage Payment Release Requests'];
        $stores = User::where('user_type', 'supplier')->where('is_active', 1)->where('is_verified', 1)->where('expiry_date', '>', Carbon::now()->unix())->get();
        $withdraw_store = $this->withDrawRepository->all();

        $collection = collect($withdraw_store['data'])->unique();
        $uniques = array();
        foreach ($withdraw_store['data'] as $obj) {
            $uniques[$obj['user_id']] = $obj;
        }
        return view('admin.withdraws.index', compact('uniques', 'stores'));
    }

    public function all(Request $request)
    {
        if (!empty($request->store_id)) {
            return response($this->withDrawRepository->all($request->store_id));
        }
        return response($this->withDrawRepository->all());
    }

    public function reject($id)
    {
        $withdraw = Withdraw::find($id);
        if (empty($withdraw)) {
            return responseBuilder()->error(__("Withdraw request not found"));
        }
        if ($withdraw->status != "pending") {
            return responseBuilder()->error(__("Bad request"));
        }
        try {
            \DB::beginTransaction();
            Withdraw::where(['id' => $id])->update([
                'status' => 'rejected',
            ]);
            \DB::commit();
            return responseBuilder()->success(__("Withdraw request rejected successfully"));
        } catch (\Exception $e) {
            \DB::rollBack();
            return responseBuilder()->error();
        }
    }

    public function payWithPayPal($id)
    {
        try {
            $url = $this->withDrawRepository->payWithPayPal($id);
            if (empty($url)) {
                return redirect()->back()->with('err', __("Withdraw request not found"));
            }
            return ($url) ? redirect($url) : redirect()->back()->with('err', __('An unknown error occurred, try later'));
        } catch (Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());
        }
    }

    public function paypalPaymentProcessed($id)
    {
        $route = $this->withDrawRepository->paypalPaymentProcessed($id);
        if (!empty($route['url'])) {
            return redirect($route['url'])->with($route['status'], $route['msg']);
        }

        return redirect()->back()->with('error', __('Something went wrong.'));
    }

    public function paypalPaymentCanceled()
    {
        session()->forget('paypal_payment_id');
        return redirect(route('admin.withdraws.index'))->with('err', __('PayPal payment was canceled'));
    }

    public function payWithCash($id)
    {

        $route = $this->withDrawRepository->payWithCash($id);
        if (!empty($route)) {
            return redirect($route['url'])->with($route['status'], $route['msg']);
        }
        return redirect()->back()->with('error', __('Something went wrong.'));
    }

}
