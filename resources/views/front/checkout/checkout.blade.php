@extends('front.layouts.app')
@section('content')
    @include('front.common.breadcrumb')

    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-8 col-xl-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="seven-check-out-pagee">
                                <div class="check-out-box-shadow-div">
                                    <div
                                        class="custom-heading-check-out d-flex align-items-center justify-content-between">
                                        <h2 class="tittle">{{__('Shipping Address')}}</h2>
                                        <a href="" class="add-addres-chec" data-toggle="modal"
                                           data-target="#addressModalCenter-1"
                                           id="openAddressModal">{{__('Add Address')}}</a>
                                    </div>
                                    <div class="address-content-check-main">
                                        @if(count($address) == 0)
                                            <h3 class="when-no-add">{{__('No address added yet.')}}</h3>
                                        @endif
                                        @forelse($address as $item)
                                            <form id="address-form-{{ $item->id }}"
                                                  action="{{ route('front.dashboard.address.make-default') }}"
                                                  method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{$item->id}}">
                                                <div
                                                    class="remember-me check-out-pageee-radio-se multi-radio-addresss  ">
                                                    <label class="custom-radio">
                                                        <div class="check-out-page-address-inf">
                                                            <div class="main-block-shpping-image-content">
                                                                <div
                                                                    class="shipping-dt-img d-flex align-items-center justify-content-center">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16.5"
                                                                         height="22" viewBox="0 0 16.5 22">
                                                                        <path id="Path_48396" data-name="Path 48396"
                                                                              d="M7.391,2.32a.986.986,0,0,0,.859.43.986.986,0,0,0,.859-.43L11.988-1.8Q14.137-4.9,14.824-5.973a12.1,12.1,0,0,0,1.332-2.6A7.822,7.822,0,0,0,16.5-11a7.963,7.963,0,0,0-1.117-4.125,8.408,8.408,0,0,0-3.008-3.008A7.963,7.963,0,0,0,8.25-19.25a7.963,7.963,0,0,0-4.125,1.117,8.408,8.408,0,0,0-3.008,3.008A7.963,7.963,0,0,0,0-11,7.822,7.822,0,0,0,.344-8.572a12.1,12.1,0,0,0,1.332,2.6Q2.363-4.9,4.512-1.8,6.273.688,7.391,2.32ZM8.25-7.562a3.31,3.31,0,0,1-2.428-1.01A3.31,3.31,0,0,1,4.813-11a3.31,3.31,0,0,1,1.01-2.428,3.31,3.31,0,0,1,2.428-1.01,3.31,3.31,0,0,1,2.428,1.01A3.31,3.31,0,0,1,11.688-11a3.31,3.31,0,0,1-1.01,2.428A3.31,3.31,0,0,1,8.25-7.562Z"
                                                                              transform="translate(0 19.25)"
                                                                              fill="#45cea2"></path>
                                                                    </svg>
                                                                </div>
                                                                <div class="right-content-shipping">
                                                                    <h2 class="tittle-name-city">{{$item->address}}</h2>
                                                                </div>

                                                            </div>
                                                            <h2 class="oder-dt-cus-head-ad">{{__('Name:')}} <span
                                                                    class="span-time-head-2">  {{$item->name}}</span>
                                                            </h2>
                                                            <h2 class="oder-dt-cus-head-ad">{{__('Phone No:')}} <span
                                                                    class="span-time-head-2"
                                                                    dir="ltr"> {{$item->user_phone}}</span>
                                                            </h2>
                                                            <h2 class="oder-dt-cus-head-ad">{{__('City:')}} <span
                                                                    class="span-time-head-2">  {{translate($item->city->name)}}</span>
                                                            </h2>
                                                        </div>
                                                        @if ($item->default_address)
                                                            <input type="radio" id="{{ $item->id }}"
                                                                   class=""
                                                                   checked data-id="{{ $item->id }}"
                                                                   name="radio">
                                                            <span class="checkmark"></span>
                                                        @else
                                                            <input type="radio" id="{{ $item->id }}"
                                                                   class="default-address"
                                                                   data-id="{{ $item->id }}"
                                                                   name="radio">
                                                            <span class="checkmark"></span>
                                                        @endif
                                                    </label>
                                                </div>
                                            </form>
                                        @empty
                                        @endforelse

                                    </div>
                                </div>


                                <div class="check-out-box-shadow-div">
                                    <div class="custom-heading-check-out">
                                        <h2 class="tittle mb-16">{{__('Payment Method')}}</h2>
                                        <div class="remember-me check-out-pageee-radio-se  ">

                                            <label class="custom-radio">{{__('Credit Card')}}
                                                <input type="radio" value="cash_on_delivery" name="payment_method"
                                                       class="payment_method">
                                                <span class="checkmark"></span>
                                            </label>
                                            <label class="custom-radio">{{__('Cash on Delivery')}}
                                                <input type="radio" value="cash_on_delivery" checked="checked"
                                                       name="payment_method"
                                                       id="radio03-02" class="payment_method">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="check-out-box-shadow-div">
                                    <div class="custom-heading-check-out">
                                        <div
                                            class="order-ancher-parent-check mb-1 d-flex justify-content-between align-items-center">
                                            <h2 class="tittle ">{{__('Order Notes')}}</h2>
                                            <a href="#" class="ancher-add-note" data-toggle="modal"
                                               data-target="#exampleModalCenter-2">{{__('add')}}</a>
                                        </div>
                                        <p class="order-notes-des-check" id="html_show">{{__('Not added yet.')}}</p>
                                    </div>
                                </div>

                                <div class="check-out-box-shadow-div border-0 pb-0 mb-0">
                                    <div class="custom-heading-check-out">
                                        <h2 class="tittle mb-16">{{__('Product List')}}</h2>
                                        <div class="check-out-cart-page-main">
                                            @forelse($cart['list'] as $index=>$cartItem)
                                                <div class="cart-page-card-main">
                                                    <div class="wrapper-card-cart d-flex align-items-center ">
                                                        <div
                                                            class="image-cart d-flex align-items-center justify-content-center">
                                                            @if (!empty($cartItem->images))
                                                                @if (str_contains($cartItem->product->default_image, 'mp4'))
                                                                    <video width="122" height="120" controls muted>
                                                                        <source
                                                                            src="{{ $cartItem->product->default_image }}"
                                                                            class="img-fluid slider-img"
                                                                            type="video/mp4">
                                                                        Your browser does not support the video tag.
                                                                    </video>
                                                                @else
                                                                    <img
                                                                        src="{{ imageUrl($cartItem->product->default_image, 122, 120, 95, 1) }}"
                                                                        alt="" id="product-primary-img"
                                                                        class="img-fluid">
                                                                @endif
                                                            @endif

                                                        </div>
                                                        <div class="right-side-of-cart-card w-100">
                                                            <div class="wrapper-content">
                                                                <div
                                                                    class="cart-page-tittle-top-cehck- d-flex align-items-center justify-content-between">
                                                                    <h2 class="tittle-cart mb-0">{!! translate($cartItem->product->name) !!}</h2>
                                                                    <h3 class="tittle-quan-check">{{__('Qty:')}} {{ $cartItem->quantity ?? '' }}</h3>
                                                                </div>
                                                                <p class="meter-name-tittle">{{ $cartItem->quantity ?? '' }} {{__('meter -')}}
                                                                    <span
                                                                        class="rpice-bold">{{ getPrice($cartItem->price->aed->amount, $currency) }}</span>
                                                                </p>
                                                                <h3 class="quantity-tittle">{{__('Supplier:')}} <span
                                                                        class="span-tittle">{!! translate($cartItem->product->store->supplier_name) !!}</span>
                                                                </h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-xl-4">
                    <div class="right-sidee-cart-main">
                        <h3 class="tittle-2 ">{{__('Order Summary')}}</h3>
                        <div class="order-summ-ineerr-cart d-flex align-items-center justify-content-between ">
                            <h2 class="price-dt-sss">{{__('Items Price')}}</h2>
                            <h2 class="price-dt-ssss">{{ getPrice($cart['price_object']->subtotal, $currency) }}</h2>
                        </div>


                        @if($cart['price_object']->discount->aed->amount > 0)
                            <div class="order-summ-ineerr-cart d-flex align-items-center justify-content-between ">
                                <h2 class="price-dt-sss">{{__('Discount')}}</h2>
                                <h2 class="price-dt-ssss">
                                    -{{ getPrice($cart['price_object']->discount, $currency) }}</h2>
                            </div>
                        @endif

                        <div class="order-summ-ineerr-cart d-flex align-items-center justify-content-between ">
                            <h2 class="price-dt-sss">{{__('Delivery Price')}}</h2>
                            <h2 class="price-dt-ssss">{{ getPrice($cart['price_object']->shipping, $currency) }}</h2>
                        </div>
                        <div class="order-summ-ineerr-cart d-flex align-items-center justify-content-between ">
                            <h2 class="price-dt-sss">{{__('VAT')}}</h2>
                            <h2 class="price-dt-ssss">{{ getPrice($cart['price_object']->vat, $currency) }}</h2>
                        </div>
                        <div
                            class="order-summ-ineerr-cart-total d-flex align-items-center justify-content-between ">
                            <h2 class="price-dt-sss">{{__('Total')}}</h2>
                            <h2 class="price-dt-ssss">{{ getPrice($cart['price_object']->total, $currency) }}</h2>
                        </div>
                    </div>
                    <div class="order-summ-ineerr-cart detail-page-bttnnn">
                        <div class="add-basket-button">
                            <form id="check-out-form" action="{{ route('front.dashboard.checkout.buy-now') }}"
                                  method="post">
                                @csrf
                                <input type="hidden" name="selected_address" id="selected_address" value="">
                                <input type="hidden" id="payment_method" name="payment_method" value="cash_on_delivery">
                                <input type="hidden" id="paymentID" name="paymentID" value="">
                                <input type="hidden" id="payerID" name="payerID" value="">
                                <input type="hidden" id="orderID" name="orderID" value="">
                                <input type="hidden" id="paymentToken" name="paymentToken" value="">
                                <input type="hidden" id="returnUrl" name="returnUrl" value="">
                                <input type="hidden" name="make_checked" value="">
                                <input type="hidden" name="order_notes" id="order_notes" value="">
                                <input type="hidden" id="selected_total" name="selected_total"
                                       value="{{ json_encode($cart['price_object']->total) }}">
                                <input type="hidden" id="selected_total_in_usd" name="selected_total_in_usd"
                                       value="{{ json_encode($cart['price_object']->total->usd->amount) }}">
                                <input type="hidden" id="paypal_input" name="paypal_input" value="1">
                                <input type="hidden" name="order_type" value="product">
                                <div class="col-sm-12 btn-col" disabled id="paypal-button"></div>
                                <div class="custom-shadown-bt-all">
                                    <button class="btn btn-primary w-100 js-check-out-btn"
                                            type="submit">{{ __('Place Order') }}
                                        <span></span></button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade custom-modal-pa-all" id="addressModalCenter-1" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <form id="modalForm" action="" method="post">
                    @csrf
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{__('Add Address')}}</h5>
                    </div>
                    <div class="modal-body px-0">
                        <div class="inputs-border-wrapper mb-25">
                            <div class="input-style">
                                <div class="wrapper-input">
                        <span class="icon-front-input">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14.425" height="14.425"
                               viewBox="0 0 14.425 14.425">
                              <path id="Icon_awesome-user-alt" data-name="Icon awesome-user-alt"
                                    d="M7.212,8.114A4.057,4.057,0,1,0,3.155,4.057,4.058,4.058,0,0,0,7.212,8.114Zm3.606.9H9.266a4.9,4.9,0,0,1-4.108,0H3.606A3.606,3.606,0,0,0,0,12.622v.451a1.353,1.353,0,0,0,1.352,1.352h11.72a1.353,1.353,0,0,0,1.352-1.352v-.451A3.606,3.606,0,0,0,10.819,9.016Z"
                                    fill="#45cea2"/>
                            </svg>
                        </span>
                                    <input type="text" class="ctm-input" id="name" name="name"
                                           placeholder="{{__('Name*')}}">
                                </div>
                            </div>
                            <div class="input-style">
                                <div class="wrapper-input">
                        <span class="icon-front-input">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14.375" height="14.375"
                               viewBox="0 0 14.375 14.375">
                              <path id="Icon_awesome-phone-alt" data-name="Icon awesome-phone-alt"
                                    d="M13.965,10.158,10.82,8.811A.674.674,0,0,0,10.034,9l-1.393,1.7A10.407,10.407,0,0,1,3.667,5.731l1.7-1.393a.672.672,0,0,0,.194-.786L4.214.408a.678.678,0,0,0-.772-.39L.522.691A.674.674,0,0,0,0,1.348,13.026,13.026,0,0,0,13.028,14.375a.674.674,0,0,0,.657-.522l.674-2.92a.682.682,0,0,0-.393-.775Z"
                                    transform="translate(0 0)" fill="#45cea2"/>
                            </svg>


                        </span>
                                    <input type="tel" name="user_phone"
                                           id="user_phone" class="ctm-input" placeholder="{{__('Phone No*')}}">
                                </div>
                            </div>


                            <!-- select 2 -->
                            <div class="input-style phone-dropdown custom-drop-contact ">
                                <div class="custom-selct-icons-arow position-relative">
                              <span class="icon-front-input">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13.5" height="18" viewBox="0 0 13.5 18">
                              <path id="Path_48396" data-name="Path 48396"
                                    d="M-.7,1.9A.807.807,0,0,0,0,2.25.807.807,0,0,0,.7,1.9L3.059-1.477q1.758-2.531,2.32-3.41a9.9,9.9,0,0,0,1.09-2.127A6.4,6.4,0,0,0,6.75-9a6.515,6.515,0,0,0-.914-3.375,6.879,6.879,0,0,0-2.461-2.461A6.515,6.515,0,0,0,0-15.75a6.515,6.515,0,0,0-3.375.914,6.879,6.879,0,0,0-2.461,2.461A6.515,6.515,0,0,0-6.75-9a6.4,6.4,0,0,0,.281,1.986,9.9,9.9,0,0,0,1.09,2.127q.563.879,2.32,3.41Q-1.617.563-.7,1.9ZM0-6.187a2.708,2.708,0,0,1-1.986-.826A2.708,2.708,0,0,1-2.812-9a2.708,2.708,0,0,1,.826-1.986A2.708,2.708,0,0,1,0-11.812a2.708,2.708,0,0,1,1.986.826A2.708,2.708,0,0,1,2.813-9a2.708,2.708,0,0,1-.826,1.986A2.708,2.708,0,0,1,0-6.187Z"
                                    transform="translate(6.75 15.75)" fill="#45cea2"></path>
                            </svg>

                        </span>
                                    <img alt="" src="{{asset('assets/front/img/arrow-down-2.png')}}"
                                         class="img-fluid arrow-abs">

                                    <select class="js-example-basic-single city_ids"
                                            id="city" name="city_id" required>
                                        <option selected="true" disabled="disabled" value="">
                                            {{ __('Select City') }}</option>
                                        @forelse($cities as $city)
                                            <option value="{{ $city->id }}"
                                                {{ old('city') == $city->id ? 'selected' : '' }}>
                                                {{ translate($city->name) }}</option>
                                        @empty
                                            <option selected disabled="disabled" value="">
                                                {{ __('No City have been created') }}</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>

                            <!-- select 2 -->
                            <div class="input-style phone-dropdown custom-drop-contact ">
                                <div class="custom-selct-icons-arow position-relative">
                              <span class="icon-front-input">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13.5" height="18" viewBox="0 0 13.5 18">
                              <path id="Path_48396" data-name="Path 48396"
                                    d="M-.7,1.9A.807.807,0,0,0,0,2.25.807.807,0,0,0,.7,1.9L3.059-1.477q1.758-2.531,2.32-3.41a9.9,9.9,0,0,0,1.09-2.127A6.4,6.4,0,0,0,6.75-9a6.515,6.515,0,0,0-.914-3.375,6.879,6.879,0,0,0-2.461-2.461A6.515,6.515,0,0,0,0-15.75a6.515,6.515,0,0,0-3.375.914,6.879,6.879,0,0,0-2.461,2.461A6.515,6.515,0,0,0-6.75-9a6.4,6.4,0,0,0,.281,1.986,9.9,9.9,0,0,0,1.09,2.127q.563.879,2.32,3.41Q-1.617.563-.7,1.9ZM0-6.187a2.708,2.708,0,0,1-1.986-.826A2.708,2.708,0,0,1-2.812-9a2.708,2.708,0,0,1,.826-1.986A2.708,2.708,0,0,1,0-11.812a2.708,2.708,0,0,1,1.986.826A2.708,2.708,0,0,1,2.813-9a2.708,2.708,0,0,1-.826,1.986A2.708,2.708,0,0,1,0-6.187Z"
                                    transform="translate(6.75 15.75)" fill="#45cea2"></path>
                            </svg>

                        </span>
                                    <img alt="" src="{{asset('assets/front/img/arrow-down-2.png')}}"
                                         class="img-fluid arrow-abs">
                                    <select class="custom-select2" name="area_id" id="area" required>
                                        <option selected disabled="disabled" value="">
                                            {{ __('Select Delivery Areas') }}</option>
                                    </select>
                                </div>
                            </div>


                            <div class="input-style">
                                <div class="wrapper-input" data-target="#map-model-address" data-toggle="modal">
                        <span class="icon-front-input">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13.5" height="18" viewBox="0 0 13.5 18">
                              <path id="Path_48396" data-name="Path 48396"
                                    d="M-.7,1.9A.807.807,0,0,0,0,2.25.807.807,0,0,0,.7,1.9L3.059-1.477q1.758-2.531,2.32-3.41a9.9,9.9,0,0,0,1.09-2.127A6.4,6.4,0,0,0,6.75-9a6.515,6.515,0,0,0-.914-3.375,6.879,6.879,0,0,0-2.461-2.461A6.515,6.515,0,0,0,0-15.75a6.515,6.515,0,0,0-3.375.914,6.879,6.879,0,0,0-2.461,2.461A6.515,6.515,0,0,0-6.75-9a6.4,6.4,0,0,0,.281,1.986,9.9,9.9,0,0,0,1.09,2.127q.563.879,2.32,3.41Q-1.617.563-.7,1.9ZM0-6.187a2.708,2.708,0,0,1-1.986-.826A2.708,2.708,0,0,1-2.812-9a2.708,2.708,0,0,1,.826-1.986A2.708,2.708,0,0,1,0-11.812a2.708,2.708,0,0,1,1.986.826A2.708,2.708,0,0,1,2.813-9a2.708,2.708,0,0,1-.826,1.986A2.708,2.708,0,0,1,0-6.187Z"
                                    transform="translate(6.75 15.75)" fill="#45cea2"/>
                            </svg>
                        </span>
                                    <input type="text" class="address ctm-input auth-input adjus-ad"
                                           name="address" required value="{{ empty(old('address')) }}"
                                           placeholder="{{ __('Address') }}" id="address" readonly
                                           data-target="#register-map-model" data-toggle="modal"
                                           data-latitude="#latitude" data-longitude="#longitude"
                                           data-address="#address">
                                </div>

                                <input type="hidden" name="latitude" id="latitude" class="latitude"
                                       value="{{ empty(old('latitude')) }}">
                                <input type="hidden" name="longitude" id="longitude" class="longitude"
                                       value="{{ empty(old('longitude')) }}">
                            </div>

                            <div class="input-style">
                                <div class="wrapper-input">
                               <span class="icon-front-input">
                          <svg xmlns="http://www.w3.org/2000/svg" width="15.5" height="15.5" viewBox="0 0 15.5 15.5">
                              <path id="Path_48396" data-name="Path 48396"
                                    d="M0-13.75A7.513,7.513,0,0,1,3.875-12.7,7.831,7.831,0,0,1,6.7-9.875,7.513,7.513,0,0,1,7.75-6,7.513,7.513,0,0,1,6.7-2.125,7.831,7.831,0,0,1,3.875.7,7.513,7.513,0,0,1,0,1.75,7.513,7.513,0,0,1-3.875.7,7.831,7.831,0,0,1-6.7-2.125,7.513,7.513,0,0,1-7.75-6,7.513,7.513,0,0,1-6.7-9.875,7.831,7.831,0,0,1-3.875-12.7,7.513,7.513,0,0,1,0-13.75Zm0,3.438a1.261,1.261,0,0,0-.922.391A1.261,1.261,0,0,0-1.312-9a1.261,1.261,0,0,0,.391.922A1.261,1.261,0,0,0,0-7.687a1.261,1.261,0,0,0,.922-.391A1.261,1.261,0,0,0,1.313-9a1.261,1.261,0,0,0-.391-.922A1.261,1.261,0,0,0,0-10.312ZM1.75-2.375v-.75a.362.362,0,0,0-.109-.266A.362.362,0,0,0,1.375-3.5H1V-6.625a.362.362,0,0,0-.109-.266A.362.362,0,0,0,.625-7h-2a.362.362,0,0,0-.266.109.362.362,0,0,0-.109.266v.75a.362.362,0,0,0,.109.266.362.362,0,0,0,.266.109H-1v2h-.375a.362.362,0,0,0-.266.109.362.362,0,0,0-.109.266v.75a.362.362,0,0,0,.109.266A.362.362,0,0,0-1.375-2h2.75a.362.362,0,0,0,.266-.109A.362.362,0,0,0,1.75-2.375Z"
                                    transform="translate(7.75 13.75)" fill="#45cea2"/>
                            </svg>
                        </span>
                                    <input name="address_description" id="address_description" required
                                           placeholder="{{ __('Write here') }}"
                                           value="{{ empty(old('address_description')) ? (!empty($data->address_description) ? $data->address_description : old('address_description')) : old('address_description') }}"
                                           class="ctm-input">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="submitModal" class="btn btn-primary w-100">{{__('Add')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade custom-modal-pa-all" id="exampleModalCenter-2" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{__('Add Order Notes')}}</h5>
                </div>
                <div class="modal-body px-0">
                    <div class="input-style">
                        <div class="wrapper-input">
                            <span class="icon-front-input">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="18.375" viewBox="0 0 21 18.375">
                            <path id="Path_48396" data-name="Path 48396"
                                  d="M0-17.062a12.049,12.049,0,0,1,5.271,1.148,9.871,9.871,0,0,1,3.814,3.1A7.132,7.132,0,0,1,10.5-8.531,7.132,7.132,0,0,1,9.085-4.245a9.871,9.871,0,0,1-3.814,3.1A12.049,12.049,0,0,1,0,0,12.468,12.468,0,0,1-4.389-.779,11.219,11.219,0,0,1-6.645.574a8.874,8.874,0,0,1-3.527.738.291.291,0,0,1-.287-.205.357.357,0,0,1,.041-.369A9.4,9.4,0,0,0-9.475-.451,8.741,8.741,0,0,0-8.162-3.158a8.906,8.906,0,0,1-1.723-2.5A6.858,6.858,0,0,1-10.5-8.531a7.132,7.132,0,0,1,1.415-4.286,9.871,9.871,0,0,1,3.814-3.1A12.048,12.048,0,0,1,0-17.062Z"
                                  transform="translate(10.5 17.063)" fill="#45cea2"></path>
                          </svg>
                      </span>
                            <input type="text" class="ctm-input border-full-cst" id="comment"
                                   placeholder="{{__('Comment')}}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" id="submit_note">{{__('Add')}}</button>
                </div>
            </div>
        </div>
    </div>

    @include('front.common.map-modal-address')
@endsection


@push('scripts')
    <script type="text/javascript" src="https://www.paypalobjects.com/api/checkout.js"></script>
    <script>

        $(`document`).ready(function () {
            $(`#coupon`).validate();
        });

        $(document).on('click', "#submit_note", function () {
            let cmt = $("#comment").val();
            $("#order_notes").val(cmt);
            $("#html_show").html(cmt);
            $("#exampleModalCenter-2").modal('hide');
        });

        $(document).ready(function () {
            $(".custom-select2").select2();
            let for_edit_form = $("#hiddenAreaId").val();
            if (for_edit_form != '') {
                $("#city_id").trigger('change');
            }

            $(document).on('click', '.default-address', function () {
                let id = $(this).attr('data-id');
                $("#address-form-" + id).submit();
            });
            $('#subscribe-form').validate();

        });

        var address = @json($address);
        let address_count = address.length;
        let default_address_id = '';
        for (let i = 0; i < address.length; i++) {
            if (address[i].default_address != 0) {
                default_address_id = address[i].id;
            }
        }
        $('#selected_address').val(default_address_id);
        let paypal_input = $("#selected_total_in_usd").val();
        $("#paypal_input").val(paypal_input);
        $(".js-check-out-btn").show();


        $(document).ready(function () {


            $(document).on('click', '.default-address', function () {
                let id = $(this).attr('data-id');
                $("#address-form-" + id).submit();
            });


            setTimeout(function () {
                let select_value = $('#selected_address').val();
                if (select_value === "") {
                    $("#test3").attr("checked", false);
                }
            }, 2000);

            $(".delete-address").on('click', function (e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                $(".delete-address").attr('disabled', 'disabled');
                swal({
                        title: "{{ __('Are you sure you want to delete this?') }}",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#1C4670",
                        confirmButtonText: "{{ __('Delete') }}",
                        cancelButtonText: "{{ __('No') }}",
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: window.Laravel.baseUrl + "dashboard/delete-address/" + id,
                                success: function (data) {
                                    toastr.success('success',
                                        "{{ __('Address removed from cart') }}")
                                    location.reload();
                                }
                            })
                        } else {
                            $(".delete-address").attr('disabled', false);
                            swal.close()
                        }
                    });
            });

            $(document).on('click', '#openAddressModal', function () {
                $('#modalForm').attr('action',
                    "{{ route('front.dashboard.address.store.withoutView', 0) }}");
                $("#name").val('');
                $("#user_phone").val('');
                $("#city_id").val('');
                $("#area_ids").val('');
                $("#address_field").val('');
                $("#address").val('');
                $("#latitude").val('');
                $("#longitude").val('');
                $("#address_description").val('');
                $("#exampleModalCenter").modal('show');
            });

            $(document).on('click', '#openModalForEdit', function (e) {
                e.preventDefault();
                let url = $(this).data('action');
                $('#modalForm').attr('action', url);
                let edit_url = $(this).data('edit_action');

                $.ajax({
                    type: "GET",
                    url: edit_url,
                    dataType: 'json',
                    success: function (response) {
                        $("#hiddenAreaId").val(response.area_id);
                        $("#name").val(response.name);
                        $("#user_phone").val(response.user_phone);
                        $("#city_id").val(response.city_id);
                        $(".city_ids").trigger("change");
                        $("#address_id").val(response.id);
                        $("#address").val(response.address);
                        $("#model-address").val(response.address);
                        $("#latitude").val(response.latitude);
                        $("#longitude").val(response.longitude);
                        $("#address_description").val(response.address_description);
                        $("#exampleModalCenter").modal('show');
                        $('#area_ids').val(response.area_id).trigger('change');
                    }
                });

            });

            $.validator.addMethod("noSpace", function (value, element) {
                return this.optional(element) || value === "NA" ||
                    value.match(/\S/);
            }, "This field cannot be empty");

            $('#modalForm').validate({
                ignore: '',
                rules: {
                    'name': {
                        required: true,
                        noSpace: true
                    },
                    'address': {
                        required: true,
                        noSpace: true
                    },
                    'city_id': {
                        required: true,
                        noSpace: true
                    },
                    'area_id': {
                        required: true,
                        noSpace: true
                    },
                    'address_description': {
                        required: true,
                        noSpace: true
                    },
                    'user_phone': {
                        required: true,
                        noSpace: true,
                        maxlength: 14
                    }
                },
                errorPlacement: function (error, element) {
                    console.log(element.attr('name'));
                    if (element.attr("name") == "terms_conditions") {
                        error.insertAfter(element.parent().siblings());
                    } else if (element.attr("name") == "city_id") {
                        $(".cityIDError").html(error);
                    } else if (element.attr("name") == "area_id") {
                        $(".areaIDError").html(error);
                    } else {
                        error.insertAfter(element);
                    }
                },
            });

            $(document).on('click', '#submitModal', function (e) {
                // debugger;
                if ($("#modalForm").valid()) {
                    let lat_val = $("#latitude").val();
                    // debugger;
                    if (lat_val === "") {
                        $(".below_address_field").html(
                            '<label id="address-error" class="error" for="address">{{ __('Please select a valid address from the address from the map.') }}</label>'
                        );
                    } else {
                        $(this).prop('disabled', true);
                        e.preventDefault();
                        $("#modalForm").submit();
                    }
                }
            });

            let stat = 'cash_on_delivery';

            $(".js-check-out-btn").on('click', function (e) {
                e.preventDefault();
                let select_value = $('#selected_address').val();
                let checkout = $("#payment_method").val();
                if (checkout == 'paypal') {
                    $('#paypal-button').trigger('click');
                } else {
                    if (select_value == "") {
                        if (address_count >= 1) {
                            toastr.error("{{ __('Please select address to proceed') }}");
                        } else {
                            toastr.error("{{ __('Please add address to proceed') }}");
                        }
                    } else {
                        $("#check-out-form").submit();
                    }
                }
            });

            $(".payment_method").on('click', function () {
                let val = $(this).val();
                $("#payment_method").val(val);
                let select_value = $('#selected_address').val();
                if (val == 'credit_card') {
                    if (val != stat) {
                        stat = val;
                        $("#paypal-button").empty();
                        setUpPaypal()
                        $(".js-check-out-btn").hide();
                        if (select_value !== "") {
                            $('#paypal-button').trigger('click');
                            $("#paypal-button").show();
                        } else {
                            if (address_count >= 1) {
                                toastr.error("{{ __('Please select address to proceed') }}");
                            } else {
                                toastr.error("{{ __('Please add address to proceed') }}");
                            }
                        }
                    }
                } else {
                    if (val != stat) {
                        stat = val;
                        $("#paypal-button").hide();
                        if (select_value !== "") {
                            $(".js-check-out-btn").show();
                        } else {
                            if (address_count >= 1) {
                                toastr.error("{{ __('Please select address to proceed') }}");
                            } else {
                                toastr.error("{{ __('Please add address to proceed') }}");
                            }
                        }
                    }
                }
                if (val != "" && val != undefined && val != null) {
                    $("input[name=pay_type]").val(val);
                }
            });
        });

        function setPaymentClick() {
            var val = $("#payment_method").val();
            let select_value = $('#selected_address').val();
            if (val == 'credit_card') {
                $("#test3").attr("checked", true);
                $(".js-check-out-btn").css('display', 'none')
                if (select_value !== "") {
                    $('#paypal-button').trigger('click');
                    $("#paypal-button").show();
                } else {
                    if (address_count >= 1) {
                        toastr.error("{{ __('Please select address to proceed') }}");
                    } else {
                        toastr.error("{{ __('Please add address to proceed') }}");
                    }
                }
            } else {
                $("#paypal-button").hide()
                if (select_value !== "") {
                    $(".js-check-out-btn").show();
                } else {
                    if (address_count >= 1) {
                        toastr.error("{{ __('Please select address to proceed') }}");
                    } else {
                        toastr.error("{{ __('Please add address to proceed') }}");
                    }
                }
            }
        }

        var paypalActions;

        function setUpPaypal() {
            let addressCount = 0;
            let paypalConfig = {
                env: 'sandbox',
                client: {
                    sandbox: 'AR9DVJvSCQyaYqojNmNyjPaz14YM17PkPJ3KlyCbDfEOg4WYZAYctEF5s6Dxkxx-jVWva2xCXOXHWGvl',
                    production: 'xxxxxxxxxx'
                },
                commit: true,
                validate: function (actions) {
                    actions.disable(); // Allow for validation in onClick()
                    paypalActions = actions; // Save for later enable()/disable() calls
                    let addressCount = $('#selected_address').val();
                    let defaultId = '{{ $defaultId ?? '' }}';
                    console.log("both value=>", addressCount, defaultId)

                    if (addressCount != "" || defaultId == 1) {
                        paypalActions.enable();
                    } else {
                        paypalActions.disable();
                    }
                },
                payment: (data, actions) => {
                    let usd_value = $("#paypal_input").val();
                    console.log("usd value =>", usd_value);
                    return actions.payment.create({
                        payment: {
                            transactions: [{
                                amount: {
                                    total: usd_value,
                                    currency: 'USD'
                                }
                            }]
                        }
                    });
                },
                onAuthorize: (data, actions) => {
                    return actions.payment.execute().then((payment) => {
                        console.log(data);
                        $('#paymentID').val(data.paymentID);
                        $('#payerID').val(data.payerID);
                        $('#orderID').val(data.orderID);
                        $('#paymentToken').val(data.paymentToken);
                        $('#returnUrl').val(data.returnUrl);
                        setTimeout(function () {
                            $("#check-out-form").submit();
                        }, 1000);

                    }).catch((error) => {
                        console.log('Eoor =>', error)
                    });
                },
                onError: (error) => {
                    console.log('Eoor1 =>', error)
                },
                onCancel: (data, actions) => {
                    console.log('Eoor2 =>', data)
                }
            };
            setTimeout(function () {
                paypal.Button.render(paypalConfig, '#paypal-button')
            }, 1000);
        }
    </script>
@endpush
