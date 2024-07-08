<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        /*Admin Routes*/
        if ($routeName == 'admin.dashboard.users.update') {
            $rules = $this->validateUserUpdateRequest();
        }
        if ($routeName == 'admin.dashboard.suppliers.update') {
            $rules = $this->validateUserUpdateRequest();
        }

        if ($routeName == 'admin.dashboard.riders.update') {
            $rules = $this->validateUserUpdateRequest();
        }

        /*Api Routes*/
        if ($routeName == 'api.auth.register') {
            $rules = $this->validateRegisterRequest();
        }
        if ($routeName == 'api.auth.verify-email') {
            $rules = $this->validateEmailVerificationRequest();
        }
        if ($routeName == 'api.auth.forgot-password') {
            $rules = $this->validateForgotPasswordRequest();
        }
        if ($routeName == 'api.auth.reset-password') {
            $rules = $this->validateForgotPasswordRequest();
        }
        if ($routeName == 'api.auth.change-password') {
            $rules = $this->validateUpdatePasswordRequest();
        }
        if ($routeName == 'api.auth.update.profile') {
            $rules = $this->validateProfileUpdateRequest();
        }

        /*Front routes*/
        if ($routeName == 'front.auth.register.submit') {
            $rules = $this->validateRegisterRequest();
        }
        if ($routeName == 'front.auth.verification.submit') {
            $rules = $this->validateEmailVerificationRequest();
        }
        if ($routeName == 'front.auth.forgot-password.submit') {
            $rules = $this->validateForgotPasswordRequest();
        }
        if ($routeName == 'front.dashboard.update.profile') {
            $rules = $this->validateProfileUpdateRequest();
        }
        if ($routeName == 'front.dashboard.update.password') {
            $rules = $this->validateUpdatePasswordRequest();
        }

        return $rules;
    }

    public function validateRegisterRequest()
    {
        $rules = collect([
            'user_type' => 'required|in:user,supplier,company',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'phone' => 'required|regex:/^(?:\+)[0-9]/',
            'password' => 'required|confirmed',
            'terms_conditions' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);
        if ($this->get('user_type', 'user') == 'user') {
            $rules = $rules->merge([
                'user_name' => 'required',
            ]);
        }

        if ($this->get('user_type', 'user') == 'supplier') {
            $rules = $rules->merge([
                'city_id' => 'required|exists:cities,id',
                'supplier_name' => 'required|array',
                'supplier_name.en' => 'required',
                'id_card_images' => 'required|array',
                'id_card_images.0' => 'required',
                'id_card_images.1' => 'required',
                'image' => 'required',
                'about' => 'required|array',
                'about.en' => 'required',
            ]);
        }
        if ($this->get('user_type', 'user') == 'company') {
            $rules = $rules->merge([
                'city_id' => 'required|exists:cities,id',
                'supplier_name' => 'required|array',
                'supplier_name.en' => 'required',
                'id_card_images' => 'required|array',
                'id_card_images.0' => 'required',
                'id_card_images.1' => 'required',
                'image' => 'required',
            ]);
        }
        return $rules->toArray();
    }

    public function validateUserUpdateRequest()
    {
        $rules = collect([
            'user_id' => 'required',
            'user_type' => 'required|in:user,supplier,company,rider',
            'email' => 'required|email',
            'phone' => 'required|regex:/^(?:\+)[0-9]/',
            'terms_conditions' => 'required',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);
        if ($this->get('user_id') == 0) {
            $rules = $rules->merge([
                'password' => 'required|confirmed',
            ]);
        }
        if ($this->get('user_type', 'user') == 'supplier') {
            $rules = $rules->merge([
                'city_id' => 'required|exists:cities,id',
                'supplier_name' => 'required|array',
                'supplier_name.en' => 'required',
                'supplier_name.ar' => 'required',

            ]);
            if ($this->get('user_id') == 0) {
                $rules = $rules->merge([
                    'image' => 'required',
                    'id_card_images' => 'required',
                ]);
            }
        }

        if ($this->get('user_type', 'user') == 'rider') {
            $rules = $rules->merge([
                'address' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
                'company_id' => 'required',
                'city_id' => 'required|exists:cities,id',
                'supplier_name' => 'required|array',
                'supplier_name.en' => 'required'
            ]);
        }

        return $rules->toArray();
    }

    public function validateEmailVerificationRequest()
    {
        $rules = collect([
            'verification_code' => 'required',
        ]);

        return $rules->toArray();
    }

    public function validateForgotPasswordRequest()
    {
        $rules = collect([
            'email' => 'required',
        ]);

        return $rules->toArray();
    }

    public function validateProfileUpdateRequest()
    {
        $rules = collect([
            'user_type' => 'required|in:user,supplier,company',
            'email' => 'required|email',
            'phone' => 'required|regex:/^(?:\+)[0-9]/',
            'password' => 'nullable|confirmed',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);
        if ($this->get('user_type', 'supplier') == 'user') {
            $rules = $rules->merge([
                'user_name' => 'required',
            ]);
        }
        if ($this->get('user_type', 'user') == 'supplier' || $this->get('user_type', 'user') == 'company') {

            $rules = $rules->merge([
                'city_id' => 'required|exists:cities,id',
                'supplier_name' => 'required|array',
                'supplier_name.en' => 'required',
                'id_card_images' => 'required|array',
                'id_card_images.0' => 'required',
                'id_card_images.1' => 'required',
                'image' => 'required',
            ]);
        }
        // dd($rules->toArray());
        return $rules->toArray();
    }

    public function validateUpdatePasswordRequest()
    {
        $rules = collect([
            'current_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        return $rules->toArray();
    }

    public function attributes()
    {
        return trans('validation')['attributes'];
    }

    public function messages()
    {
        return [
            'id_card_images.1.required' => __('Trade Lisence Front and Back Images(2) are required'),
            'id_card_images.0.required' => __('Trade Lisence Images are required'),
        ];
    }

}
