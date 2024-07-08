
@forelse($order->orderItems as $key=> $orderItem)
<div class="custom-product-card-dt d-flex align-items-center">
    <div class="image-of-card-1">
      <img src="{{imageUrl($orderItem->product->default_image,149, 104, 95, 1)}}" class="img-fluid" alt="image">

    </div>
    <div class="right-side-content-img-p w-100">
      <div class="tittle-quantity-parent">
        <h2 class="tittle-name text-truncate">{{translate((array)$orderItem->name) ?? ''}}</h2>
        <h3 class="quantity-tittle">Qty: {!! $orderItem->quantity !!}</h3>

      </div>
      <div class="one-meter-tittle-name">
        <h2 class="tittle-h2">{!! $orderItem->quantity !!} meter - <span class="color-gre">{!! getPrice($orderItem->price,$currency)!!}</span></h2>

      </div>

    </div>

  </div>
  @empty
@endforelse
