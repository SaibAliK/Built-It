<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;


class ImageRequest extends FormRequest

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
        $rule = $this->validateSingleImage();
        if ($routeName == 'api.multi.image.uploader') {
            $rule = $this->validateMultipleImages();
        }

//        if ($routeName == 'api.product.upload-image') {
//            $rule = $this->validateSingleImage();
//        }

        return $rule;

    }

    public function validateMultipleImages()
    {
        return [
            'images' => 'array',
            'images.*' => 'mimes:jpeg,jpg,png,mov,quicktime,mp4|max:60000'
        ];
    }

    public function validateSingleImage()
    {
        return [
            'image' => 'mimes:jpeg,jpg,png'
        ];

    }

    public function attributes()
    {
        return trans('validation')['attributes'];
    }

    public function messages()
    {
        return [
            'image' => __('The Image Must be of jpeg,jpg,png'),
            'image.*' => __('The Image Must be of jpeg,jpg,png'),
            'image.mimes' => __('The Image Must be of jpeg,jpg,png'),
        ];
    }

}

