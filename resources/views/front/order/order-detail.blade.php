@extends('front.layouts.app')
@section('content')
    @include('front.common.breadcrumb')
    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                @include('front.dashboard.common.left-sidebar')


                <div class="col-lg-8 col-md-8">
                    @if($user->isSupplier() && $order->status == 'pending')
                    <div class="order-detail-canel-btn">
                        <a href="#" class="btn btn-primary w-100" data-toggle="modal" data-target="#exampleModalCenter">Assign Delivery Company</a>
                    </div>
                    @endif

                    @if($user->isSupplier() && $order->status == 'delivered')
                    <div class="order-detail-canel-btn">
                        <a href="#" class="btn btn-primary w-100" data-toggle="modal" data-target="#complete-modal">Complete Order</a>
                    </div>
                    @endif

                    @if($user->isCompany() && $order->status == 'confirmed')

                    <div class="order-detail-canel-btn">
                        <a href="#" class="btn btn-primary w-100" data-toggle="modal" data-target="#exampleModalCenter1">Assign To Rider</a>
                    </div>
                    @endif

                    @if($user->isRider() && $order->status == 'shipped')
                    <div class="order-detail-canel-btn">
                        <a href="#" class="btn btn-primary w-100" data-toggle="modal" data-target="#onWay-modal">Delivered</a>
                    </div>
                    @endif

                    @if($order->status == 'pending' || $user->isCompany() && $order->status == 'confirmed')
                    <div class="order-detail-canel-btn mt-15">
                      <button type="button" class="btn btn-black w-100 " data-toggle="modal"
                      data-target="#exampleModalCenter-2">Cancel Order</button>
                  </div>
                  @endif

                  @if($user->isSupplier() && $order->status == 'completed' || $user->isUser() && $order->status == 'completed')
                    <div class="invoice-detail-main">
                      <div class="dropdown invoice">
                          <button type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                            class="m-usd">
                           invoice
                           <svg xmlns="http://www.w3.org/2000/svg" width="9" height="5.5" viewBox="0 0 9 5.5">
                            <path id="Path_48396" data-name="Path 48396" d="M6.62-6.852a.481.481,0,0,1,.148.352.481.481,0,0,1-.148.352l-3.5,3.5a.481.481,0,0,1-.352.148.481.481,0,0,1-.352-.148l-3.5-3.5A.481.481,0,0,1-1.231-6.5a.481.481,0,0,1,.148-.352A.481.481,0,0,1-.731-7h7A.481.481,0,0,1,6.62-6.852Z" transform="translate(1.731 7.5)" fill="#333" stroke="rgba(0,0,0,0)" stroke-width="1"/>
                          </svg>


                          </button>
                          <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                            <a href="{{ route('front.dashboard.order.print.pdf', $user->isSupplier() ? $order->orderDetails->first()->id  : $order->id) }}" target="_blank" class="dropdown-item">View Invoive</a>
                            <a href="{{ route('front.dashboard.order.send.pdf',  $user->isSupplier() ? $order->orderDetails->first()->id  : $order->id) }}" class="dropdown-item">Send Invoice</a>
                          </div>
                        </div>

                     </div>
                    @endif
                    <div class="order-detail-main-area">
                        <div class="inner-wrapper-order-dt d-flex flex-column flex-lg-row justify-content-between">
                          <div class="top-tittle-area-dt">
                             <h2 class="oder-dt-cus-head">Order#
                                 {!! $order->order_number !!}
                            </h2>
                             <div class="total-p-dt-page">
                                <h4 class="price-tittle-dt">total: <span>{!! getPrice($order->total,$currency)!!}</span></h4>

                             </div>
                             <h2 class="oder-dt-cus-head-2">Order Time: <span class="span-time-head-2">{{ \Carbon\Carbon::createFromTimestamp($order->created_date)->format('F d, Y') }} {{__('at')}} {{ \Carbon\Carbon::createFromTimestamp($order->created_date)->format('H:i A') }}</span></h2>
                             <h2 class="oder-dt-cus-head-2">Total Products: <span class="span-time-head-2">{{count($order->orderItems)}}</span></h2>


                          </div>
                         <div class="order-dt-statuss">
                            <h2 class="oder-dt-cus-head-right">status: <span class="span-status--right">{{getOrderStatus($order->status)}}</span></h2>

                         </div>
                        </div>




                    @if(!is_string($order->address))
                        <div class="custom-class-for-space-dt">
                         <h2 class="dt-sub-tittle-head">Shipping Address</h2>
                         <div class="main-block-shpping-image-content">
                            <div class="shipping-dt-img d-flex align-items-center justify-content-center">
                              <svg xmlns="http://www.w3.org/2000/svg" width="27" height="36" viewBox="0 0 27 36">
                                <path id="Path_48396" data-name="Path 48396" d="M12.094,3.8a1.613,1.613,0,0,0,1.406.7,1.613,1.613,0,0,0,1.406-.7l4.711-6.75q3.516-5.062,4.641-6.82a19.8,19.8,0,0,0,2.18-4.254A12.8,12.8,0,0,0,27-18a13.03,13.03,0,0,0-1.828-6.75,13.758,13.758,0,0,0-4.922-4.922A13.03,13.03,0,0,0,13.5-31.5a13.03,13.03,0,0,0-6.75,1.828A13.758,13.758,0,0,0,1.828-24.75,13.03,13.03,0,0,0,0-18a12.8,12.8,0,0,0,.563,3.973,19.8,19.8,0,0,0,2.18,4.254q1.125,1.758,4.641,6.82Q10.266,1.125,12.094,3.8ZM13.5-12.375a5.417,5.417,0,0,1-3.973-1.652A5.417,5.417,0,0,1,7.875-18a5.417,5.417,0,0,1,1.652-3.973A5.416,5.416,0,0,1,13.5-23.625a5.417,5.417,0,0,1,3.973,1.652A5.416,5.416,0,0,1,19.125-18a5.417,5.417,0,0,1-1.652,3.973A5.417,5.417,0,0,1,13.5-12.375Z" transform="translate(0 31.5)" fill="#45cea2"/>
                              </svg>
                            </div>
                            <div class="right-content-shipping">
                                <h2 class="tittle-name-city">{{$order->address['address']}}</h2>
                            </div>
                         </div>
                         <h2 class="oder-dt-cus-head-ad">Name: <span class="span-time-head-2">{{$order->address['name']}}</span></h2>
                         <h2 class="oder-dt-cus-head-ad">Phone No: <span class="span-time-head-2" dir="ltr">{{$order->address['user_phone']}}</span></h2>
                         <h2 class="oder-dt-cus-head-ad">City: <span class="span-time-head-2"> {{ isset($order->address['city']) ? translate($order->address['city']['name']) : '' }}</span></h2>


                        </div>
                        @endif

                        @if($order->status == 'confirmed' && $user->isSupplier())

                        <div class="custom-class-for-space-dt">
                         <h2 class="dt-sub-tittle-head">Delivery Company</h2>
                         <div class="main-block-shpping-image-content">
                            <div class="shipping-dt-img d-flex align-items-center justify-content-center">
                              <svg xmlns="http://www.w3.org/2000/svg" width="27" height="36" viewBox="0 0 27 36">
                                <path id="Path_48396" data-name="Path 48396" d="M12.094,3.8a1.613,1.613,0,0,0,1.406.7,1.613,1.613,0,0,0,1.406-.7l4.711-6.75q3.516-5.062,4.641-6.82a19.8,19.8,0,0,0,2.18-4.254A12.8,12.8,0,0,0,27-18a13.03,13.03,0,0,0-1.828-6.75,13.758,13.758,0,0,0-4.922-4.922A13.03,13.03,0,0,0,13.5-31.5a13.03,13.03,0,0,0-6.75,1.828A13.758,13.758,0,0,0,1.828-24.75,13.03,13.03,0,0,0,0-18a12.8,12.8,0,0,0,.563,3.973,19.8,19.8,0,0,0,2.18,4.254q1.125,1.758,4.641,6.82Q10.266,1.125,12.094,3.8ZM13.5-12.375a5.417,5.417,0,0,1-3.973-1.652A5.417,5.417,0,0,1,7.875-18a5.417,5.417,0,0,1,1.652-3.973A5.416,5.416,0,0,1,13.5-23.625a5.417,5.417,0,0,1,3.973,1.652A5.416,5.416,0,0,1,19.125-18a5.417,5.417,0,0,1-1.652,3.973A5.417,5.417,0,0,1,13.5-12.375Z" transform="translate(0 31.5)" fill="#45cea2"/>
                              </svg>
                            </div>
                            <div class="right-content-shipping">
                                <h2 class="tittle-name-city">{{$order->deliveryCompany->company->address}}</h2>

                            </div>
                         </div>
                         <h2 class="oder-dt-cus-head-ad">Name: <span class="span-time-head-2">{{translate($order->deliveryCompany->company->supplier_name)}}</span></h2>
                         <h2 class="oder-dt-cus-head-ad">Phone No: <span class="span-time-head-2" dir="ltr">{{$order->deliveryCompany->company->phone}}</span></h2>
                         {{-- <h2 class="oder-dt-cus-head-ad">Address: <span class="span-time-head-2"> {{$order->address->address['address_description'] ?? ''}}</span></h2> --}}

                        </div>
                        @endif

                        @if($user->isCompany() || $user->isRider())

                        <div class="custom-class-for-space-dt">
                         <h2 class="dt-sub-tittle-head">supplier Information</h2>
                         <div class="main-block-shpping-image-content">
                            <div class="shipping-dt-img d-flex align-items-center justify-content-center">
                              <svg xmlns="http://www.w3.org/2000/svg" width="27" height="36" viewBox="0 0 27 36">
                                <path id="Path_48396" data-name="Path 48396" d="M12.094,3.8a1.613,1.613,0,0,0,1.406.7,1.613,1.613,0,0,0,1.406-.7l4.711-6.75q3.516-5.062,4.641-6.82a19.8,19.8,0,0,0,2.18-4.254A12.8,12.8,0,0,0,27-18a13.03,13.03,0,0,0-1.828-6.75,13.758,13.758,0,0,0-4.922-4.922A13.03,13.03,0,0,0,13.5-31.5a13.03,13.03,0,0,0-6.75,1.828A13.758,13.758,0,0,0,1.828-24.75,13.03,13.03,0,0,0,0-18a12.8,12.8,0,0,0,.563,3.973,19.8,19.8,0,0,0,2.18,4.254q1.125,1.758,4.641,6.82Q10.266,1.125,12.094,3.8ZM13.5-12.375a5.417,5.417,0,0,1-3.973-1.652A5.417,5.417,0,0,1,7.875-18a5.417,5.417,0,0,1,1.652-3.973A5.416,5.416,0,0,1,13.5-23.625a5.417,5.417,0,0,1,3.973,1.652A5.416,5.416,0,0,1,19.125-18a5.417,5.417,0,0,1-1.652,3.973A5.417,5.417,0,0,1,13.5-12.375Z" transform="translate(0 31.5)" fill="#45cea2"/>
                              </svg>
                            </div>
                            <div class="right-content-shipping">
                                <h2 class="tittle-name-city">{{$order->orderDetails->first()->store->address}}</h2>

                            </div>
                         </div>
                         <h2 class="oder-dt-cus-head-ad">Name: <span class="span-time-head-2">{{translate($order->orderDetails->first()->store->supplier_name)}}</span></h2>
                         <h2 class="oder-dt-cus-head-ad">Phone No: <span class="span-time-head-2" dir="ltr">{{$order->orderDetails->first()->store->phone}}</span></h2>
                         {{-- <h2 class="oder-dt-cus-head-ad">Address: <span class="span-time-head-2"> {{$order->address->address['address_description'] ?? ''}}</span></h2> --}}

                        </div>
                        @endif

                        @if($order->status == 'shipped' && $user->isCompany())

                        <div class="custom-class-for-space-dt">
                         <h2 class="dt-sub-tittle-head">Rider Information</h2>
                         <div class="main-block-shpping-image-content">
                            <div class="shipping-dt-img d-flex align-items-center justify-content-center">
                              <svg xmlns="http://www.w3.org/2000/svg" width="27" height="36" viewBox="0 0 27 36">
                                <path id="Path_48396" data-name="Path 48396" d="M12.094,3.8a1.613,1.613,0,0,0,1.406.7,1.613,1.613,0,0,0,1.406-.7l4.711-6.75q3.516-5.062,4.641-6.82a19.8,19.8,0,0,0,2.18-4.254A12.8,12.8,0,0,0,27-18a13.03,13.03,0,0,0-1.828-6.75,13.758,13.758,0,0,0-4.922-4.922A13.03,13.03,0,0,0,13.5-31.5a13.03,13.03,0,0,0-6.75,1.828A13.758,13.758,0,0,0,1.828-24.75,13.03,13.03,0,0,0,0-18a12.8,12.8,0,0,0,.563,3.973,19.8,19.8,0,0,0,2.18,4.254q1.125,1.758,4.641,6.82Q10.266,1.125,12.094,3.8ZM13.5-12.375a5.417,5.417,0,0,1-3.973-1.652A5.417,5.417,0,0,1,7.875-18a5.417,5.417,0,0,1,1.652-3.973A5.416,5.416,0,0,1,13.5-23.625a5.417,5.417,0,0,1,3.973,1.652A5.416,5.416,0,0,1,19.125-18a5.417,5.417,0,0,1-1.652,3.973A5.417,5.417,0,0,1,13.5-12.375Z" transform="translate(0 31.5)" fill="#45cea2"/>
                              </svg>
                            </div>
                            <div class="right-content-shipping">
                                <h2 class="tittle-name-city">{{$order->rider->rider->address}}</h2>

                            </div>
                         </div>
                         <h2 class="oder-dt-cus-head-ad">Name: <span class="span-time-head-2">{{translate($order->rider->rider->supplier_name)}}</span></h2>
                         <h2 class="oder-dt-cus-head-ad">Phone No: <span class="span-time-head-2" dir="ltr">{{$order->rider->rider->phone}}</span></h2>
                         {{-- <h2 class="oder-dt-cus-head-ad">Address: <span class="span-time-head-2"> {{$order->address->address['address_description'] ?? ''}}</span></h2> --}}

                        </div>
                        @endif

                        <div class="custom-class-for-space-dt">
                         <h2 class="dt-sub-tittle-head">Order Notes</h2>
                            <h4 class="cash-delivey-pay-dt">{{$order->order_notes}}</h4>

                        </div>

                        <div class="custom-class-for-space-dt">
                          <h2 class="dt-sub-tittle-head">Payment Method</h2>
                          @if($order->payment_method=="cash_on_delivery")
                          <h4 class="cash-delivey-pay-dt"> {{__('Cash On Delivery')}}</h4>
                      @else
                      <h4 class="cash-delivey-pay-dt"> {{__('Paypal')}}</h4>
                      @endif


                         </div>
                         @if($order->status == "cancelled")
                         <div class="custom-class-for-space-dt">
                            <h2 class="dt-sub-tittle-head">Cancelation Note</h2>

                            <h4 class="cash-delivey-pay-dt"> {!! $order->cancel_reason !!}</h4>

                        </div>
                        @endif






                         <!-- product list-card here detail -->
                         <div class="product-list-ord-dt-page">
                          <h2 class="dt-sub-tittle-head">Product List</h2>
                         <div class="d-flex align-items-center justify-content-between">
                            @if($user->isUser())

                            <h2 class="oder-dt-cus-head-2">Supplier: <span class="span-time-head-2">{{translate((array)$order->orderItems[0]->store->supplier_name) ?? ''}}</span></h2>
                            {{-- <h2 class="oder-dt-cus-head-2">Status: <span class="span-time-head-2"> {{$order->orderDetail->status ?? 'N/A'}}</span></h2> --}}

                        @endif


                         </div>

                          <!-- repeat-card block -->
                          @include('front.order.partials.product_listing',[$order])

                         </div>

                         <!-- end product-list card -->




                      <div class="order-summary-parent">
                        <div class="inner-summary-p">
                          <h2 class="tittle-summ">Order Summary</h2>
                          <div class="two-tittles-parent">
                            <h2 class="tittle-left">Items Price</h2>
                            <h3 class="tittle-right">{!! getPrice($order->subtotal,$currency)!!}</h3>

                          </div>
                          <div class="two-tittles-parent">
                            <h2 class="tittle-left">Discount</h2>


                            @if($user->isUser())

                            @if ($order->discount > 0)
                                <div class="sec-1 d-flex justify-content-between align-items-center">
                                    <div class="price">{{__('Coupon Code')}}</div>
                                    <div href="#" class=" applying">#{{ $order->user->coupon }} <span
                                            class="grey">({{ $order->discount_percentage }}%)</span><span
                                            class="rates">-{{getPrice( $order->discount,$currency)}}</span>
                                    </div>
                                </div>
                            @else
                            <h3 class="tittle-right"> {{getPrice(0,$currency)}} </h3>
                            @endif
                        @else
                            @if ($order->discount > 0)
                                <div class="sec-1 d-flex justify-content-between align-items-center">
                                    <div class="price">{{__('Coupon Code')}}</div>
                                    <div href="#" class=" applying">#{{ $order->order->user->coupon }} <span
                                            class="grey">({{ $order->discount_percentage }}%)</span><span
                                            class="rates">-{{getPrice( $order->discount,$currency)}}</span>
                                    </div>
                                </div>
                                @else
                                <h3 class="tittle-right"> {{getPrice(0,$currency)}} </h3>
                            @endif

                        @endif


                            {{-- <h3 class="tittle-right">-50 AED</h3> --}}

                          </div>
                          <div class="two-tittles-parent">
                            <h2 class="tittle-left">Delivery Fee</h2>
                            <h3 class="tittle-right">{!! getPrice($order->shipping,$currency)!!}</h3>

                          </div>
                          <div class="two-tittles-parent">
                            <h2 class="tittle-left">VAT</h2>
                            <h3 class="tittle-right">{!! getPrice($order->vat,$currency)!!}</h3>

                          </div>

                          <div class="total-summ-bottom-parent">
                            <h2 class="tittle-left">Total</h2>
                            <h3 class="tittle-right">{!! getPrice($order->total,$currency)!!}</h3>

                          </div>


                        </div>

                      </div>



                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade custom-modal-pa-all" id="exampleModalCenter-2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form action="{{route('front.dashboard.order.cancel')}}" method="post" id="update_cart_form" class="mw-100 w-100">
                @csrf
            <div class="modal-content">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            @if(!$user->isCompany() )
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Cancellation Reasons</h5>

            </div>

            <div class="modal-body">
              <div class="radio-button-modal-cancel">

                  <div class="remember-me cancel-out-pageee-radio pt-1 ">
                      <label class="custom-radio">Delivery time is too long
                        <input type="radio" checked="checked" value="Delivery time is too long" name="cancel_reason">
                        <span class="checkmark"></span>
                      </label>

                      <label class="custom-radio">Want to change payment method
                        <input type="radio" value="Want to change payment method" name="cancel_reason">
                        <span class="checkmark"></span>
                      </label>

                      <label class="custom-radio">Change of mind
                        <input type="radio" value="Change of mind" name="cancel_reason">
                        <span class="checkmark"></span>
                      </label>

                      <label class="custom-radio">Other
                        <input type="radio" value="Other" name="cancel_reason">
                        <span class="checkmark"></span>
                      </label>
                    </div>

              </div>

            </div>
            @endif
            <div class="modal-footer">
              <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
              @if($user->isSupplier() )

                  <input type="hidden" value="{!! $order->user_id !!}" name="orderUserId">
                  <input type="hidden" value="{!! $order->orderDetails->first()->id !!}" name="id">
                  <input type="hidden" value="{!! $order->id !!}" name="order_id">
                  <input type="hidden" value="{!! $order->orderDetails->first()->store_id !!}" name="store_id">
                  <button type="submit" class="btn btn-primary w-100">
                    Cancel Order
                  </button>
              @endif

              @if($user->isCompany() )
                  <input type="hidden" value="{!! $order->user_id !!}" name="orderUserId">
                  <input type="hidden" value="{!! $order->orderDetails->first()->id !!}" name="order_detail_id">
                  <input type="hidden" value="{!! $order->id !!}" name="order_id">
                  <input type="hidden" value="{!! $order->store_id !!}" name="store_id">

                <button type="submit" class="btn btn-primary w-100">
                    Cancel Order
                  </button>
              @endif

                @if($user->isUser())
                <input type="hidden" value="{!! $order->id !!}" name="id">
                <input type="hidden" value="{!! $order->orderDetails[0]->store_id !!}" name="store_id">
                <button type="submit" class="btn btn-primary w-100">
                 Cancel Order
                </button>
                 @endif
            </div>
          </div>
        </form>
        </div>
      </div>


                     {{-- <div class="modal fade" id="exampleModalCenter-2" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <p class="text-center succes-gift mb-20">Are you sure you want to cancel your
                                        current
                                        Booking (# @if($user->isUser())
                                        {!! $order->order_number !!}
                                    @else
                                        {!! $order->order_no !!}
                                    @endif )</p>
                                    <div class="d-flex gx-30 align-items-center">
                                        <button data-dismiss="modal" class="secondary-btn">
                                            <span>No</span>
                                        </button>

                                        @if($user->isSupplier() )
                                        <form action="{{route('front.dashboard.order.cancel')}}" method="post" id="update_cart_form" class="mw-100 w-100">
                                            @csrf
                                            <input type="hidden" value="{!! $order->user_id !!}" name="orderUserId">
                                            <input type="hidden" value="{!! $order->orderDetails->first()->id !!}" name="id">
                                            <input type="hidden" value="{!! $order->id !!}" name="order_id">
                                            <input type="hidden" value="{!! $order->orderDetails->first()->store_id !!}" name="store_id">
                                        <div class="auth-btn w-100">
                                            <button type="submit" class="primary-btn mw-100 w-100">
                                                <span>Yes</span>
                                            </button>
                                        </div>
                                        </form>
                                        @endif

                                        @if($user->isCompany() )
                                        <form action="{{route('front.dashboard.order.cancel')}}" method="post" id="update_cart_form" class="mw-100 w-100">
                                            @csrf
                                            <input type="hidden" value="{!! $order->user_id !!}" name="orderUserId">
                                            <input type="hidden" value="{!! $order->orderDetails->first()->id !!}" name="order_detail_id">
                                            <input type="hidden" value="{!! $order->id !!}" name="order_id">
                                            <input type="hidden" value="{!! $order->store_id !!}" name="store_id">
                                        <div class="auth-btn w-100">
                                            <button type="submit" class="primary-btn mw-100 w-100">
                                                <span>Yes</span>
                                            </button>
                                        </div>
                                        </form>
                                        @endif


                                        @if($user->isUser())
                                        <form action="{{route('front.dashboard.order.cancel')}}" method="post" id="update_cart_form" class="mw-100 w-100">
                                            @csrf
                                            <input type="hidden" value="{!! $order->id !!}" name="id">
                                            <input type="hidden" value="{!! $order->orderDetails[0]->store_id !!}" name="store_id">
                                        <div class="auth-btn w-100">
                                            <button type="submit" class="primary-btn mw-100 w-100">
                                                <span>Yes</span>
                                            </button>
                                        </div>
                                        </form>
                                        @endif
                                    </div>



                                    <button type="button" class="close d-none" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>



                            </div>
                        </div> --}}


                           <!-- Modal delivery assign-->
