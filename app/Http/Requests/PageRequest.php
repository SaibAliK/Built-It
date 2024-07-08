<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
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
        if ($routeName == 'admin.dashboard.pages.update') {
            $rules = $this->validatePageUpdateRequest();
        }
        return $rules;
    }

    public function validatePageUpdateRequest()
    {
        $rules = collect([
            'name' => 'array|required',
            'name.en' => 'required',
            'name.ar' => 'required',
            'content' => 'required|array',
            'content.en' => 'required',
            'content.ar' => 'required',
            'page_id' => 'required',
//            'image' => 'sometimes',
        ]);

        return $rules->toArray();
    }


}
