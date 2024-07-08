<?php

namespace App\Http\Dtos;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class AddToCartDto extends DataTransferObject
{
    public ?int $product_id = 0;
    public ?int $quantity = 1;
    public ?int $area_id = 0;
    public ?array $subAttribute_id = [];

    public function __construct($args)
    {
        parent::__construct($args);
    }

    public static function fromRequest(Request $params): self
    {
        $self = collect([
            'product_id' => $params->input('product_id', 0),
            'quantity' => $params->input('quantity', 1),
            'area_id' => $params->input('area_id', 0),
            'subAttribute_id' => $params->input('subAttribute_id', []),
        ]);
        return new self($self->toArray());
    }
}
