@if($user->isUser())
    {{-- for User Action--}}
    @if($order->status == 'pending')
        <div class="subscription suppiler-product articles-detail order-detail-modal">
            <button type="button" class="secondary-btn btnss cancelOrder">
                {{__('Cancel Order')}}
            </button>
            <!-- Cancel for User and Supplier Modal -->
            <div class="modal fade" id="cancelOrder" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content article-modal cancel-modal">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">{{__('Cancel Order?')}}</h5>
                            <button type="button" class="close " data-dismiss="modal"
                                    aria-label="Close">
                        <span aria-hidden="true">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="17.945" height="17.945"
                                                 viewBox="0 0 17.945 17.945">
                                            <path id="Icon_metro-cross"
                                                  data-name="Icon metro-cross"
                                                  d="M20.353,16.345h0L14.908,10.9l5.444-5.444h0a.562.562,0,0,0,0-.793L17.781,2.092a.562.562,0,0,0-.793,0h0L11.544,7.536,6.1,2.092h0a.562.562,0,0,0-.793,0L2.735,4.664a.562.562,0,0,0,0,.793h0L8.179,10.9,2.735,16.345h0a.562.562,0,0,0,0,.793L5.306,19.71a.562.562,0,0,0,.793,0h0l5.444-5.444,5.444,5.444h0a.562.562,0,0,0,.793,0l2.572-2.572a.562.562,0,0,0,0-.793Z"
                                                  transform="translate(-2.571 -1.928)"
                                                  fill="#999"/>
                                        </svg>
                                        </span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="rzn">{{__('Are you sure you want to cancel the current order')}}
                                {{__('Order#')}}@if($user->isUser()) {!! $order->order_number !!} @else {!! $order->order_no !!} @endif {{__('?')}}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="article-btn d-flex align-items-center">
                                <button type="button" id="no_btn_in_cancel_modal" class="btn secondary-btn"
                                        data-dismiss="modal">{{__('No')}}
                                </button>
                                <button type="button" class="primary-btn btn"
                                        id="yes_btn_cancel_modal">
                                    {{__('Yes')}}
                                </button>
                                <!--second modal-->
                                <div class="modal fade" id="cancel_reason_modal" tabindex="-1"
                                     role="dialog"
                                     aria-labelledby="exampleModalCenterTitle-2"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered"
                                         role="document">
                                        <div class="modal-content article-modal cancels-modal">
                                            <form action="{{route('front.dashboard.order.cancel')}}"
                                                  method="post"
                                                  id="cancel_form_submit">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="exampleModalLongTitle">
                                                        {{__('Cancellation Reason')}}</h5>
                                                    <button type="button" class="close"
                                                            data-dismiss="modal"
                                                            aria-label="Close">
                                                <span aria-hidden="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         width="17.945" height="17.945"
                                                         viewBox="0 0 17.945 17.945">
                                                    <path id="Icon_metro-cross"
                                                          data-name="Icon metro-cross"
                                                          d="M20.353,16.345h0L14.908,10.9l5.444-5.444h0a.562.562,0,0,0,0-.793L17.781,2.092a.562.562,0,0,0-.793,0h0L11.544,7.536,6.1,2.092h0a.562.562,0,0,0-.793,0L2.735,4.664a.562.562,0,0,0,0,.793h0L8.179,10.9,2.735,16.345h0a.562.562,0,0,0,0,.793L5.306,19.71a.562.562,0,0,0,.793,0h0l5.444-5.444,5.444,5.444h0a.562.562,0,0,0,.793,0l2.572-2.572a.562.562,0,0,0,0-.793Z"
                                                          transform="translate(-2.571 -1.928)"
                                                          fill="#999"/>
                                                </svg>
                                                </span>
                                                    </button>
                                                </div>
                                                <div class="modal-body subscription">
                                                    @forelse($reasons as $item)
                                                        <label class="contain">{{translate($item->reason)}}
                                                            <input type="radio"
                                                                   value="{{json_encode($item->reason)}}"
                                                                   id="radio03-{{$item->id}}"
                                                                   name="cancel_reason" required>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        @include('front.common.alert', ['input' => 'cancel_reason'])
                                                    @empty
                                                        <h2>{{__('No Reason Found')}}</h2>
                                                    @endforelse
                                                </div>
                                                <input type="hidden" value="{{$order->id ?? ''}}" name="id">
                                                @if($user->isUser())
                                                    <input type="hidden" value="{{$order->id}}"
                                                           name="id">
                                                    <input type="hidden"
                                                           value="{{$order->orderDetails[0]->store_id ?? ''}}"
                                                           name="store_id">
                                                @else
                                                    <input type="hidden" value="{{$order->order_id ?? ''}}"
                                                           name="order_id">
                                                    <input type="hidden" value="{{$order->store_id ?? ''}}"
                                                           name="store_id">
                                                    <input type="hidden"
                                                           value="{{$order->order->user_id ?? ''}}"
                                                           name="orderUserId"
                                                           class="ctm-input">
                                                @endif

                                                <div class="modal-footer">
                                                    <div
                                                        class="article-btn d-flex align-items-center">
                                                        <button type="button" id="cancel_submit"
                                                                class="btn primary-btn">{{__('Cancel Order')}}
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!--end-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($order->status == 'completed')
        <div class="all-product order-com">
            <div class="sortes">
                <div class="dropdown pro-drop btn-sorting">
                    <button class="btn btn-secondary dropdown-toggle btn-sort" type="button"
                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                        {{__('Invoice')}}
                        <div class="down">
                            <img src="{{asset('assets/front/img/down-arr.png')}}" alt="" class="img-fluid">
                        </div>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <div class="active">
                            <a href="{{ route('front.dashboard.order.print.pdf', $order->id) }}" target="_blank"
                               class="dropdown-item">{{__('View Invoice')}}
                            </a>
                        </div>
                        <div class="active">
                            <a class="dropdown-item border-none" data-order_id="{{$order->id ?? ''}}"
                               id="sendIn">{{__('Send Invoice')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@else
    @if($order->status == "pending")
        <div class="subscription suppiler-product articles-detail order-detail-modal">
            <div class="sup-order-detail ">
                <button class="primary-btn" data-toggle="modal"
                        data-target="#acceptOrder">
                    {{__('Accept')}}
                </button>
                <button type="button" class="secondary-btn btnss cancelOrder" data-toggle="modal"
                        data-target="#exampleModalCenter">
                    {{__('Cancel Order')}}
                </button>
            </div>
            <!-- Cancel for User and Supplier Modal -->
            <div class="modal fade" id="cancelOrder" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content article-modal cancel-modal">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">{{__('Cancel Order?')}}</h5>
                            <button type="button" class="close " data-dismiss="modal"
                                    aria-label="Close">
                        <span aria-hidden="true">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="17.945" height="17.945"
                                                 viewBox="0 0 17.945 17.945">
                                            <path id="Icon_metro-cross"
                                                  data-name="Icon metro-cross"
                                                  d="M20.353,16.345h0L14.908,10.9l5.444-5.444h0a.562.562,0,0,0,0-.793L17.781,2.092a.562.562,0,0,0-.793,0h0L11.544,7.536,6.1,2.092h0a.562.562,0,0,0-.793,0L2.735,4.664a.562.562,0,0,0,0,.793h0L8.179,10.9,2.735,16.345h0a.562.562,0,0,0,0,.793L5.306,19.71a.562.562,0,0,0,.793,0h0l5.444-5.444,5.444,5.444h0a.562.562,0,0,0,.793,0l2.572-2.572a.562.562,0,0,0,0-.793Z"
                                                  transform="translate(-2.571 -1.928)"
                                                  fill="#999"/>
                                        </svg>
                                        </span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="rzn">{{__('Are you sure you want to cancel the current order')}}
                                {{__('Order#')}}@if($user->isUser()) {!! $order->order_number !!} @else {!! $order->order_no !!} @endif {{__('?')}}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="article-btn d-flex align-items-center">
                                <button type="button" id="no_btn_in_cancel_modal" class="btn secondary-btn"
                                        data-dismiss="modal">{{__('No')}}
                                </button>
                                <button type="button" class="primary-btn btn"
                                        id="yes_btn_cancel_modal">
                                    {{__('Yes')}}
                                </button>
                                <!--second modal-->
                                <div class="modal fade" id="cancel_reason_modal" tabindex="-1"
                                     role="dialog"
                                     aria-labelledby="exampleModalCenterTitle-2"
                                     aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered"
                                         role="document">
                                        <div class="modal-content article-modal cancels-modal">
                                            <form action="{{route('front.dashboard.order.cancel')}}"
                                                  method="post"
                                                  id="cancel_form_submit">
                                                @csrf
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="exampleModalLongTitle">
                                                        {{__('Cancellation Reason')}}</h5>
                                                    <button type="button" class="close"
                                                            data-dismiss="modal"
                                                            aria-label="Close">
                                                <span aria-hidden="true">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                         width="17.945" height="17.945"
                                                         viewBox="0 0 17.945 17.945">
                                                    <path id="Icon_metro-cross"
                                                          data-name="Icon metro-cross"
                                                          d="M20.353,16.345h0L14.908,10.9l5.444-5.444h0a.562.562,0,0,0,0-.793L17.781,2.092a.562.562,0,0,0-.793,0h0L11.544,7.536,6.1,2.092h0a.562.562,0,0,0-.793,0L2.735,4.664a.562.562,0,0,0,0,.793h0L8.179,10.9,2.735,16.345h0a.562.562,0,0,0,0,.793L5.306,19.71a.562.562,0,0,0,.793,0h0l5.444-5.444,5.444,5.444h0a.562.562,0,0,0,.793,0l2.572-2.572a.562.562,0,0,0,0-.793Z"
                                                          transform="translate(-2.571 -1.928)"
                                                          fill="#999"/>
                                                </svg>
                                                </span>
                                                    </button>
                                                </div>
                                                <div class="modal-body subscription">
                                                    @forelse($reasons as $item)
                                                        <label class="contain">{{translate($item->reason)}}
                                                            <input type="radio"
                                                                   value="{{json_encode($item->reason)}}"
                                                                   id="radio03-{{$item->id}}"
                                                                   name="cancel_reason" required>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        @include('front.common.alert', ['input' => 'cancel_reason'])
                                                    @empty
                                                        <h2>{{__('No Reason Found')}}</h2>
                                                    @endforelse
                                                </div>
                                                <input type="hidden" value="{{$order->id ?? ''}}" name="id">
                                                @if($user->isUser())
                                                    <input type="hidden" value="{{$order->id}}"
                                                           name="id">
                                                    <input type="hidden"
                                                           value="{{$order->orderDetails[0]->store_id ?? ''}}"
                                                           name="store_id">
                                                @else
                                                    <input type="hidden" value="{{$order->order_id ?? ''}}"
                                                           name="order_id">
                                                    <input type="hidden" value="{{$order->store_id ?? ''}}"
                                                           name="store_id">
                                                    <input type="hidden"
                                                           value="{{$order->order->user_id ?? ''}}"
                                                           name="orderUserId"
                                                           class="ctm-input">
                                                @endif

                                                <div class="modal-footer">
                                                    <div
                                                        class="article-btn d-flex align-items-center">
                                                        <button type="button" id="cancel_submit"
                                                                class="btn primary-btn">{{__('Cancel Order')}}
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <!--end-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="acceptOrder" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content article-modal cancel-modal">
                        <form action="{!! route('front.dashboard.order.accept') !!}" method="post">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">{{__('Accept Order?')}}</h5>
                                <button type="button" class="close " data-dismiss="modal"
                                        aria-label="Close">
                        <span aria-hidden="true">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 width="17.945" height="17.945"
                                                 viewBox="0 0 17.945 17.945">
                                            <path id="Icon_metro-cross"
                                                  data-name="Icon metro-cross"
                                                  d="M20.353,16.345h0L14.908,10.9l5.444-5.444h0a.562.562,0,0,0,0-.793L17.781,2.092a.562.562,0,0,0-.793,0h0L11.544,7.536,6.1,2.092h0a.562.562,0,0,0-.793,0L2.735,4.664a.562.562,0,0,0,0,.793h0L8.179,10.9,2.735,16.345h0a.562.562,0,0,0,0,.793L5.306,19.71a.562.562,0,0,0,.793,0h0l5.444-5.444,5.444,5.444h0a.562.562,0,0,0,.793,0l2.572-2.572a.562.562,0,0,0,0-.793Z"
                                                  transform="translate(-2.571 -1.928)"
                                                  fill="#999"/>
                                        </svg>
                                        </span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="rzn">{{__('Are you sure you want to accept the current order')}}
                                    {{__('Order#')}}@if($user->isUser()) {!! $order->order_number !!} @else {!! $order->order_no !!} @endif {{__('?')}}
                                </div>
                            </div>
                            <input type="hidden" value="{{$order->id}}" name="id">
                            <input type="hidden" value="{{$order->order->user_id}}" name="orderUserId">
                            <div class="modal-footer">
                                <div class="article-btn d-flex align-items-center">
                                    <button type="button" class="btn secondary-btn"
                                            data-dismiss="modal">{{__('No')}}
                                    </button>
                                    <button type="submit" class="primary-btn btn">
                                        {{__('Yes')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($order->status == "accepted")
        <div class="subscription suppiler-product articles-detail order-detail-modal">
            <div class="sup-order-detail ">
                <button type="button" class="primary-btn btnss" data-toggle="modal"
                        data-target="#orderComplete">
                    {{__('Mark as Complete')}}
                </button>
            </div>
            {{--  mark Order Complete--}}
            <div class="modal fade" id="orderComplete" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content article-modal cancel-modal">
                        <form action="{!! route('front.dashboard.order.complete') !!}" method="post">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">{{__('Mark as Complete?')}}</h5>
                                <button type="button" class="close " data-dismiss="modal"
                                        aria-label="Close">
                                         <span aria-hidden="true">
                                             <svg xmlns="http://www.w3.org/2000/svg"
                                                  width="17.945" height="17.945"
                                                  viewBox="0 0 17.945 17.945">
                                             <path id="Icon_metro-cross"
                                                   data-name="Icon metro-cross"
                                                   d="M20.353,16.345h0L14.908,10.9l5.444-5.444h0a.562.562,0,0,0,0-.793L17.781,2.092a.562.562,0,0,0-.793,0h0L11.544,7.536,6.1,2.092h0a.562.562,0,0,0-.793,0L2.735,4.664a.562.562,0,0,0,0,.793h0L8.179,10.9,2.735,16.345h0a.562.562,0,0,0,0,.793L5.306,19.71a.562.562,0,0,0,.793,0h0l5.444-5.444,5.444,5.444h0a.562.562,0,0,0,.793,0l2.572-2.572a.562.562,0,0,0,0-.793Z"
                                                   transform="translate(-2.571 -1.928)"
                                                   fill="#999"/>
                                         </svg>
                                         </span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="rzn">{{__('Are you sure you want to mark the selected order')}}
                                    {{__('Order#')}}@if($user->isUser()) {!! $order->order_number !!} @else {!! $order->order_no !!} @endif {{__('?')}} {{__('as complete?')}}
                                </div>
                            </div>
                            <input type="hidden" value="{{$order->id ?? ''}}" name="id">
                            <input type="hidden" value="{{$order->total->sar->amount ?? ''}}" name="total">
                            <input type="hidden" value="{{$order->store_id ?? ''}}" name="store_id">
                            <input type="hidden" value="{{$order->order->user_id ?? ''}}" name="orderUserId">
                            <input type="hidden" value="{{$order->order_id}}" name="orderId">
                            <div class="modal-footer">
                                <div class="article-btn d-flex align-items-center">
                                    <button type="button" class="btn secondary-btn"
                                            data-dismiss="modal">{{__('No')}}
                                    </button>
                                    <button type="submit" class="btn primary-btn">{{__('Yes')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endif