@if($user->isSupplier())

<div class="modal fade custom-modal-pa-all" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content delivery-assign-modal">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Assign Delivery Company</h5>

        </div>
        <form method="get"
            action="{{ route('front.dashboard.order.update', ['id' => $order->id, 'slug' => 'accept']) }}"
            id="order-accept-modal" tabindex="-1" role="dialog"
            aria-labelledby="comment-modalTitle" aria-hidden="true">
        <div class="modal-body">
          <div class="delivery-assign-radio">

              <div class="remember-me delivery-radio-list pt-1 ">
                @forelse ($delivery_companies as $key => $dc)

                  <label class="custom-radio comany_id_select" data-id="{{ $dc->id }}">
                    <input type="radio" checked="checked" name="radio">
                    <span class="checkmark">
                      <!-- card radio  -->
                      <div class="sleect-delivery-radio-p-card">
                        <div class="image-block-card-radio">
                          <img src="{{ imageUrl($dc->image, 50, 50, 95, 1) }}" class="img-fluid" alt="image">

                        </div>

                        <div class="right-side-contentof-card">
                          <h3 class="tittle-fedd text-truncate">{{ translate($dc->supplier_name) }}</h3>
                          <h4 class="address-icon-s text-truncate"><svg xmlns="http://www.w3.org/2000/svg" width="10.5" height="14" viewBox="0 0 10.5 14">
                            <path id="Path_48397" data-name="Path 48397" d="M4.7,1.477a.627.627,0,0,0,.547.273A.627.627,0,0,0,5.8,1.477L7.629-1.148Q9-3.117,9.434-3.8a7.7,7.7,0,0,0,.848-1.654A4.977,4.977,0,0,0,10.5-7a5.067,5.067,0,0,0-.711-2.625,5.35,5.35,0,0,0-1.914-1.914A5.067,5.067,0,0,0,5.25-12.25a5.067,5.067,0,0,0-2.625.711A5.35,5.35,0,0,0,.711-9.625,5.067,5.067,0,0,0,0-7,4.977,4.977,0,0,0,.219-5.455,7.7,7.7,0,0,0,1.066-3.8q.438.684,1.8,2.652Q3.992.438,4.7,1.477ZM5.25-4.812a2.106,2.106,0,0,1-1.545-.643A2.106,2.106,0,0,1,3.063-7a2.106,2.106,0,0,1,.643-1.545A2.106,2.106,0,0,1,5.25-9.187a2.106,2.106,0,0,1,1.545.643A2.106,2.106,0,0,1,7.438-7a2.106,2.106,0,0,1-.643,1.545A2.106,2.106,0,0,1,5.25-4.812Z" transform="translate(0 12.25)" fill="#45cea2"/>
                          </svg>
                          {{ $dc->address }}</h4>

                        </div>

                      </div>

                      <!-- end card radio -->
                    </span>
                  </label>
                  @empty
                  @include('front.common.alert-empty', [
                      'message' => 'No Delivery Companies Found',
                  ])
                @endforelse

                </div>



          </div>

        </div>
        <div class="modal-footer">
            <input type="hidden" name="order_detail_id" value="{{ $order->orderDetails->first()->id }}">
            <input type="hidden" id="delivery_companies" name="company_id" @if(count($delivery_companies) > 0) value="{{$delivery_companies->first()->id}}" @endif>
          <button type="button" class="btn btn-black w-100" data-dismiss="modal">Close</button>
          <span class="space-seprater-"></span>
          <button type="{{count($delivery_companies) > 0 ? 'submit' : 'button'}}" class="btn btn-primary w-100">assign</button>
        </div>
    </form>
      </div>
    </div>
  </div>

  @if ($order->status == 'delivered')
        {{-- <div class="modal fade ctm-modal" id="complete-modal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <button class="close-btn" data-dismiss="modal">
                        <svg id="cross-icon" xmlns="http://www.w3.org/2000/svg" width="21.547" height="21.547"
                            viewBox="0 0 21.547 21.547">
                            <path id="Path_49208" data-name="Path 49208"
                                d="M12.7,10.774l8.711-8.71a.481.481,0,0,0,0-.68L20.165.141a.481.481,0,0,0-.68,0l-8.71,8.71L2.064.141a.5.5,0,0,0-.68,0L.143,1.384a.48.48,0,0,0,0,.68l8.71,8.71-8.71,8.71a.48.48,0,0,0,0,.68l1.242,1.243a.481.481,0,0,0,.68,0l8.71-8.71,8.71,8.71a.481.481,0,0,0,.68,0l1.242-1.243a.481.481,0,0,0,0-.68Z"
                                transform="translate(-0.002)" fill="#852830" />
                        </svg>
                    </button>
                    <div class="modal-logo-block mx-auto">
                        <a href="{{ route('front.index') }}">
                            <img src="{{ asset('assets/front/images/auth-logo.png') }}" alt="modal-logo"
                                class="img-fluid modal-logo">
                        </a>
                    </div>
                    <h3 class="auth-title">
                        {{ __('Mark as Complete') }}
                    </h3>
                    <p class="primary-text-light mb-28">
                        {{ __('Are you sure you want to mark the current order Order#') }}{{ $order->order_number }}
                        {{ __('as complete?') }}
                    </p>
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="outline-btn secondary-btn">
                                <span>{{ __('No') }}</span>
                            </button>
                        </div>
                        <div class="col-sm-6">
                            <form method="get"
                                action="{{ route('front.dashboard.order.update', ['id' => $order->id, 'slug' => 'complete']) }}" />
                               <input type="hidden" name="order_detail_id" value="{{ $order->orderDetails->first()->id }}">

                                <button type="submit" class="outline-btn">
                                <span>{{ __('Yes') }}</span>
                            </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}


        <div class="modal fade custom-modal-pa-all" id="complete-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Are you sure you want to mark the current order Order#') }}{{ $order->order_number }}
                    {{ __('as complete?') }}</h5>

                </div>


                <form method="get" action="{{ route('front.dashboard.order.update', ['id' => $order->id, 'slug' => 'complete']) }}" >
                  <input type="hidden" name="order_detail_id" value="{{ $order->orderDetails->first()->id }}">
                <button type="submit" class="btn btn-primary w-100">Yes</button>
                </form>


              </div>
            </div>
          </div>
    @endif
  @endif

  <!-- end delivery assign modal -->


@if($user->isCompany())

<div class="modal fade custom-modal-pa-all" id="exampleModalCenter1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content delivery-assign-modal">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Assign To Rider</h5>

        </div>
        <form method="get"
            action="{{ route('front.dashboard.order.assign.rider', $order->orderDetails->first()->id) }}"
            id="order-accept-modal-rider" tabindex="-1" role="dialog"
            aria-labelledby="comment-modalTitle" aria-hidden="true">
        <div class="modal-body">
          <div class="delivery-assign-radio">

              <div class="remember-me delivery-radio-list pt-1 ">
                @forelse ($riders as $key => $dc)

                  <label class="custom-radio rider_id_select" data-id="{{ $dc->id }}">
                    <input type="radio" checked="checked" name="radio" required>
                    <span class="checkmark">
                      <!-- card radio  -->
                      <div class="sleect-delivery-radio-p-card">
                        <div class="image-block-card-radio">
                          <img src="{{ imageUrl($dc->image, 50, 50, 95, 1) }}" class="img-fluid" alt="image">

                        </div>

                        <div class="right-side-contentof-card">
                          <h3 class="tittle-fedd text-truncate">{{ translate($dc->supplier_name) }}</h3>
                          <h4 class="address-icon-s text-truncate"><svg xmlns="http://www.w3.org/2000/svg" width="10.5" height="14" viewBox="0 0 10.5 14">
                            <path id="Path_48397" data-name="Path 48397" d="M4.7,1.477a.627.627,0,0,0,.547.273A.627.627,0,0,0,5.8,1.477L7.629-1.148Q9-3.117,9.434-3.8a7.7,7.7,0,0,0,.848-1.654A4.977,4.977,0,0,0,10.5-7a5.067,5.067,0,0,0-.711-2.625,5.35,5.35,0,0,0-1.914-1.914A5.067,5.067,0,0,0,5.25-12.25a5.067,5.067,0,0,0-2.625.711A5.35,5.35,0,0,0,.711-9.625,5.067,5.067,0,0,0,0-7,4.977,4.977,0,0,0,.219-5.455,7.7,7.7,0,0,0,1.066-3.8q.438.684,1.8,2.652Q3.992.438,4.7,1.477ZM5.25-4.812a2.106,2.106,0,0,1-1.545-.643A2.106,2.106,0,0,1,3.063-7a2.106,2.106,0,0,1,.643-1.545A2.106,2.106,0,0,1,5.25-9.187a2.106,2.106,0,0,1,1.545.643A2.106,2.106,0,0,1,7.438-7a2.106,2.106,0,0,1-.643,1.545A2.106,2.106,0,0,1,5.25-4.812Z" transform="translate(0 12.25)" fill="#45cea2"/>
                          </svg>
                          {{ $dc->address }}</h4>

                        </div>

                      </div>

                      <!-- end card radio -->
                    </span>
                  </label>
                  @empty
                  @include('front.common.alert-empty', [
                      'message' => 'No Rider Found',
                  ])
                @endforelse

                </div>



          </div>

        </div>
        <div class="modal-footer">

            <input type="hidden" id="rider_id" name="rider_id" @if(count($riders) > 0) value="{{$riders->first()->id}}" @endif>
            <input type="hidden" name="order_detail_id" value="{{ $order->orderDetails->first()->id }}">
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <input type="hidden" name="delivery_company_id" value="{{ $user->id }}">
            <input type="hidden" name="status" value="shipped">
          <button type="button" class="btn btn-black w-100" data-dismiss="modal">Close</button>
          <span class="space-seprater-"></span>
          <button type="{{count($riders) > 0 ? 'submit' : 'button'}}" class="btn btn-primary w-100">assign</button>
        </div>
    </form>
      </div>
    </div>
  </div>
@endif


@if ($user->isRider())



<div class="modal fade custom-modal-pa-all" id="onWay-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Are you sure you want to mark the selected order Order#') }}{{ $order->order_number }}
            {{ __('as delivered?') }}</h5>

        </div>
            <form
            action="{{ route('front.dashboard.order.update', ['id' => $order->id, 'slug' => 'delivered']) }}">
            <input type="hidden" name="order_detail_id" value="{{ $order->orderDetails->first()->id }}">
            <input type="hidden" name="order_id" value="{{ $order->id }}">
            <input type="hidden" name="status" value="delivered">

            <button type="submit" class="btn btn-primary w-100">Yes</button>
        </form>


      </div>
    </div>
  </div>
@endif
  <!-- end delivery assign modal -->
@endsection

@push('scripts')
    <script>
        function generatePDF(element, type, status = false) {
            let id = ($(element).attr('seq'));
            $.ajax({
                url: window.Laravel.baseUrl + 'dashboard/printpdf/' + id + '/' + type,
                success: function (data) {
                    console.log("view Invoice =>",data.data);
                    if (data.success == true) {
                        window.open(data.data, '_blank');
                    } else {
                        if (status == false) {
                            var url = window.Laravel.base + data;
                            window.open(url, '_blank');
                        }
                    }
                }
            });
        }

        $(document).ready(function () {

            $('#order-accept-modal-rider').validate({
                ignore: '',
                rules: {
                    company_id: {
                        required: true
                    }
                },
                errorPlacement: function (error, element) {
                    debugger;
                error.insertAfter(element.parent());


                }
            });



            $(`.comany_id_select`).on('click', function(e) {
                let id = $(this).data('id');
                $(`#delivery_companies`).val(id);
            });

            $(`.rider_id_select`).on('click', function(e) {
                let id = $(this).data('id');
                $(`#rider_selected`).val(id);
            });

            $(document).on('click', '#yes_btn_cancel_modal', function () {
                $("#cancel_reason_modal").modal('show');
            });

            $(document).on('click', '#sendIn', function () {

                let order_id = $(this).data('order_id');
                $.ajax({
                    url: window.Laravel.baseUrl + 'dashboard/shareInvoiceToEmail/' + order_id,
                    success: function (data) {
                        toastr.success('success', "{{__('Invoice Shared Successfully')}}")
                    }
                });
            });

            $(document).on('click', '#no_btn_in_cancel_modal', function () {
                $("#cancelOrder").modal('hide');
            });

            $(document).on('click', '.cancelOrder', function () {
                $("#cancelOrder").modal('show');
            });

            $(document).on('click', '#action_for_rate_supplier', function () {
                let id = $(this).data('id');
                window.location.href = window.Laravel.baseUrl + "supplier/" + id + "/add-review";
            });

            $(document).on('click', '#action_for_rate_product', function () {
                let slug = $(this).data('slug');
                window.location.href = window.Laravel.baseUrl + "product/" + slug + "/add-review";
            });

            $('#submit_assign_rider').validate({
                ignore: '',
                rules: {
                    company_id: {
                        required: true
                    }
                },
                errorPlacement: function (error, element) {
                    if (element.attr("name") == "company_id") {
                        $('.error_for_driver').html(error);
                    } else {
                        error.insertAfter(element.parent());

                    }
                }
            });


            $(document).on('click', '#close_assign_modal', function () {
                $("#exampleModalCenter_for_assign").modal('hide');
            });

            $(document).on('click', '#cancel_submit', function () {
                $("#cancel_form_submit").submit();
            });

            $(document).on('click', '#submit_assign_modal', function () {
                if ($('#submit_assign_rider').validate()) {
                    $("#submit_assign_rider").submit();
                }
            });

            $(document).on('click', '.reason_list', function () {
                $(this).addClass('active');
                $(this).closest('a').find('input').attr('checked', true);
            });
        });


    </script>
@endpush
