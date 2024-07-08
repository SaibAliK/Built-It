<?php

namespace App\Http\Dtos;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;


class UserProfileUpdateDto extends DataTransferObject
{

    public int $id = 0;
    public string $user_type = 'user';
    public array $supplier_name = ['en'=>'', 'ar'=>''];
    public ?string $user_name;
    public string $phone;
    public ?string $address = null;
    public ?string $password= null;
    public float $latitude = 0.0;
    public float $longitude = 0.0;
    public int $city_id = 0;
    public array $about = ['en'=>'', 'ar'=>''];
    public ?string $image = null;
    public ?array $id_card_images = [];

    public function __construct($args)
    {
        parent::__construct($args);
    }


    /**
     * @throws UnknownProperties
     */
    public static function fromRequest(Request $params): self
    {
        $self = collect([
            'id' => $params->input('user_id'),
            'user_type' => $params->input('user_type', 'user'),
            'user_name' => $params->input('user_name'),
            'phone' => $params->input('phone'),
            'image' => $params->filled('image')? $params->input('image') : null,
            'password' => $params->filled('password')? $params->input('password') : null,
            'address' => $params->input('address'),
            'latitude' => $params->input('latitude'),
            'longitude' => $params->input('longitude'),
        ]);
        if ($params->input('user_type', 'user') == 'supplier'){
            $self = $self->merge([
                'supplier_name' => [
                    'en'=>$params->filled('supplier_name.en')?$params->input('supplier_name.en'): '',
                    'ar'=>$params->filled('supplier_name.ar')?$params->input('supplier_name.ar', ''): ''
                ],

                'city_id' => $params->input('city_id'),
                'about' => [
                    'en'=>$params->filled('about.en')?$params->input('about.en'): '',
                    'ar'=>$params->filled('about.ar')?$params->input('about.ar', ''): ''
                ],
                'id_card_images' => $params->filled('id_card_images') ? $params->input('id_card_images') : null,
            ]);
        }
        if($params->input('user_type', 'user') == 'company')
        {
            $self = $self->merge([
                'supplier_name' => [
                    'en'=>$params->filled('supplier_name.en')?$params->input('supplier_name.en'): '',
                    'ar'=>$params->filled('supplier_name.ar')?$params->input('supplier_name.ar', ''): ''
                ],
                'about' => [
                    'en'=>$params->filled('about.en')?$params->input('about.en'): '',
                    'ar'=>$params->filled('about.ar')?$params->input('about.ar', ''): ''
                ],
                'city_id' => $params->input('city_id'),
                'id_card_images' => $params->filled('id_card_images') ? $params->input('id_card_images') : null,
                'package_id' => $params->input('package_id',0),
                'is_id_card_verified' => $params->input('is_id_card_verified',false),
            ]);
        }

        return new self($self->toArray());
    }

}
