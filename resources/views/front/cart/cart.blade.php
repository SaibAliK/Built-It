@extends('front.layouts.app')
@section('content')
    @include('front.common.breadcrumb')
    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="cart-tittle-page-main-shop">{{__('Shopping Cart')}} <span
                                    class="gray-color">( {{count($cart['list'])}} {{__('Article')}})</span></h3>
                            <form action="{{route('front.dashboard.cart.update')}}" method="post" id="update_cart_form">
                                @csrf
                                @forelse($cart['list'] as $key => $list)
                                    <div class="cart-page-card-main">
                                        <div class="cros-icon-cart deleteCart" data-id="{{$list->id}}">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="wrapper-card-cart d-flex align-items-center ">
                                            <div class="image-cart d-flex align-items-center justify-content-center">
                                                @if( str_contains($list->images, 'video'))
                                                    <video width="120" height="120" controls>
                                                        <source src="{{$list->default_image}}"
                                                                type="video/mp4">
                                                    </video>
                                                @else
                                                    <img
                                                        src="{!! imageUrl($list->images,120,120,100,1) !!}"
                                                        class="img-fluid" alt="">
                                                @endif
                                            </div>

                                            <div class="right-side-of-cart-card w-100">
                                                <div class="wrapper-content">
                                                    <h2 class="tittle-cart">{{translate($list->product->name) ?? ''}}</h2>
                                                    <p class="meter-name-tittle">{{$list->quantity}} {{__('meter -')}}
                                                        <span
                                                            class="rpice-bold">{{getPrice($list->price->aed->amount, $currency)}}</span>
                                                    </p>
                                                    <h3 class="quantity-tittle">{{__('Supplier:')}} <span
                                                            class="span-tittle">{{translate($list->store->supplier_name) ?? ''}}</span>
                                                    </h3>
                                                    <div class="price-quantitys-cart d-flex justify-content-between">
                                                        <div
                                                            class="counter-cartbtn d-flex align-items-center justify-content-between  ">
                                                            <div class="counter-quantity d-flex">
                                                                <button type="button" class="p-m minus">-</button>
                                                                <input class="Number cart-input text-center"
                                                                       name="card_item[{{$key}}][quantity]"
                                                                       id="cart_number" type="text" readonly
                                                                       value="{{$list->quantity}}">
                                                                <input type="hidden" name="card_item[{{$key}}][card_id]"
                                                                       value="{{$list->id}}">
                                                                <button type="button" class="p-m"><span
                                                                        class="plus-top-icons plus">+</span>
                                                                </button>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                @empty
                                    @include('front.common.alert-empty', ['message' => __('No Cart found.')])
                                @endforelse
                            </form>
                        </div>
                    </div>
                </div>

                @if(count($cart['list']) > 0)
                    <div class="col-lg-4 col-xl-4">
                        <div class="right-sidee-cart-main">
                            <h3 class="tittle-2 ">{{__('Order Summary')}}</h3>
                            <div class="order-summ-ineerr-cart d-flex align-items-center justify-content-between ">
                                <h2 class="price-dt-sss">{{__('Items Price')}}</h2>
                                <h2 class="price-dt-ssss">{{getPrice($cart['price_object']->subtotal, $currency)}}</h2>
                            </div>


                            @if ($cart['price_object']->discount->aed->amount > 0)
                                <div class="order-summ-ineerr-cart d-flex align-items-center justify-content-between">
                                    <h2 class="price-dt-sss">{{__('Discount')}}</h2>
                                    <h2 class="price-dt-ssss">
                                        -{{getPrice( $cart['price_object']->discount,$currency)}}</h2>
                                </div>
                            @endif

                            <div class="order-summ-ineerr-cart d-flex align-items-center justify-content-between ">
                                <h2 class="price-dt-sss">{{__('Delivery Price')}}</h2>
                                <h2 class="price-dt-ssss">{{getPrice($cart['price_object']->shipping, $currency)}}</h2>
                            </div>
                            <div class="order-summ-ineerr-cart d-flex align-items-center justify-content-between ">
                                <h2 class="price-dt-sss">{{__('VAT')}}</h2>
                                <h2 class="price-dt-ssss">{{getPrice($cart['price_object']->vat, $currency)}}</h2>
                            </div>
                            <div
                                class="order-summ-ineerr-cart-total d-flex align-items-center justify-content-between ">
                                <h2 class="price-dt-sss">{{__('Total')}}</h2>
                                <h2 class="price-dt-ssss">{{getPrice($cart['price_object']->total, $currency)}}</h2>
                            </div>
                        </div>
                        <div class="order-summ-ineerr-cart detail-page-bttnnn">

                            <div class="add-basket-button">
                                <a href="{{route('front.dashboard.checkout.index')}}" id="checkout_btn"
                                   class="btn btn-primary w-100">{{__('Continue To Checkout')}}</a>
                                <div class="d-none mt-1" id="update_cart">
                                    <a href="#" id="submit_update_cart"
                                       class="btn btn-info w-100">{{__('Update')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $(".deleteCart").on('click', function (e) {
                let id = $(this).attr('data-id');
                $(".deleteCart").attr('disabled', 'disabled');
                swal({
                        title: "{{__("Are you sure you want to delete this?")}}",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#1C4670",
                        confirmButtonText: "{{__( "Delete")}}",
                        cancelButtonText: "{{__("No")}}",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: window.Laravel.baseUrl + "dashboard/delete-cart/" + id,
                                success: function (data) {
                                    toastr.success('success', "{{__('Product removed from cart')}}")
                                    location.reload();
                                }
                            })
                        } else {
                            $(".deleteCart").attr('disabled', false);
                            swal.close()
                        }
                    });
            });

            $(document).on('click', '.minus', function () {
                $("#update_cart").removeClass('d-none');
                let value = $(this).closest('div').find('.text-center');
                let ConvertToInt = parseInt(value.val());
                if (ConvertToInt >= 2) {
                    $(this).closest('div').find('.text-center').val(ConvertToInt - 1);
                }
            });

            $(document).on('click', '.plus', function () {
                $("#update_cart").removeClass('d-none');
                let value = $(this).closest('div').find('.text-center');
                let ConvertToInt = parseInt(value.val());
                $(this).closest('div').find('.text-center').val(ConvertToInt + 1);
            });

            $(document).on('click', '#submit_update_cart', function (e) {
                e.preventDefault();
                $("#update_cart_form").submit();
            });
        });
    </script>
@endpush
