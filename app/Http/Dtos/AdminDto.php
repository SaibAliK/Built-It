<?php

namespace App\Http\Dtos;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;


class AdminDto extends DataTransferObject
{

    public int $id = 0;

    public string $name;

    public string $email;

    public ?string $password = null;

    public ?string $imageUrl = null;


    public function __construct($args)
    {
        parent::__construct($args);
    }


    /**
     * @throws UnknownProperties
     */
    public static function fromRequest(Request $params): self
    {
        return new self([
            'id' => $params->input('id'),
            'name' => $params->input('name'),
            'email' => $params->input('email'),
            'password' => $params->filled('password')? $params->input('password') : null,
            'imageUrl' => $params->filled('imageUrl')?$params->input('imageUrl') : null,
        ]);
    }

    public static function fromCollection(Collection $params): self
    {
        return new self([
            'id' => $params->get('id'),
            'name' => $params->get('name'),
            'email' => $params->get('email'),
            'password' => !is_null($params->get('password')) || !empty($params->get('password'))? $params->get('password') : null,
            'imageUrl' =>!is_null($params->get('imageUrl')) || !empty($params->get('imageUrl'))? $params->get('imageUrl') : null
        ]);
    }

}
