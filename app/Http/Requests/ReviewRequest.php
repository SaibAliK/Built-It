<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
        return [
//            'review' => 'required',
            'rating' => 'required',
            'store_id' => 'required',
            'user_id' => 'required',
            'order_detail_id' => 'required_without:order_detail_items_id',
            'product_id' => 'required_without:order_detail_id',
            'order_detail_items_id' => 'required_without:order_detail_id',
        ];
    }

    public function attributes()
    {
        return trans('validation')['attributes'];
    }
}
