<?php

namespace App\Http\Dtos;

use App\Models\SubscriptionPackage;
use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;


class UserSubscriptionPaymentDto extends DataTransferObject
{

    public int $package_id;
    public string $name;
    public float $usdPrice = 0;
    public float $usdVat = 0;


    public function __construct($args)
    {
        parent::__construct($args);
    }

    public static function fromCollection(Collection $params): self
    {
        return new self([
            'package_id' => $params->get('package_id'),
            'name' => $params->get('name'),
            'usdPrice' =>getUsdPrice($params->get('price')),
            'usdVat' =>getUsdPrice($params->get('price') * config('settings.value_added_tax') / 100),
        ]);
    }

}
