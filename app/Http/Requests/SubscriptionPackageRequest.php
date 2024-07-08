<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionPackageRequest extends FormRequest
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
        if ($routeName == 'admin.dashboard.subscriptions.update') {
            $rules = $this->validateSubscriptionPackageUpdateRequest();
        }
        return $rules;
    }

    public function validateSubscriptionPackageUpdateRequest()
    {

        $rules = collect([
            'name' => 'array|required',
            'name.en' => 'required',
            'name.ar' => 'required',
            'duration' => 'required',
            'duration_type' => 'required',
            'description' => 'required|array',
            'description.en' => 'required',
            'description.ar' => 'required',
            'package_id' => 'required',
            'is_free' => 'required',
        ]);

        if ($this->get('is_free') !== "1") {
            $rules = $rules->merge([
                'price' => 'required',
            ]);
        }
        return $rules->toArray();

    }


}
