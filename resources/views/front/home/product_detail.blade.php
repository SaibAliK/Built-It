@extends('front.layouts.app')
@push('stylesheet-page-level')
    <style>
        .rate {
            display: flex;
            border: 0;
            flex-direction: row-reverse;
            overflow: hidden;
        }

        .rate > input {
            display: none;
        }

        .rate > label {
            float: right;
            color: #cccccc;
            margin-bottom: 0;
        }

        .rate > label:before {
            display: inline-block;
            cursor: pointer;
            font-family: FontAwesome;
            content: "\f005 ";
            margin: 0.25rem;
            font-size: 1.7rem;
            line-height: 1.6rem;
        }

        .rate .half {
            position: relative;
        }

        .rate .half:before {
            content: "\f089 ";
            position: absolute;
            padding-right: 0;
            left: 0;
        }

        input:checked ~ label,
        label:hover,
        label:hover ~ label {
            color: #FFC107;
        }


        input:checked + label:hover,
        input:checked ~ label:hover,
        input:checked ~ label:hover ~ label,
        label:hover ~ input:checked ~ label {
            color: #FFC107;
        }
    </style>
@endpush
@section('content')
    @include('front.common.breadcrumb')
    <section class="login-seca-all-page" style="background: {{ $colors['background_color'] }} !important;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-md-12 col-lg-6">
                            <div class="prod-gallery-box" dir="ltr">
                                <div class="cart-icon-start-parent">
                                    <div class="labe-starts">
                                        <h3 class="tittle">
                                            @if ($product->average_rating > 0)
                                                {{ number_format($product->average_rating, 1) }}
                                            @else
                                                0
                                            @endif
                                            <span class="star-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="21.541" height="21"
                                         viewBox="0 0 12.541 12">
                                      <path id="Path_48396" data-name="Path 48396"
                                            d="M8.531-10.078a.713.713,0,0,1,.41-.375.8.8,0,0,1,.539,0,.713.713,0,0,1,.41.375l1.523,3.094,3.422.492a.711.711,0,0,1,.48.281.783.783,0,0,1,.164.516.719.719,0,0,1-.223.492L12.773-2.789,13.359.633a.74.74,0,0,1-.105.527.688.688,0,0,1-.434.316.723.723,0,0,1-.539-.07L9.211-.187,6.141,1.406a.723.723,0,0,1-.539.07.688.688,0,0,1-.434-.316A.74.74,0,0,1,5.063.633l.586-3.422L3.164-5.2A.719.719,0,0,1,2.941-5.7a.783.783,0,0,1,.164-.516.711.711,0,0,1,.48-.281l3.422-.492Z"
                                            transform="translate(-2.941 10.5)" fill="#ff6a00"></path>
                                    </svg>
                                  </span>
                                        </h3>
                                    </div>
                                </div>

                                <div class="number-convert-img-parent">
                                    <h2 class="number-first">
                                        <span class="current">0</span>
                                        <span>/</span>
                                        <span class="number-second total"></span>
                                    </h2>
                                </div>

                                <!-- image large -->
                                <div class="slider-for">
                                    @if (count($product->images) > 0)
                                        @forelse($product->images as $item)
                                            <div class="product-detail-slider position-relative">
                                                <div class="img-box d-flex align-items-center justify-content-center">
                                                    @if (str_contains($item->imageType(), 'video'))
                                                        <video width="555" height="548" controls>
                                                            <source src="{{ $product->default_image }}"
                                                                    class="img-fluid"
                                                                    type="video/mp4">
                                                        </video>
                                                    @else
                                                        <img
                                                            src="{!! imageUrl($item->file_path, 555, 548, 100, 1) !!}"
                                                            id="product-primary-img" class="img-fluid"
                                                            alt="">
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                        @endforelse
                                    @endif

                                </div>
                                <!-- image small -->
                                <div class="prod-detail-nav" dir="ltr">
                                    <div class="arrow-left prev-d"><i class="fas fa-angle-left"></i></div>
                                    <div class="small-prod-thumd slider-nav">
                                        @if (count($product->images) > 0)
                                            @forelse($product->images as $item)
                                                <div class="thumb-img img-click slick-slide ">
                                                    @if (str_contains($item->imageType(), 'video'))
                                                        <video width="62" height="62" muted>
                                                            <source src="{{ $item->image_url_small }}"
                                                                    class="img-fluid"
                                                                    path="{!! $item->image_url_small !!}"
                                                                    type="video/mp4">
                                                        </video>
                                                    @else
                                                        <img alt="img"
                                                             src="{!! imageUrl($item->image_url_small, 62, 62, 100, 1) !!}"
                                                             path="{!! imageUrl($item->image_url_small, 555, 548, 100, 1) !!}"
                                                             class="img-fluid">
                                                    @endif
                                                </div>
                                            @empty
                                            @endforelse
                                        @endif
                                    </div>
                                    <div class="arrow-right next-d"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-6">
                            <div class="main-top-dt-pap ">
                                <div class="title-price d-flex align-items-baseline justify-content-between">
                                    <div class="title"> {{ translate($product->name) }}</div>
                                    <div class="product-dt-pagee-cut">
                                        <h2 class="price-tittle" style="color: {{ $colors['text_color'] }} !important;">
                                            <span class="cut-price-title">
                                                 @if ($product->offer_percentage > 0)
                                                    {{ getPrice($product->price, $currency) }}
                                                @endif
                                            </span>
                                            {{$currency}}
                                            @if($currency == 'AED')
                                                <input type="hidden" id="current_price"
                                                       value="{{ getPriceObject($product->discounted_price)->aed->amount}}">
                                                <span
                                                    class="update_price"> {{ getPriceObject($product->discounted_price)->aed->amount}}</span>
                                            @else
                                                <input type="hidden" id="current_price"
                                                       value=" {{getPriceObject($product->discounted_price)->usd->amount}}">
                                                <span
                                                    class="update_price"> {{getPriceObject($product->discounted_price)->usd->amount}}</span>
                                            @endif {{__('/')}} {{__('Meter')}}
                                        </h2>
                                    </div>
                                </div>
                                <h4 class="single-tittle-p-dt">{{translate($product->category->name)}}
                                </h4>
                                {{--   / {{translate($product->subCategory->name)}}--}}
                                <h4 class="supplier-name-dt-p"
                                    style="color: {{ $colors['text_color'] }} !important;">{{__('Supplier:')}} <span
                                        class="tittle-sup"
                                        style="color: {{ $colors['text_color'] }} !important;">{{ translate($product->store->supplier_name) ?? '' }}</span>
                                </h4>
                            </div>

                            <div class="detail-dec-p-pap">
                                <p class="des-p-dt"
                                   style="color: {{ $colors['text_color'] }} !important;"> {{ translate($product->description) }}</p>
                            </div>

                            <form action="{{ route('front.dashboard.cart.add') }}" id="cartForm" method="post">
                                @csrf
                                <div class="right-sidee-dt-main-area">
                                    <div class="counter-cartbtn d-flex align-items-center justify-content-between">
                                        <div class="select-size-amount-and-sele-dt">
                                        </div>
                                        <div class="counter-quantity d-flex">
                                            <button class="p-m minus">-</button>
                                            <input type="text" placeholder="1" value="1" id="cart_number" readonly
                                                   name="quantity"
                                                   class="Number cart-input text-center">
                                            <button class="p-m plus"><span class="plus-top-icons ">+</span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-product-btn-cart">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    @if (session()->get('area_id') && $product->store->is_CoveredArea(session()->get('area_id')))
                                        <button type="button"
                                                style="color: {{ $colors['text_color'] }} !important;"
                                                class="btn btn-primary w-100 {{ $product->quantity == 0 ? 'disable' : '' }}"
                                                id="cartBtn"{{ $product->quantity == 0 ? 'disable' : '' }}>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25.653" height="21.861"
                                                 viewBox="0 0 25.653 21.861">
                                                <path id="Path_48392" data-name="Path 48392"
                                                      d="M2.982,8.964a1.91,1.91,0,0,0-1.9-1.9,1.9,1.9,0,0,0,0,3.793A1.91,1.91,0,0,0,2.982,8.964Zm13.275,0a1.9,1.9,0,1,0-1.9,1.9A1.91,1.91,0,0,0,16.257,8.964Zm1.9-16.119a.955.955,0,0,0-.948-.948H-.589C-.737-8.815-.766-10-1.759-10H-5.552a.955.955,0,0,0-.948.948.955.955,0,0,0,.948.948h3.022L.093,4.09a7.631,7.631,0,0,0-.9,2.03.955.955,0,0,0,.948.948H15.309a.948.948,0,1,0,0-1.9H1.678a2.244,2.244,0,0,0,.356-.948,4.924,4.924,0,0,0-.193-1.037L17.309,1.378A.971.971,0,0,0,18.153.43Z"
                                                      transform="translate(7 10.5)" fill="#fff" stroke="rgba(0,0,0,0)"
                                                      stroke-width="1"/>
                                            </svg>
                                            {{__('add to cart')}}</button>
                                    @else
                                        <button type="button"
                                                style="color: {{ $colors['text_color'] }} !important;"
                                                class="btn btn-primary w-100 {{ $product->quantity == 0 ? 'disable' : '' }}"
                                                data-toggle="modal"
                                                data-target="#supplier-areas">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="25.653" height="21.861"
                                                 viewBox="0 0 25.653 21.861">
                                                <path id="Path_48392" data-name="Path 48392"
                                                      d="M2.982,8.964a1.91,1.91,0,0,0-1.9-1.9,1.9,1.9,0,0,0,0,3.793A1.91,1.91,0,0,0,2.982,8.964Zm13.275,0a1.9,1.9,0,1,0-1.9,1.9A1.91,1.91,0,0,0,16.257,8.964Zm1.9-16.119a.955.955,0,0,0-.948-.948H-.589C-.737-8.815-.766-10-1.759-10H-5.552a.955.955,0,0,0-.948.948.955.955,0,0,0,.948.948h3.022L.093,4.09a7.631,7.631,0,0,0-.9,2.03.955.955,0,0,0,.948.948H15.309a.948.948,0,1,0,0-1.9H1.678a2.244,2.244,0,0,0,.356-.948,4.924,4.924,0,0,0-.193-1.037L17.309,1.378A.971.971,0,0,0,18.153.43Z"
                                                      transform="translate(7 10.5)" fill="#fff" stroke="rgba(0,0,0,0)"
                                                      stroke-width="1"/>
                                            </svg>
                                            {{__('add to cart')}}</button>
                                            <div class="modal fade custom-modal-pa-all" id="supplier-areas" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                                <div class="modal-header border-0">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Select any Area</h5>
                                                
                                                </div>
                                                <div class="modal-body px-0">
                                                <div class="slect-on-homeee w-100 mx-0">
                                                <select class="selectpicker select2" name="header_area"
                                                        onchange="getval(this);"
                                                            id="header-categories-select-2 ">
                                                            <option value="">{{__('Location')}}</option>
                                                            @forelse($product->store->coveredAreas as $area)
                                                            <option value="{{ $area->id }}">
                                                            {{ translate($area->name) }}</option>
                                                            @empty
                                                            <option value="" disabled
                                                            selected>{{__('Select Area')}}</option>
                                                            @endforelse
                                                </select>

                                                </div>
                                               
                                                
                                                </div>
                                                <div class="modal-footer">
                                                <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                                                <!-- <button type="button" class="btn btn-primary w-100">Cancel Order</button> -->
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="rating-product-detail-parent">
                        <div class="row">
                            <div class="col-md-12">
                                <div
                                    class="rating-heading-button-dt-p mt-60 mb-15 d-flex align-items-center justify-content-between">
                                    <h2 class="tittle"
                                        style="color: {{ $colors['text_color'] }} !important;">{{__('Ratings and Reviews')}}</h2>
                                    <div class="modal-button-rating">
                                        @if (Auth::check())
                                            @if (Auth::user()->isUser() && isset($product->orderDetailItems[0]))
                                                <a href="" class="btn btn-black w-100" data-toggle="modal"
                                                   data-target="#exampleModalCenter-1">{{__('Write a review')}}</a>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                @if (count($product->reviews) > 0)
                                    @forelse($product->reviews as $key => $stores)
                                        <div class="review-list-box rating-page-cls d-flex">
                                            <div class="review-img">
                                                @if (isset($stores->user))
                                                    <img
                                                        src="{!! imageUrl(url($stores->user->image_url), 50, 50, 100, 1) !!}"
                                                        class="img-fluid" alt="">
                                                @endif
                                            </div>
                                            <div class="review-detail-box">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div class="tittle-left-starts-icons d-flex align-items-center">
                                                        <div class="title">{{ $stores->user->user_name }}</div>
                                                        <div class="starts-tittle-icon">
                                                            <span
                                                                class="tittle">{{ getStarRating($stores->rating) ?? '0' }}</span>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14.631"
                                                                 height="14"
                                                                 viewBox="0 0 14.631 14">
                                                                <path id="Path_48396" data-name="Path 48396"
                                                                      d="M9.953-11.758a.832.832,0,0,1,.479-.437.931.931,0,0,1,.629,0,.832.832,0,0,1,.479.438l1.777,3.609,3.992.574a.83.83,0,0,1,.561.328.913.913,0,0,1,.191.6.839.839,0,0,1-.26.574L14.9-3.254,15.586.738a.864.864,0,0,1-.123.615.8.8,0,0,1-.506.369.843.843,0,0,1-.629-.082L10.746-.219,7.164,1.641a.843.843,0,0,1-.629.082.8.8,0,0,1-.506-.369A.864.864,0,0,1,5.906.738L6.59-3.254,3.691-6.07a.839.839,0,0,1-.26-.574.913.913,0,0,1,.191-.6.83.83,0,0,1,.561-.328l3.992-.574Z"
                                                                      transform="translate(-3.431 12.25)"
                                                                      fill="#ff6a00"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="reviw-time">{{ Carbon\Carbon::parse($stores->updated_at)->diffForHumans() }}</div>
                                                </div>

                                                <div class="des clearfix">
                                                    {!! nl2br($stores->review) !!}
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse
                                @else
                                    <div class="review mt-2">
                                        <div class="alert alert-danger" role="alert">
                                            {{ __('No Reviews') }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (Auth::check())
        @if (Auth::user()->isUser() && isset($product->orderDetailItems[0]))
            <div class="modal fade custom-modal-pa-all" id="exampleModalCenter-1" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form
                            method="post" id="reviewFormforProduct"
                            action="{{ route('front.dashboard.store.create-reviews') }}">
                            @csrf
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <div class="modal-header border-0">
                                <h5 class="modal-title mb-0 pb-0"
                                    id="exampleModalLongTitle">{{__('Write a review')}}</h5>
                            </div>
                            <div class="modal-body px-0">
                                <div class="star-box-raing-det w-100">
                                    <div class="rating-add-mt d-flex justify-content-center">
                                        <fieldset class="rating float-left">
                                            <input type="radio" id="" name="rating" value="5" ngmodel=""><label
                                                class="full xyz m-0"
                                                for="star5"
                                                title="Awesome - 5 stars"></label>
                                            <input type="radio" id="" name="rating" value="4.5" ngmodel=""><label
                                                class="half xyz m-0" for="star4half"
                                                title="Pretty good - 4.5 stars"></label>
                                            <input type="radio" id="" name="rating" value="4" ngmodel=""><label
                                                class="full xyz m-0"
                                                for="star4"
                                                title="Pretty good - 4 stars"></label>
                                            <input type="radio" id="" name="rating" value="3.5" ngmodel=""><label
                                                class="half xyz m-0" for="star3half" title="Meh - 3.5 stars"></label>
                                            <input type="radio" id="" name="rating" value="3" ngmodel=""><label
                                                class="full xyz m-0"
                                                for="star3"
                                                title="Meh - 3 stars"></label>
                                            <input type="radio" id="" name="rating" value="2.5" ngmodel=""><label
                                                class="half xyz m-0" for="star2half"
                                                title="Kinda bad - 2.5 stars"></label>
                                            <input type="radio" id="" name="rating" value="2" ngmodel=""><label
                                                class="full xyz m-0"
                                                for="star2"
                                                title="Kinda bad - 2 stars"></label>
                                            <input type="radio" id="" name="rating" value="1.5" ngmodel=""><label
                                                class="half xyz m-0" for="star1half" title="Meh - 1.5 stars"></label>
                                            <input type="radio" id="" name="rating" value="1" ngmodel=""><label
                                                class="full xyz m-0"
                                                for="star1"
                                                title="Sucks big time - 1 star"></label>
                                            <input type="radio" id="" name="rating" value="0.5" ngmodel=""><label
                                                class="half xyz m-0" for="starhalf"
                                                title="Sucks big time - 0.5 stars"></label>
                                        </fieldset>
                                    </div>
                                    <div class="add-review-box-acd">
                                        <div class="input-style">
                                            <div class="wrapper-input">
                                        <span class="icon-front-input">
                        <svg xmlns="http://www.w3.org/2000/svg" width="21" height="18.375" viewBox="0 0 21 18.375">
                            <path id="Path_48396" data-name="Path 48396"
                                  d="M0-17.062a12.049,12.049,0,0,1,5.271,1.148,9.871,9.871,0,0,1,3.814,3.1A7.132,7.132,0,0,1,10.5-8.531,7.132,7.132,0,0,1,9.085-4.245a9.871,9.871,0,0,1-3.814,3.1A12.049,12.049,0,0,1,0,0,12.468,12.468,0,0,1-4.389-.779,11.219,11.219,0,0,1-6.645.574a8.874,8.874,0,0,1-3.527.738.291.291,0,0,1-.287-.205.357.357,0,0,1,.041-.369A9.4,9.4,0,0,0-9.475-.451,8.741,8.741,0,0,0-8.162-3.158a8.906,8.906,0,0,1-1.723-2.5A6.858,6.858,0,0,1-10.5-8.531a7.132,7.132,0,0,1,1.415-4.286,9.871,9.871,0,0,1,3.814-3.1A12.048,12.048,0,0,1,0-17.062Z"
                                  transform="translate(10.5 17.063)" fill="#45cea2"></path>
                          </svg>
                      </span>
                                                <div class="review-text"></div>
                                                <input type="text" name="review" class="ctm-input border-full-cst"
                                                       required
                                                       placeholder="{{__('Comment')}}">
                                                <input type="hidden" name="slug"
                                                       value="{{ $product->slug ?? '' }}">
                                                <input type="hidden" name="store_id"
                                                       value="{!! $product->store->id !!}">
                                                <input type="hidden" name="product_id"
                                                       value="{!! $product->id !!}">
                                                <input type="hidden" name="order_detail_items_id"
                                                       value="{!! $product->orderDetailItems[0]->id !!}">
                                                <input type="hidden" name="user_id"
                                                       value="{{ Auth::user()->id }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer mt-25">
                                <button type="submit" class="btn btn-primary w-100"
                                        id="ReviewButtonSubmit">{{__('Submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    @endif


@endsection

@push('scripts')

    <script>
        $(document).ready(function () {
            $('#reviewFormforProduct').validate({
                ignore: '',
                rules: {
                    rating: {
                        required: true
                    }
                },
                errorPlacement: function (error, element) {
                    console.log(element.attr("name"));
                    if (element.attr("name") == "rating") {
                        error.insertAfter(element.parent().parent().parent());
                    } else if (element.attr("name") == "review") {
                        $(".review-text").html(error);
                    } else {
                        error.insertAfter(element.parent());

                    }
                }
            });
            if (window.location.href.indexOf("add-review") > -1) {
                $(".rating-btn").click();
                $('html, body').animate({
                    scrollTop: $(".rating-detail-rea").offset().top
                }, 1500);
            }
        });

        $(document).on('click', '.minus', function (e) {
            e.preventDefault();
            let value = $(this).closest('div').find('.text-center');
            let ConvertToInt = parseInt(value.val());
            if (ConvertToInt >= 2) {
                $(this).closest('div').find('.text-center').val(ConvertToInt - 1);

                let qty = $('#cart_number').val();
                let current_price = $("#current_price").val();
                let new_price = qty * current_price;
                $('.update_price').text(parseFloat(new_price).toFixed(2));
            }
        });

        $(document).on('click', '.plus', function (e) {
            e.preventDefault();
            let value = $(this).closest('div').find('.text-center');
            let ConvertToInt = parseInt(value.val());
            $(this).closest('div').find('.text-center').val(ConvertToInt + 1);

            let qty = $('#cart_number').val();
            let current_price = $("#current_price").val();
            let new_price = qty * current_price;
            $('.update_price').text(parseFloat(new_price).toFixed(2));
        });

        $(document).on('click', '#cartBtn', function (e) {
            e.preventDefault();
            $("#cartForm").submit();
        });

        // $(document).on('click', '#ReviewButtonSubmit', function (e) {
        //     e.preventDefault();
        //     $("#reviewFormforProduct").submit();
        // });
    </script>
@endPush
