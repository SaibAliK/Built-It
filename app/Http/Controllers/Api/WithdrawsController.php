<?php

namespace App\Http\Controllers\Api;


use App\Http\Repositories\UserRepository;
use App\Http\Repositories\WithDrawRepository;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WithdrawsController extends Controller
{
    protected UserRepository $userRepository;
    protected WithDrawRepository $withDrawRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->withDrawRepository = new WithDrawRepository();
    }

    public function paymentProfile()
    {
        $user = $this->userRepository->getUser();

        $withdraws = Withdraw::where('user_id', $user->id)->get();
        $amount = 0;
        if (count($withdraws) > 0) {
            foreach ($withdraws as $key => $withdraw) {
                if ($withdraw->status == 'completed') {
                    $amount += $withdraw->amount['sar']['amount'];
                }
            }
        }
        return responseBuilder()->success(__('data'), [
            'withdraws' => $withdraws->toArray(),
            'released_amount' => $amount
        ]);
    }

    public function updatePaymentProfile(Request $request)
    {
        $isUserValid = $this->withDrawRepository->checkPaypalValidation($request->get('client_id'), $request->get('secret_id'));
        if ($isUserValid) {
            $user = $this->userRepository->updatePaymentInfo($request);
            if ($user) {
                return responseBuilder()->success(__('Paypal Credentials Updated'), $user->toArray());
            }
            return responseBuilder()->success(__('Something went wrong.'));
        }
        return responseBuilder()->error('Credential is not Valid.');
    }

    public function withdrawPayment(Request $request)
    {
        if ($request->amount > \auth()->user()->available_balance) {
            return responseBuilder()->error(__('Amount is greater than available amount.'));
        }
        $withdraw = $this->withDrawRepository->save($request, $fromWeb = true);
        if ($withdraw == false) {
            return responseBuilder()->error(__('One request already in queue.'));
        }
        if ($withdraw) {
            return responseBuilder()->success(__('Withdraw request successful.'), $withdraw->toArray());
        }

        return responseBuilder()->error(__('Something went wrong.'));
    }
}
