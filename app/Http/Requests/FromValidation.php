<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;


class FromValidation extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $routeAlias = $this->route()->action['as'];
        $rules = [];
        if (str_contains($routeAlias, 'admin.dashboard.categories.update')) {
            $rules = [
                'name' => 'required|array',
                'name.en' => 'required|string',
                'name.ar' => 'required|string',
                'image' => 'required',
            ];
        }
        if (str_contains($routeAlias, 'admin.dashboard.categories.sub-categories.update')) {
            $rules = [
                'name' => 'required|array',
                'name.en' => 'required|string',
                'name.ar' => 'required|string',
                'image' => 'required',
            ];
        }

        if (str_contains($routeAlias, 'admin.dashboard.sub-categories.update')) {
            $rules = [
                'name' => 'required|array',
                'name.en' => 'required|string',
                'name.ar' => 'required|string',
                'image' => 'required',
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return trans('validation')['attributes'];
    }

}
