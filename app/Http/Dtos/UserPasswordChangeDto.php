<?php

namespace App\Http\Dtos;

use App\Models\SubscriptionPackage;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;


class UserPasswordChangeDto extends DataTransferObject
{

    public string $current_password;
    public string $password;

    public function __construct($args)
    {
        parent::__construct($args);
    }

    public static function fromRequest(Request $params): self
    {
        return new self([
            'current_password' => $params->get('current_password'),
            'password' => $params->get('password'),
        ]);
    }

}
