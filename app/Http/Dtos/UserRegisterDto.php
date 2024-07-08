<?php

namespace App\Http\Dtos;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;


class UserRegisterDto extends DataTransferObject
{
    public int $id = 0;
    public string $user_type = 'user';
    public array $supplier_name = ['en' => '', 'ar' => ''];
    public ?string $user_name;
    public string $email;
    public string $phone;
    public ?string $password = null;
    public string $address;
    public float $latitude;
    public float $longitude;
    public int $city_id = 0;
    public string $company_id = "";
    public bool $is_active = true;
    public bool $is_verified = false;
    public bool $is_id_card_verified = false;
    public array $about = ['en' => '', 'ar' => ''];
    public ?string $image = null;
    public ?array $id_card_images = [];
    public ?int $package_id = 0;
    public ?string $google_id = null;
    public ?string $facebook_id = null;
    public ?string $fcm_token = null;


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
            'id' => $params->input('user_id') ?? 0,
            'user_type' => $params->input('user_type', 'user'),
            'user_name' => $params->input('user_name'),
            'email' => $params->input('email'),
            'phone' => $params->input('phone'),
            'password' => $params->filled('password') ? $params->input('password') : null,
            'image' => $params->filled('image') ? $params->input('image') : null,
            'is_active' => $params->filled('is_active') ? $params->input('is_active') : true,
            'is_verified' => $params->filled('is_verified') ? $params->input('is_verified') : false,
            'google_id' => $params->filled('google_id') ? $params->input('google_id') : null,
            'facebook_id' => $params->filled('facebook_id') ? $params->input('facebook_id') : null,
            'address' => $params->input('address'),
            'latitude' => $params->input('latitude'),
            'longitude' => $params->input('longitude'),
            'fcm_token' => $params->filled('fcm_token') ? $params->input('fcm_token') : null,
        ]);
        if ($params->input('user_type', 'user') == 'supplier') {
            $self = $self->merge([
                'supplier_name' => [
                    'en' => $params->filled('supplier_name.en') ? $params->input('supplier_name.en') : '',
                    'ar' => $params->filled('supplier_name.ar') ? $params->input('supplier_name.ar', '') : ''
                ],
                'city_id' => $params->input('city_id'),
                'about' => [
                    'en' => $params->filled('about.en') ? $params->input('about.en') : '',
                    'ar' => $params->filled('about.ar') ? $params->input('about.ar', '') : ''
                ],
                'id_card_images' => $params->filled('id_card_images') ? $params->input('id_card_images') : null,
                'package_id' => $params->input('package_id', 0),
                'is_id_card_verified' => $params->input('is_id_card_verified', false),
            ]);
        }
        if ($params->input('user_type', 'user') == 'company') {
            $self = $self->merge([
                'supplier_name' => [
                    'en' => $params->filled('supplier_name.en') ? $params->input('supplier_name.en') : '',
                    'ar' => $params->filled('supplier_name.ar') ? $params->input('supplier_name.ar', '') : ''
                ],
                'city_id' => $params->input('city_id'),
                'id_card_images' => $params->filled('id_card_images') ? $params->input('id_card_images') : null,
//                'package_id' => $params->input('package_id', 0),
                'is_id_card_verified' => $params->input('is_id_card_verified', false),
            ]);
        }


        if ($params->input('user_type', 'user') == 'rider') {

            $self = $self->merge([
                'supplier_name' => [
                    'en' => $params->filled('supplier_name.en') ? $params->input('supplier_name.en') : '',
                    'ar' => $params->filled('supplier_name.ar') ? $params->input('supplier_name.ar', '') : ''
                ],
                'city_id' => $params->input('city_id'),
                'id_card_images' => [
                    0 => $params->input('id_card_images.0'),
                    1 => $params->input('id_card_images.1'),
                ],
                'company_id' => $params->filled('company_id') ? $params->input('company_id') : null,
                'is_verified' => 1,
            ]);
        }


        if ($params->filled('google_id') || $params->filled('facebook_id')) {
            if ($params->input('user_id') == 0) {
                $self = $self->replace(['is_verified' => true]);
            }
        }
        return new self($self->toArray());
    }
}
