<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class AdminRequest extends FormRequest
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
        if ($routeName == 'admin.dashboard.update-profile'){
          $rules =  $this->validateProfileRequest();
        }
        if ($routeName == 'admin.dashboard.administrators.store'){
          $rules =  $this->validateProfileRequest();
        }
        if ($routeName == 'admin.dashboard.administrators.update'){
          $rules =  $this->validateProfileRequest();
        }
        return $rules;
    }

    public function validateProfileRequest(){
        return [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'nullable|string|confirmed',
            'imageUrl' => 'nullable|string',
        ];
    }

}
