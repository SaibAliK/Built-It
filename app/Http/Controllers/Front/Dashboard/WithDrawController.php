<?php

namespace App\Http\Controllers\Front\Dashboard;

use App\Http\Repositories\UserRepository;
use App\Http\Repositories\WithDrawRepository;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WithDrawController extends Controller
{
    protected UserRepository $userRepository;
    protected WithDrawRepository $withDrawRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->withDrawRepository = new WithDrawRepository();
        $this->breadcrumbs[0] = ['url' => route('front.dashboard.index'), 'title' => __('Home')];
    }

    public function paymentProfile(Request $request)
    {
        try {
            if (!isset($request->payment_info) && !isset($request->payment_profile) && !isset($request->withdraw_request)) {
                $request->merge([
                    'payment_info' => true
                ]);
            }

            $this->breadcrumbTitle = __('Payment Profile');
            $this->breadcrumbs[1] = ['url' => '', 'title' => __('Payment Profile')];

            $withdraws = Withdraw::where('user_id', $this->user['id'])->get();

            $amount = 0;
            if (count($withdraws) > 0) {
                foreach ($withdraws as $key => $withdraw) {
                    if ($withdraw->status == 'completed') {
                        $amount += $withdraw->amount['sar']['amount'];
                    }
                }
            }
            return view('front.dashboard.paymentProfile.payment-profile', [
                'withdraws' => $withdraws->toArray(),
                'released_amount' => $amount
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('err', $e->getMessage())->withInput();
        }


    }

    public function updatePaymentProfile(Request $request)
    {
        $isUserValid = $this->withDrawRepository->checkPaypalValidation($request->get('client_id'), $request->get('secret_id'));
        if ($isUserValid) {
            $user = $this->userRepository->updatePaymentInfo($request);
            if ($user) {
                return redirect()->back()->with('status', __('Payment profile updated successfully.'));
            }
            return redirect()->back()->with('err', __('Something went wrong.'));
        }
        return redirect()->back()->with('err', __('Credential is not Valid.'));
    }

    public function withdrawPayment(Request $request)
    {
        try {
            if ($request->amount > \auth()->user()->available_balance) {
                return redirect()->back()->with('err', __('Amount is greater than available amount.'));
            }
            $withdraw = $this->withDrawRepository->save($request, $fromWeb = true);
            if ($withdraw == false) {
                return redirect()->back()->with('err', __('One request already in queue.'));
            }
            if ($withdraw) {
                return redirect()->back()->with('status', __('Withdraw request successful.'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());
        }
    }
}
