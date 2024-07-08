<?php

namespace App\Http\Dtos;

use App\Models\SubscriptionPackage;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;


class UserFeaturedSubscriptionSaveDto extends DataTransferObject
{

    public int $id = 0;
    public int $user_id;
//    public int $product_id;
    public SubscriptionPackage $package;
    public bool $is_expired = true;
    public ?string $payment_status = null;
    public ?string $payer_id = null;
    public ?string $first_name = null;
    public ?string $last_name = null;
    public ?string $payment_id = null;
    public ?string $payer_email = null;
    public ?string $payer_status = null;
    public string $payment_method = 'paypal';
    public ?array $paypal_response = null;
    public float $aed_price;
    public string $currency = 'USD';

    public function __construct($args)
    {
        parent::__construct($args);
    }

    public static function fromCollection(Collection $params): self
    {
        return new self([
            'id' => $params->get('user_subscription_package_id', 0),
            'user_id' => $params->get('user_id'),
//            'product_id' => $params->get('product_id'),
            'package' => $params->get('package'),
            'is_expired' => $params->get('is_expired', true),
            'payment_status' => $params->get('payment_status'),
            'payer_id' => $params->get('payer_id'),
            'first_name' => $params->get('first_name'),
            'last_name' => $params->get('last_name'),
            'payment_id' => $params->get('payment_id'),
            'payer_email' => $params->get('payer_email'),
            'payer_status' => $params->get('payer_status'),
            'payment_method' => $params->get('payment_method', 'paypal'),
            'paypal_response' => $params->get('paypal_response'),
            'aed_price' => $params->get('aed_price'),
            'currency' => $params->get('currency'),
        ]);
    }

}
