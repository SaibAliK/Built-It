<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $routeName = $this->route()->getName();
        $rules = [];

        if ($routeName == 'api.auth.product.save' || $routeName == 'front.dashboard.product.save') {
            $rules = $this->SaveProductRequest();
        }

        if ($this->route()->getName() == 'api.auth.product.set-favourite' || $routeName == 'front.auth.product.add.favorite') {
            $rules = $this->checkSetFavourite();
        }

        return $rules;
    }

    public function SaveProductRequest()
    {
        $rules = [
            'name' => 'required|array',
            'name.en' => 'required|string|max:100',
            'name.ar' => 'required|string|max:100',
            'description' => 'required|array',
            'description.en' => 'required',
            'description.ar' => 'required',
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'quantity' => 'required|integer|min:1|max:9999',
            'price' => 'required|min:1|max:999999',
            'product_images' => 'required|array',
        ];

        if ($this->allowOffer == true) {
            $rules = array_merge($rules, [
                'offer_percentage' => 'sometimes|integer|min:0|max:99',
                'offer_expiry_date' => 'required'
            ]);
        }

        return $rules;
    }

    public function checkSetFavourite()
    {
        return $rules = [
            'product_id' => 'required|exists:products,id',
        ];
    }

    public function attributes()
    {
        return trans('validation')['attributes'];
    }


    public function messages()
    {
        return [
            'time_slots.array' => __('The Availability Slots is required'),
        ];
    }

}
