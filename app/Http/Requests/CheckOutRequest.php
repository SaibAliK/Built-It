<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckOutRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];
        if ($this->route()->getName() == 'api.checkout.buy-now' && $this->request->get('payment_method') == 'paypal') {

            $rules = [
                'paymentID' => 'required',
                'payerID' => 'required',
                'selected_address' => 'required|exists:addresses,id',
                'payment_method' => 'required',
            ];
        }

        if ($this->route()->getName() == 'api.checkout.buy-now' && $this->request->get('payment_method') == 'cash_on_delivery') {

            $rules = [
                'selected_address' => 'required|exists:addresses,id',
                'payment_method' => 'required',
            ];
        }

        if ($this->route()->getName() == 'front.dashboard.checkout.buy-now' || $this->route()->getName() == 'api.checkout.buy-now') {
            $rules = $this->checkOut();
        }
        return $rules;
    }

    private function checkOut()
    {
        return [
            'selected_address' => 'required|exists:addresses,id',
            'payment_method' => 'required',
        ];
    }

    public function attributes()
    {
        return trans('validation')['attributes'];
    }


    public function messages()
    {
        return [
            'selected_address.required' => __('Please select Or add at least one Address for checkout.'),
            'payerID.required' => __('Payer Id is required for checkout.'),
            'paymentID.required' => __('Payment Id is required for checkout.'),
            'payment_method.required' => __('please select one payment method.'),
        ];
    }
}
