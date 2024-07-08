<?php


namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository\Repository;
use App\Models\OrderDetail;


class OrderDetailRepository extends Repository
{


    public function __construct()
    {
        $this->setModel(new OrderDetail());
    }

    public function all()
    {
        return $this->getModel()->all();
    }

    public function getQuery()
    {
        return $this->getModel()->query();
    }

    public function save($data = null, $order = null, $count = null)
    {
        $subTotalWithDiscount = 0.00;
        $forVatCalculation = $data->subtotal;
        if ($order->discount_percentage > 0) {
            $discount_amount = $data->subtotal * ($order->discount_percentage / 100);
            $subTotalWithDiscount = $data->subtotal - $discount_amount;
            $forVatCalculation = $subTotalWithDiscount;
        }
        $orderDetail = $this->getQuery()->create([
            'order_id' => $order->id,
            'order_no' => $order->order_number,
            'store_id' => $data->product->user_id,
            'user_id' => $order->user_id,
            'product_id' => $data->product_id,
            'vat_percentage' => config('settings.value_added_tax'),
            'status' => "pending",
            'image' => $data->user->image ?? '',
            'shipping' => $order->shipping ?? '',
            'subtotal' => $data->subtotal_sum,
            'discount' => $subTotalWithDiscount,
            'vat' => $order->vat,
            'total' => $order->total,
        ]);

        $user_name = $order->user->user_name;

        sendNotification([
            'sender_id' => isset($order->user_id) ? $order->user_id : $data->product->user_id,
            'receiver_id' => $data->product->user_id,
            'extras->order_id' => $orderDetail->id,
            'extras->display_name' => $user_name,
            'extras->order_no' => $orderDetail->order_no,
            'title->en' => 'Order Received',
            'title->ar' => 'طلب وارد',
            'description->en' => 'You have received a new order',
            'description->ar' => 'لقد تلقيت طلبًا جديدًا',
            'action' => 'ORDER'
        ]);

        return $orderDetail;
    }

    public function update($id, $path)
    {
        return $this->getModel()->where('id', '=', $id)->update(['invoice' => $path]);
    }
}
