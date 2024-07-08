<?php


namespace App\Http\Repositories;

use App\Http\Libraries\DataTable;
use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Withdraw;
use App\Models\User;
use App\Traits\MyTechnologyPayPal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use Exception;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;


class WithDrawRepository extends Repository
{
    use MyTechnologyPayPal;

    public function __construct()
    {
        $this->setModel(new Withdraw());
    }

    public function checkPaypalValidation($client_id = null, $secret_id = null)
    {
        if (!empty($client_id) && !empty($secret_id)) {
            $ch = curl_init();
            $clientId = $client_id;
            $secret = $secret_id;

            curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $secret);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

            $result = json_decode(curl_exec($ch), true);

            if (!empty($result['error'])) {
                curl_close($ch);
                return false;
            } else {
                curl_close($ch);
                if (!empty($result['access_token'])) {
                    return true;
                }
                return false;
            }

            curl_close($ch);
        }
        return false;

    }

    public function save($request, $fromWeb = false)
    {
        DB::beginTransaction();
        try {
            $user = $this->getUser();
            $count = $this->getModel()->where([['user_id', $user->id], ['status', 'pending']])->count();
            if ($count > 0) {
                return false;
            }
            $withDraw = $this->getModel()->create([
                'user_id' => $user->id,
                'amount' => $request->get('amount', 0),
                'method' => $request->method
            ]);
            DB::commit();
            return $withDraw;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function get()
    {
        $withdraws = $this->getModel()->where('user_id', $this->user['id'])->get();
        $amount = 0;
        if (count($withdraws) > 0) {
            foreach ($withdraws as $key => $withdraw) {
                if ($withdraw->status == 'completed') {
                    $amount += $withdraw->amount['aed']['amount'];
                }
            }
        }
        return view('front.dashboard.store.payment-profile', [
            'withdraws' => $withdraws->toArray(),
            'released_amount' => $amount
        ]);
    }

    public function store()
    {
        $query = $this->getQuery();
        $data = $query->with(['store'])->get()->unique('user_id');

        return $data;

    }

    public function all($id = null)
    {
        $columns = [
            ['db' => 'id', 'dt' => 'id'],
            ['db' => 'user_id', 'dt' => 'user_id'],
            ['db' => 'amount', 'dt' => 'amount'],
            ['db' => 'status', 'dt' => 'status'],
            ['db' => 'method', 'dt' => 'method'],
            ['db' => 'created_at', 'dt' => 'created_at'],
        ];
        $userWhere = function ($query) {
            $query->with('languages');
        };
        DataTable::init(new Withdraw(), $columns);
        DataTable::with('user');
        $store_id = \request('datatable.query.store_name', '');
        $status = \request('datatable.query.withdraw_status', '');

        if (!empty($store_id)) {
            DataTable::where('user_id', '=', intval($store_id));
        }

        if (!empty($id)) {
            DataTable::where('user_id', '=', intval($id));
        }

        if (!empty($status)) {
            DataTable::where('status', '=', $status);
        }
        $withdraws = DataTable::get();
        $start = 1;
        if ($withdraws['meta']['start'] > 0 && $withdraws['meta']['page'] > 1) {
            $start = $withdraws['meta']['start'] + 1;
        }
        $count = $start;
        if (sizeof($withdraws['data']) > 0) {
            $dateFormat = config('settings.date-format');

            foreach ($withdraws['data'] as $key => $item) {
                $withdraws['data'][$key]['index'] = $count++;

                $actions = '';
                if (empty($item['user']->client_id) && empty($item['user']->secret_id)) {
                    $actions = 'kindly update paypal credential';
                } else if ($item['status'] == 'pending') {
                    if (strlen($item['user']->client_id) > 0 && strlen($item['user']->secret_id) > 0 && $item['method'] == 'paypal') {
                        $actions = '<a class="m-portlet__nav-link btn m-btn m-btn--hover-success m-btn--icon m-btn--icon-only m-btn--pill" href="' . route('admin.dashboard.withdraws.pay-with-paypal', ['withdraws' => $item['id']]) . '" title="Pay with PayPal"><i class="fa fa-fw fa-paypal"></i></a>';
                    }
                }

                if ($item['status'] == 'completed') {
                    $actions = '---';
                }
                if ($item['status'] == 'rejected') {
                    $actions = '---';
                }
                $withdraws['data'][$key]['actions'] = $actions;


                if (!empty($item['user']['supplier_name']) && count($item['user']['supplier_name']) > 0) {
                    $withdraws['data'][$key]['store_name'] = $item['user']['supplier_name']['en'];
                } else {
                    $withdraws['data'][$key]['store_name'] = 'N/A';
                }

                $withdraws['data'][$key]['amount'] = $item['amount']['aed']['amount'];
                $withdraws['data'][$key]['status'] = ucfirst($item['status']);
                $withdraws['data'][$key]['created_at'] = Carbon::createFromTimestamp($item['created_at'])->format('d M, Y');
                unset($withdraws['data'][$key]['user']);
            }
        }
        return $withdraws;
    }

    public function reject($id)
    {
        $withdraw = $this->getModel()->find($id);
        if (empty($withdraw)) {
            return responseBuilder()->error(__("Withdraw request not found"));
        }
        if ($withdraw->status != "pending") {
            return responseBuilder()->error(__("Bad request"));
        }
        try {
            \DB::beginTransaction();
            $this->getModel()->where(['id' => $id])->update([
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
        DB::beginTransaction();
        try {
            $withdraw = $this->getModel()->with('user')->whereHas('user')->find($id);
            if (!empty($withdraw) && $withdraw->status == "pending" && (strlen($withdraw->user->client_id) > 0 || strlen($withdraw->user->secret_id) > 0)) {

                $this->initPayPal($withdraw->user->client_id, $withdraw->user->secret_id);
                $this->itemList = new ItemList();
                $paypalItem = new Item();
                $name = ['ar' => 'Withdraw request payment',
                    'en' => 'Withdraw request payment'
                ];
                $desc = ['ar' => 'Requested by ' . $withdraw->user->email,
                    'en' => 'Requested by ' . $withdraw->user->email
                ];
                $paypalItem->setName(\Illuminate\Support\Str::limit(translate($name), 250))
                    ->setDescription(\Illuminate\Support\Str::limit(translate($desc), 225))
                    ->setCurrency('USD')
                    ->setQuantity(1)
                    ->setPrice(getUsdPrice($withdraw->getOriginal('amount')['aed']['amount']));
                $this->itemList->addItem($paypalItem);

                $user_id = $withdraw->user->id;
                $amount = $withdraw->amount['aed']['amount'];
                $user = User::find($user_id);
                $new_bal = $user->available_balance - $amount;
                $user->update([
                    'available_balance' => $new_bal
                ]);

                $details = new \stdClass();

                $details->subtotal = number_format(getUsdPrice($withdraw->getOriginal('amount')['aed']['amount']), 2, '.', '');
                $this->subTotal = number_format(getUsdPrice($withdraw->getOriginal('amount')['aed']['amount']), 2, '.', '');
                $return = $this->doExpressCheckout(route('admin.dashboard.withdraws.paypal-payment-processed', ['withdraws' => $withdraw->id]), route('admin.dashboard.withdraws.paypal-payment-canceled'));

                DB::commit();
                return $return;
            } else {
                return $withdraw;
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function paypalPaymentProcessed($id)
    {
        $payerId = request()->get('PayerID');
        $token = request()->get('token');
        $withdraw = $this->getModel()->with('user')->whereHas('user')->find($id);
        if (empty($withdraw)) {
            return $withdraw;
        }
        if ($withdraw->status != "pending") {
            return $withdraw;
        }
        if (strlen($withdraw->user->client_id) <= 0 || strlen($withdraw->user->secret_id) <= 0) {
            return $withdraw;
        }
        $this->initPayPal($withdraw->user->client_id, $withdraw->user->secret_id);
        // Get the payment ID before session clear
        $paymentId = session()->get('paypal_payment_id');
        // clear the session payment ID
        if (empty($payerId) || empty($token)) {
            session()->forget('paypal_payment_id');
            return [
                'url' => route('admin.dashboard.withdraws.index'),
                'status' => 'error',
                'msg' => __('Withdraw request not completed.')
            ];

        }
        $payment = Payment::get($paymentId, $this->apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
        //Execute the payment
        $result = $payment->execute($execution, $this->apiContext);
        if ($result->getState() == 'approved') {
            // payment made
            try {
                \DB::beginTransaction();


                $this->getModel()->where(['id' => $id])->update([
                    'status' => 'completed',
                ]);

                \DB::commit();
                return [
                    'url' => route('admin.dashboard.withdraws.index'),
                    'status' => 'status',
                    'msg' => __('Withdraw request completed successfully.')
                ];

            } catch (\Exception $e) {
                \DB::rollBack();
                return [
                    'url' => route('admin.dashboard.withdraws.index'),
                    'status' => 'error',
                    'msg' => __('Something went wrong, please try later.')
                ];

            }
        } else {
            session()->forget('paypal_payment_id');

            return [
                'url' => route('admin.dashboard.withdraws.index'),
                'status' => 'error',
                'msg' => __('Payment failed! Request could not be completed.')
            ];

        }
    }

    public function paypalPaymentCanceled()
    {
        session()->forget('paypal_payment_id');
        return route('admin.dashboard.withdraws.index');
    }

    public function payWithCash($id)
    {
        try {
            \DB::beginTransaction();
            $this->getModel()->where(['id' => $id])->update([
                'status' => 'completed',
            ]);
            \DB::commit();
            return [
                'url' => route('admin.dashboard.withdraws.index'),
                'status' => 'status',
                'msg' => __('Withdraw request completed successfully.')
            ];

        } catch (\Exception $e) {
            \DB::rollBack();
            return [
                'url' => route('admin.dashboard.withdraws.index'),
                'status' => 'status',
                'msg' => __('Something went wrong, please try later.')
            ];

        }
    }

}
