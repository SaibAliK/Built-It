<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserSubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     *
     * @return array
     */
    public function rules()
    {
        $routeName = $this->route()->getName();
        $rules = [];

        if ($routeName == 'front.dashboard.subscription.payment') {
            $rules = $this->validateSubscriptionPaymentRequest();
        }

        if ($routeName == 'api.auth.subscription.payment-response') {
            $rules = $this->validateApiSubscriptionPaymentRequest();
        }
        return $rules;
    }

    public function validateSubscriptionPaymentRequest()
    {
        $rules = collect([
            'package_id' => 'required|exists:subscription_packages,id',
//            'payment_method' => 'required|in:paypal',
        ]);

        return $rules->toArray();
    }
    public function validateApiSubscriptionPaymentRequest()
    {
        if($this->get('type') == 'commission')
        {
            $rules = collect([
                'type' => 'required',
            ]);
        }
        else{

            $rules = collect([
                'package_id' => 'required|exists:subscription_packages,id',
                'is_free' => 'required',
            ]);
            if ($this->get('is_free', 0) !== 1){
                $rules = $rules->merge([
//                    "paymentId" => 'required',
//                    "token" => 'required',
//                    "PayerID" => 'required'
                ]);
            }
        }

        return $rules->toArray();
    }


}
