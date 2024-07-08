@extends('front.layouts.app')
@push('stylesheet-page-level')
    <style>
        .active {
            background-color: black !important;
            color: white !important;
        }

        .active-content {
            display: block !important;
        }
    </style>
@endpush
@section('content')
    @include('front.common.breadcrumb')

    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                @include('front.dashboard.common.left-sidebar')
                {{-- <div class="col-lg-9">`
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="tabs w-100">
                                <a href="{!! route('front.dashboard.order.index',['status'=>'all']) !!}"
                                   class="tablink d-flex justify-content-center align-items-center {!! url()->current() == route('front.dashboard.order.index','all') ? 'active':'' !!}">{{__('All')}}</a>
                                <a href="{!! route('front.dashboard.order.index',['status'=>'pending']) !!}"
                                   class="tablink d-flex justify-content-center align-items-center {!! url()->current() == route('front.dashboard.order.index','pending') ? 'active':'' !!}">{{__('Pending')}}</a>
                                @if($user->isUser())
                                    <a href="{!! route('front.dashboard.order.index',['status'=>'in-progress']) !!}"
                                       class="tablink d-flex justify-content-center align-items-center {!! url()->current() == route('front.dashboard.order.index','in-progress') ? 'active':'' !!}">{{__('In-progress')}}</a>
                                @endif

                                <a href="{!! route('front.dashboard.order.index',['status'=>'accepted']) !!}"
                                   class="tablink d-flex justify-content-center align-items-center {!! url()->current() == route('front.dashboard.order.index','accepted') ? 'active':'' !!}"
                                   id="defaultOpen">{{__('Accepted')}}
                                </a>
                                <a href="{!! route('front.dashboard.order.index',['status'=>'completed']) !!}"
                                   class="tablink d-flex justify-content-center align-items-center {!! url()->current() == route('front.dashboard.order.index','completed') ? 'active':'' !!}"
                                   id="defaultOpen">{{__('Completed')}}
                                </a>
                                <a href="{!! route('front.dashboard.order.index',['status'=>'cancelled']) !!}"
                                   class="tablink d-flex justify-content-center align-items-center {!! url()->current() == route('front.dashboard.order.index','cancelled') ? 'active':'' !!}"
                                   id="defaultOpen">{{__('Cancelled')}}
                                </a>
                            </div>
                            <div class="show">
                                @forelse($orders as $order)
                                    <a href="{!! route('front.dashboard.order.detail',$order->id) !!}">
                                        <div class="my-order-box">
                                            <div class="order-inner">
                                                <div class="order-no">
                                                    <div class="order-num">
                                                        @if($user->user_type == 'user')
                                                            <div
                                                                class="order-title">{{__('Order#')}} {!! $order->order_number !!}</div>
                                                        @else
                                                            <div
                                                                class="order-title">{{__('Order#')}} {!! $order->order_no !!}</div>
                                                        @endif
                                                    </div>
                                                    <div class="total">{{__('Total:')}}
                                                        <span>{!! getPrice($order->total,$currency)!!}</span>
                                                    </div>
                                                </div>
                                                <div class="my-orders-detail d-flex">
                                                    <div class="my-img-block">
                                                        <img
                                                            src="{{imageUrl(asset($order['orderItems'][0]->product->imagesWithTrashed->first()->file_path),95,76,90,3)}}"
                                                            alt="" class="img-fluid">
                                                    </div>
                                                    <div class="order-block">
                                                        <div class="order-time">{{__('Order Time:')}}
                                                            <span> {{ \Carbon\Carbon::createFromTimestamp($order->created_at)->format('F d, Y') }} {{__('at')}} {{ \Carbon\Carbon::createFromTimestamp($order->created_at)->format('H:i A') }}</span>
                                                        </div>
                                                        <div class="total-order">{{__('Total Products:')}}
                                                            <span>
                                                                {{$order->order_items_count}}
                                                            </span>
                                                        </div>
                                                        <div class="status">{{__('Status:')}} <span>
                                                                @if( $order->status == "pending")
                                                                    <span class="way">{{__('Pending')}}</span>
                                                                @elseif( $order->status == "completed")
                                                                    <span class="way"
                                                                          style="color: #000;">{{__('Completed')}}</span>
                                                                @elseif( $order->status == "accepted")
                                                                    <span class="way">{{__('Accepted')}}</span>
                                                                @elseif( $order->status == "in-progress")
                                                                    <span class="way">{{__('In Progress')}}</span>
                                                                @elseif( $order->status == "cancelled")
                                                                    <span class="way"
                                                                          style="color: #e40d0d;">{{__('Cancelled')}}</span>
                                                                @endif
                                                            </span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="col-sm-12 mt-2">
                                        <div class="alert alert-danger" role="alert">
                                            {{__('No order found')}}
                                        </div>
                                    </div>
                                @endforelse
                                <div class="mt-4">
                                    {{ $orders->withQueryString()->links('front.common.pagination', ['paginator' => $orders]) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="col-lg-8 col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="order-pagesss-selct">
                                <!-- select 2 -->

                                <div class="filter-order-page ml-auto mb-3">
                                    <div class="input-style phone-dropdown custom-drop-contact ">
                                        <!-- custom select -->
                                        <div class="custom-selct-icons-arow position-relative">
                                            <img src="{{asset('assets/front/img/arrow-down-2.png')}}"
                                                 class="img-fluid arrow-abs">
                                            <select class="js-example-basic-single" name="state">
                                                <option value="AL">filter</option>
                                                <a href="{!! route('front.dashboard.order.index',['status'=>'pending']) !!}">
                                                    <option value="">Pending</option>
                                                </a>
                                                <option value="AL">filter 3</option>

                                            </select>
                                        </div>

                                        <!-- end custom-select -->
                                    </div>

                                </div>


                                <!-- end select 2 -->

                            </div>

                            @forelse($orders as $order)

                                <a href="{!! route('front.dashboard.order.detail',auth()->user()->isUser() ? $order->id : $order->order_id) !!}">
                                    <div class="main-area-my-orders">
                                        <div class="inner-wrapper-orders">
                                            <div
                                                class="order-no-time-main d-flex align-items-center justify-content-between">
                                                <div class="wrapper-inner-time-no">
                                                    @if($user->user_type == 'user')
                                                        <div
                                                            class="order-number">{{__('Order#')}} {!! $order->order_number !!}</div>
                                                    @else
                                                        <div
                                                            class="order-number">{{__('Order#')}} {!! $order->order_no !!}</div>
                                                    @endif


                                                </div>
                                                <div class="right-two-boxes-main-od">
                                                    <h2 class="status-tittle-od">Total: <span
                                                            class="accpet-span">{!! getPrice($order->total,$currency)!!}</span>
                                                    </h2>


                                                </div>


                                            </div>

                                            <div
                                                class="bottom-conent-image-des-main d-flex align-items-center justify-content-between">
                                                <div class="inner-image-con d-flex align-items-center">
                                                    <div
                                                        class="image-my-orders d-flex align-items-center justify-content-center">
                                                        <img
                                                            src="{{imageUrl(asset($order['orderItems'][0]->product->imagesWithTrashed->first()->file_path),95,76,90,3)}}"
                                                            class="img-fluid" alt="image">

                                                    </div>

                                                    <div class="three-tittles-main-od">
                                                        <p class="tittle-sub-name">Order Time: <span
                                                                class="book-time-span">{{ \Carbon\Carbon::createFromTimestamp($order->created_at)->format('F d, Y') }} {{__('at')}} {{ \Carbon\Carbon::createFromTimestamp($order->created_at)->format('H:i A') }}</span>
                                                        </p>
                                                        <p class="tittle-sub-name">Total Products: <span
                                                                class="book-time-span">{{$order->order_items_count}}</span>
                                                        </p>
                                                        <p class="tittle-sub-name status-top-space-order">Status:
                                                            <span
                                                                class="book-time-span status-green">{{getOrderStatus($order->status)}}</span>
                                                            {{-- @if( $order->status == "pending")
                                                            <span class="book-time-span status-green">{{__('Pending')}}</span>
                                                        @elseif( $order->status == "completed")
                                                            <span class="book-time-span status-green"
                                                                  style="color: #000;">{{__('Completed')}}</span>
                                                                  @elseif( $order->status == "confirmed")
                                                                  <span class="book-time-span status-green"
                                                                        style="color: #000;">{{__('Confirmed')}}</span>
                                                        @elseif( $order->status == "accepted")
                                                            <span class="book-time-span status-green">{{__('Accepted')}}</span>
                                                        @elseif( $order->status == "in-progress")
                                                            <span class="book-time-span status-green">{{__('In Progress')}}</span>
                                                            @elseif( $order->status == "shipped")
                                                            <span class="book-time-span status-green">{{__('Shipped')}}</span>
                                                            @elseif( $order->status == "delivered")
                                                            <span class="book-time-span status-green">{{__('Delivered')}}</span>
                                                        @elseif( $order->status == "cancelled")
                                                            <span class="book-time-span status-green"
                                                                  style="color: #e40d0d;">{{__('Cancelled')}}</span>
                                                        @endif --}}
                                                        </p>

                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </a>
                            @empty
                                <div class="col-sm-12 mt-2">
                                    <div class="alert alert-danger" role="alert">
                                        {{__('No order found')}}
                                    </div>
                                </div>
                            @endforelse

                        </div>
                        {{ $orders->withQueryString()->links('front.common.pagination', ['paginator' => $orders]) }}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script>

    </script>
@endpush
