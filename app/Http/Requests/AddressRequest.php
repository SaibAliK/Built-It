<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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


    public function rules()
    {
        $rules = [];
        if ($this->route()->getName() == 'front.dashboard.address.store' || $this->route()->getName() == 'api.address.store') {
            $rules = $this->addNewAddress();
        }

        return $rules;
    }


    public function attributes()
    {
        return trans('validation')['attributes'];
    }

    private function addNewAddress()
    {
        return [
            'address' => 'required',
            'name' => 'required',
            'user_phone' => 'required|min:9',
            'address_description' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'city_id' => 'required',
            'area_id' => 'required',
        ];
    }

    public function messages()
    {
        // use trans instead on Lang
        return [
            'address.required' => __('please search and add address.'),
            'address_name.required' => __('Please add Address name.'),
            'building.required' => __('Please add building no.'),
            'address_description.required' => __('Please add address description.'),
//            'user_phone.required'=>__('Please enter mobile number.'),
//            'type.required'=>__('please select one address type.'),
            'latitude' => 'required',
            'longitude' => 'required',
        ];
    }
}
