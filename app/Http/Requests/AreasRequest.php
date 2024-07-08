<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AreasRequest extends FormRequest
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
        if ($this->route()->getName() == 'front.dashboard.areas.store' || $this->route()->getName() == 'api.saveAreas') {
            if (request()->get('id') != "") {
                $rules = $this->updateAddress();
            } else {
                $rules = $this->addNewAddress();
            }
        }

        return $rules;
    }

    private function addNewAddress()
    {
        return [
            'selected_area' => 'required',
//            'price' => 'required',
        ];
    }

    private function updateAddress()
    {
        return [
            'id' => 'required',
            'selected_area' => 'required',
//            'price' => 'required',
        ];
    }


    public function messages()
    {
        return [
            'selected_area.required' => __('please select delivery areas'),
            'price.required' => __('Please add price.'),
        ];
    }
}
