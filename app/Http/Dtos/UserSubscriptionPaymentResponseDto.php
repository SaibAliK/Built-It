<?php

namespace App\Http\Dtos;

use App\Models\SubscriptionPackage;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;


class UserSubscriptionPaymentResponseDto extends DataTransferObject
{

    public int $package_id = 0;
    public ?string $paymentId;
    public ?string $token;
    public ?string $PayerID;


    public function __construct($args)
    {
        parent::__construct($args);
    }

    public static function fromRequest(Request $params): self
    {
        $self = collect([
            "package_id" => $params->get('package_id', 0),
            "paymentId" => $params->get('paymentId', ''),
            "token" => $params->get('token', ''),
            "PayerID" => $params->get('PayerID', '')
        ]);

        return new self($self->toArray());
    }

}
