<?php


namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository\Repository;
use App\Models\OrderDetailItems;


class OrderDetailItemRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new OrderDetailItems());
    }

    public function all()
    {
        return $this->getModel()->all();
    }

    public function getQuery()
    {
        return $this->getModel()->query();
    }

    public function save($data = null, $orderDetailID = null, $order = null, $total_discount = null, $discounted_total = null)
    {
        return $this->getQuery()->create([
            'order_detail_id' => $orderDetailID->id,
            'order_id' => $order->id,
            'store_id' => $orderDetailID->store_id,
            'product_id' => $data->product_id,
            'name' => $data->product->name,
            'price' => $data->price,
            'quantity' => $data->quantity,
            'image' => $data->product->images->first()->image ?? '',
            'subtotal' => $data->subtotal,
            'shipping' => $data->shipping,
            'discount' => $total_discount,
            'extras' => $data->extras,
            'total' => $discounted_total,
        ]);
    }
}
