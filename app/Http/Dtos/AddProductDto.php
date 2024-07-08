<?php

namespace App\Http\Dtos;

use Illuminate\Support\Collection;
use Spatie\DataTransferObject\DataTransferObject;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;


class AddProductDto extends DataTransferObject
{
    public ?int $id = 0;
    public array $name = ['en' => '', 'ar' => ''];
    public array $description = ['en' => '', 'ar' => ''];
    public int $category_id = 0;
    public int $subcategory_id = 0;
    public ?int $featured_package_id = 0;
    public float $price;
    public ?float $offer_percentage = 0;
    public int $offer_expiry_date;
    public int $quantity = 0;
    public ?array $product_images = null;
    public ?array $attribute = [];
    public bool $is_featured = false;
    public ?bool $allowOffer = false;

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
            'id' => $params?->input('id'),
            'name' => [
                'en' => $params->filled('name.en') ? $params->input('name.en') : '',
                'ar' => $params->filled('name.ar') ? $params->input('name.ar', '') : ''
            ],
            'description' => [
                'en' => $params->filled('description.en') ? $params->input('description.en') : '',
                'ar' => $params->filled('description.ar') ? $params->input('description.ar', '') : ''
            ],
            'category_id' => $params->input('category_id'),
            'subcategory_id' => $params->input('subcategory_id'),
            'offer_percentage' => $params?->input('offer_percentage'),
            'offer_expiry_date' => DateToUnixformat($params->input('offer_expiry_date')),
            'allowOffer' => $params?->input('allowOffer'),
            'featured_package_id' => $params?->input('featured_package_id'),
            'price' => $params->filled('price') ? $params->input('price') : true,
            'attribute' => $params->input('attribute', []),
            'quantity' => $params->filled('quantity') ? $params->input('quantity') : 0,
            'product_images' => $params->filled('product_images') ? $params->input('product_images') : null,
        ]);

        return new self($self->toArray());
    }

}
