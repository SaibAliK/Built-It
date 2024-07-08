<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {

        $rules = [];

        if ($this->route()->getName() == 'api.product.save') {
            $rules = $this->checkSaveRequest();
        }

        if ($this->route()->getName() == 'front.dashboard.cart.update' || $this->route()->getName() == 'api.cart.update') {
            $rules = $this->checkUpdateRequest();
        }

        if ($this->route()->getName() == 'front.dashboard.cart.add') {
            $rules = [
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|min:1'
            ];
        }

        if ($this->route()->getName() == 'api.auth.cart.add') {
            $rules = [
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|min:1',
                'area_id' => 'required'
            ];
        }
        return $rules;
    }

    public function checkSaveRequest()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required',
            'selected_attributes' => 'required',
        ];
    }

    public function checkUpdateRequest()
    {
        return [
            'card_item.*.card_id' => 'required',
            'card_item.*.quantity' => 'required|min:1',
        ];
    }

    public function attributes()
    {
        return trans('validation')['attributes'];
    }


    public function messages()
    {
        return [];
    }
}
