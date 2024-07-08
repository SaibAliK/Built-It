<?php

namespace App\Http\Dtos;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;


class SubscriptionUpdateDto extends DataTransferObject
{

    public int $id = 0;
    public array $name = ['en'=>'', 'ar'=>''];
    public float $price = 0;
    public int $duration;
    public string $duration_type = 'days';
    public array $description = ['en'=>'', 'ar'=>''];
    public string $subscription_type = 'supplier';
    public int $is_free = 0;

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
            'id' => $params->input('package_id'),
            'name' => $params->input('name', ['en'=>'', 'ar'=>'']),
            'price' => $params->input('price', 0) ?? 0,
            'duration' => $params->input('duration', 0),
            'duration_type' => $params->input('duration_type', 'days'),
            'description' => $params->input('description', ['en'=>'', 'ar'=>'']),
            'subscription_type' => $params->input('subscription_type', 'supplier'),
            'is_free' => $params->input('is_free', 0),
        ]);

        return new self($self->toArray());
    }

}
