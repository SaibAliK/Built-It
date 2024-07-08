<?php

namespace App\Traits;

use Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Payment;
use PayPal\Api\ItemList;
use PayPal\Api\Refund;
use PayPal\Api\RefundRequest;
use PayPal\Api\Sale;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentDetail;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

trait MyTechnologyPayPal
{

    protected $apiContext, $payment, $itemList, $details, $subTotal = 0;

    public function initPayPal($clientID = null, $clientSecret = null)
    {
        $paypal = config('paypal');
        if (empty($clientID) && empty($clientSecret)) {
            // setup PayPal api context
            $this->apiContext = new ApiContext(new OAuthTokenCredential($paypal['client_id'], $paypal['client_secret']));
            $this->apiContext->setConfig($paypal['settings']);
        } else {

            $this->apiContext = new ApiContext(new OAuthTokenCredential($clientID, $clientSecret));
            $this->apiContext->setConfig($paypal['settings']);
        }
    }
    public function  doExpressCheckout($returnUrl, $cancelUrl)
    {
        $payer = new Payer();
        $payer->setPaymentMethod('paypal');
        $amount = new Amount();
        $amount
            ->setCurrency('USD')
            ->setTotal($this->subTotal)
            ->setDetails($this->details);
        $transaction = new Transaction();
        $transaction
            ->setAmount($amount)
            ->setItemList($this->itemList);
        $redirectUrls = new RedirectUrls();
        $redirectUrls
            ->setReturnUrl($returnUrl)
            ->setCancelUrl($cancelUrl);
        $payment = new Payment();
        $payment
            ->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions(array($transaction));

        DB::beginTransaction();
        try {
            $payment->create($this->apiContext);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            if (config('app.debug')) {
                echo "Exception: " . $ex->getMessage() . PHP_EOL;
                $errData = json_decode($ex->getData(), true);
                exit;
            } else {
                die('Some error occur, sorry for inconvenient');
            }
        }

        foreach ($payment->getLinks() as $link) {
            if ($link->getRel() == 'approval_url') {
                $redirectUrl = $link->getHref();
                logger('Return link =>', [$returnUrl]);
                break;
            }
        }
        // add payment ID to session
        if (isset($redirectUrl)) {
            logger('Return link =>', [$payment->getId()]);
            session()->put('paypal_payment_id', $payment->getId());
            // redirect to paypal;
            return $redirectUrl;
        }
    }
    public function verifyPayment($paymentId, $totalAmount)
    {
        $payment = new Payment();
        $this->payment = $payment->get($paymentId, $this->apiContext);
        if ($this->payment->getState() == 'approved') {
            $paymentData = $this->payment->toArray();
            // verify payment amount
            $amountPaid = (isset($paymentData['transactions'][0]['amount']['total'])) ? $paymentData['transactions'][0]['amount']['total'] : 0;
            // make sure it is paid within 10 minutes
            //            $now = Carbon::now();
            //            $paymentDate = Carbon::parse($paymentData['create_time']);
            //            $differenceInMinutes = $paymentDate->diffInMinutes($now);
            return ($amountPaid == $totalAmount);
        }
        return FALSE;
    }
    public function refundPayment($refundAmount, $transactionId)
    {

        $refund = new Refund();
        $amount = new Amount();
        $amount->setTotal($refundAmount)->setCurrency('USD');
        $refund->setAmount($amount);
        $refundRequest = new RefundRequest();
        $refundRequest->setAmount($amount);
        $sale = new Sale();
        $sale->setId($transactionId);
        try {
            $refundedSale = $sale->refundSale($refundRequest, $this->apiContext);
            return $refundedSale;
        } catch (\Exception $ex) {
            if (config('app.debug')) {
                return  'error';
            } else {
                die('Some error occur, sorry for inconvenient');
            }
        }
    }
}
