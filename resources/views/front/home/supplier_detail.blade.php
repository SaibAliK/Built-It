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

    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="suppleir-detail-page-image">
                        <img src="{{imageUrl($supplier->image_url,555,256,90,1)}}" class="img-fluid" alt="image">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="supplier-dt-page-right-content">
                        <div class="top-about-tittle-st">
                            <h3 class="tittle">{{translate($supplier->supplier_name)}}</h3>
                            <div class="star-rating-area">
                                <div class="ratilike ng-binding pl-0">
                                    @if($supplier->rating > 0)
                                        {{ number_format($supplier->rating,1) }}
                                    @else
                                        0
                                    @endif
                                </div>
                                <div class="rating-static clearfix" rel="{{round(getStarRating($supplier->rating),1)}}">
                                    <label class="full" title="{{__('Awesome - 5 stars')}}"></label>
                                    <label class="half"
                                           title="{{__('Pretty good - 4.5 stars')}}"></label>
                                    <label class="full" title="{{__('Pretty good - 4 stars')}}"></label>
                                    <label class="half" title="{{__('Good - 3.5 stars')}}"></label>
                                    <label class="full" title="{{__('Good - 3 stars')}}"></label>
                                    <label class="half" title="{{__('Average - 2.5 stars')}}"></label>
                                    <label class="full" title="{{__('Average - 2 stars')}}"></label>
                                    <label class="half"
                                           title="{{__('You can do better - 1.5 stars')}}"></label>
                                    <label class="full"
                                           title="{{__('You can do better - 1 star')}}"></label>
                                    <label class="half"
                                           title="{{__('You can do better - 0.5 stars')}}"></label>
                                </div>
                            </div>
                        </div>

                        <div class="supplier-decs-p-st">
                            <p>{{translate($supplier->about)}}</p>
                        </div>

                        <div class="info-icon-content-set">
                            <li class="list-item-inf">
                            <span class="icon-cwidth">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10.5" height="14" viewBox="0 0 10.5 14">
                                    <path id="Path_48396" data-name="Path 48396"
                                          d="M4.7,1.477a.627.627,0,0,0,.547.273A.627.627,0,0,0,5.8,1.477L7.629-1.148Q9-3.117,9.434-3.8a7.7,7.7,0,0,0,.848-1.654A4.977,4.977,0,0,0,10.5-7a5.067,5.067,0,0,0-.711-2.625,5.35,5.35,0,0,0-1.914-1.914A5.067,5.067,0,0,0,5.25-12.25a5.067,5.067,0,0,0-2.625.711A5.35,5.35,0,0,0,.711-9.625,5.067,5.067,0,0,0,0-7,4.977,4.977,0,0,0,.219-5.455,7.7,7.7,0,0,0,1.066-3.8q.438.684,1.8,2.652Q3.992.438,4.7,1.477ZM5.25-4.812a2.106,2.106,0,0,1-1.545-.643A2.106,2.106,0,0,1,3.063-7a2.106,2.106,0,0,1,.643-1.545A2.106,2.106,0,0,1,5.25-9.187a2.106,2.106,0,0,1,1.545.643A2.106,2.106,0,0,1,7.438-7a2.106,2.106,0,0,1-.643,1.545A2.106,2.106,0,0,1,5.25-4.812Z"
                                          transform="translate(0 12.25)" fill="#45cea2"/>
                                  </svg>
                            </span>
                                {{$supplier->address}}
                            </li>

                            <li class="list-item-inf">
                            <span class="icon-cwidth">
                                <svg id="Component_5_1" data-name="Component 5 – 1" xmlns="http://www.w3.org/2000/svg"
                                     width="10" height="14.525" viewBox="0 0 10 14.525">
                                    <path id="Path_29" data-name="Path 29"
                                          d="M70.082,10.893a.352.352,0,0,1-.3-.161s0-.007-.007-.011a7.182,7.182,0,0,1-.782-3.459A7.177,7.177,0,0,1,69.774,3.8l.007-.011a.351.351,0,0,1,.3-.161V0c-2.005,0-3.631,3.251-3.631,7.262s1.626,7.262,3.631,7.262Z"
                                          transform="translate(-66.451)" fill="#45cea2"/>
                                    <path id="Path_30" data-name="Path 30"
                                          d="M187.661.436A.436.436,0,0,0,187.225,0h-1.307V3.631h1.307a.436.436,0,0,0,.436-.436Z"
                                          transform="translate(-181.851)" fill="#45cea2"/>
                                    <path id="Path_31" data-name="Path 31"
                                          d="M187.661,320.436a.436.436,0,0,0-.436-.436h-1.307v3.631h1.307a.436.436,0,0,0,.436-.436Z"
                                          transform="translate(-181.851 -309.106)" fill="#45cea2"/>
                                    <path id="Path_32" data-name="Path 32"
                                          d="M237.663,176.049l-.545-.545a1.156,1.156,0,0,0,0-1.634l.545-.545a1.926,1.926,0,0,1,0,2.724Z"
                                          transform="translate(-231.308 -167.425)" fill="#45cea2"/>
                                    <path id="Path_33" data-name="Path 33"
                                          d="M269.637,146.247l-.545-.545a2.692,2.692,0,0,0,0-3.811l.545-.545a3.462,3.462,0,0,1,0,4.9Z"
                                          transform="translate(-262.194 -136.534)" fill="#45cea2"/>
                                    <path id="Path_34" data-name="Path 34"
                                          d="M301.629,116.434l-.545-.545a4.23,4.23,0,0,0,0-5.989l.545-.545a5,5,0,0,1,0,7.079Z"
                                          transform="translate(-293.097 -105.633)" fill="#45cea2"/>
                                  </svg>
                            </span>
                                {{$supplier->phone}}
                            </li>

                            <li class="list-item-inf">
                            <span class="icon-cwidth">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12.883" height="9.662"
                                     viewBox="0 0 12.883 9.662">
                                    <path id="Path_50" data-name="Path 50"
                                          d="M11.272,1.61H1.61L6.441,5.636ZM0,1.61A1.615,1.615,0,0,1,1.61,0h9.662a1.615,1.615,0,0,1,1.61,1.61V8.052a1.615,1.615,0,0,1-1.61,1.61H1.61A1.615,1.615,0,0,1,0,8.052Z"
                                          fill="#45cea2" fill-rule="evenodd"/>
                                  </svg>
                            </span>
                                {{$supplier->email}}
                            </li>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mx-auto">
                    <ul class="nav nav-pills mb-30 mt-15 register-tabs pro-dt-profilee-tabs">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->has('products')  ? 'active ' : ''  }}"
                               id="products">{{__('products')}}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->has('reviews')  ? 'active' : ''  }}"
                               id="reviews">{{__('Reviews')}}</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row">

                <div class="col-12">
                    <div class="tab-content">
                        <div class="tab-pane fade show {{ request()->has('products')  ? 'active' : ''  }}">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="supplier-detail-page-filter">
                                        <div class="filter-order-page">
                                            <div class="input-style phone-dropdown custom-drop-contact">
                                                <div class="custom-selct-icons-arow position-relative">
                                                    <img alt="" src="{{asset('assets/front/img/arrow-down-2.png')}}"
                                                         class="img-fluid arrow-abs">
                                                    <select class="js-example-basic-single filter" name="state">
                                                        <option value="" selected>{{__('Select Filter')}}</option>
                                                        <option value="all" class="filter">{{__('All')}}</option>
                                                        <option value="featured"
                                                                class="filter">{{__('Featured')}}</option>
                                                        <option value="offer" class="filter">{{__('Offer')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="supplier-listing-page-serch">
                                        <div class="custom-input-map-near">
                                            <div class="near-input-plus">
                                                <div class="input-style">
                                                    <div class="loc-icon-input-near">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="13.5" height="18"
                                                             viewBox="0 0 13.5 18">
                                                            <path id="Path_48396" data-name="Path 48396"
                                                                  d="M6.047,1.9a.807.807,0,0,0,.7.352.807.807,0,0,0,.7-.352L9.809-1.477q1.758-2.531,2.32-3.41a9.9,9.9,0,0,0,1.09-2.127A6.4,6.4,0,0,0,13.5-9a6.515,6.515,0,0,0-.914-3.375,6.879,6.879,0,0,0-2.461-2.461A6.515,6.515,0,0,0,6.75-15.75a6.515,6.515,0,0,0-3.375.914A6.879,6.879,0,0,0,.914-12.375,6.515,6.515,0,0,0,0-9,6.4,6.4,0,0,0,.281-7.014a9.9,9.9,0,0,0,1.09,2.127q.563.879,2.32,3.41Q5.133.563,6.047,1.9Zm.7-8.086a2.708,2.708,0,0,1-1.986-.826A2.708,2.708,0,0,1,3.938-9a2.708,2.708,0,0,1,.826-1.986,2.708,2.708,0,0,1,1.986-.826,2.708,2.708,0,0,1,1.986.826A2.708,2.708,0,0,1,9.563-9a2.708,2.708,0,0,1-.826,1.986A2.708,2.708,0,0,1,6.75-6.187Z"
                                                                  transform="translate(0 15.75)" fill="#45cea2"></path>
                                                        </svg>
                                                    </div>
                                                    <input type="text" class="ctm-input" id="keyword"
                                                           value="{{$request->keyword ?? ''}}"
                                                           placeholder="{{__('Search by Keyword')}}">
                                                    @if($request->keyword != "")
                                                        <button type="button" class="btn btn-primary"
                                                                id="clear-search">
                                                            <i class="fas fa-times"></i></button>
                                                    @else
                                                        <button type="submit" class="btn btn-primary"
                                                                id="supplier-search">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                 height="24"
                                                                 viewBox="0 0 24 24">
                                                                <path id="Path_11" data-name="Path 11"
                                                                      d="M49.438,58.778A9.17,9.17,0,0,0,55.4,56.6l7.248,7.248a.85.85,0,0,0,1.2,0,.85.85,0,0,0,0-1.2L56.6,55.4a9.324,9.324,0,1,0-7.165,3.375Zm0-16.98A7.641,7.641,0,1,1,41.8,49.438,7.649,7.649,0,0,1,49.438,41.8Z"
                                                                      transform="translate(-40.099 -40.099)"
                                                                      fill="#fff"></path>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @forelse($products as $item)
                                    <div class="col-md-4 col-lg-3">
                                        <div class="feature-product-card-main">
                                            <div class="inner-wrapper-feat">
                                                <div class="image-block-fet">
                                                    <a href="{{ route('front.product.detail', $item->id) }}">
                                                        @if( str_contains($item->imageType(), 'video'))
                                                            <video width="337" height="268" controls>
                                                                <source src="{{$item->default_image}}"
                                                                        type="video/mp4">
                                                            </video>
                                                        @else
                                                            <img
                                                                src="{!! imageUrl($item->default_image,263,260,90,1) !!}"
                                                                class="img-fluid" alt="">
                                                        @endif
                                                    </a>
                                                    <div class="cart-icon-start-parent">
                                                        <div class="labe-starts">
                                                            <h3 class="tittle">
                                                                @if($item->average_rating > 0)
                                                                    {{ number_format($item->average_rating,1) }}
                                                                @else
                                                                    0
                                                                @endif
                                                                <span class="star-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12.541" height="12"
                                                 viewBox="0 0 12.541 12">
                                              <path id="Path_48396" data-name="Path 48396"
                                                    d="M8.531-10.078a.713.713,0,0,1,.41-.375.8.8,0,0,1,.539,0,.713.713,0,0,1,.41.375l1.523,3.094,3.422.492a.711.711,0,0,1,.48.281.783.783,0,0,1,.164.516.719.719,0,0,1-.223.492L12.773-2.789,13.359.633a.74.74,0,0,1-.105.527.688.688,0,0,1-.434.316.723.723,0,0,1-.539-.07L9.211-.187,6.141,1.406a.723.723,0,0,1-.539.07.688.688,0,0,1-.434-.316A.74.74,0,0,1,5.063.633l.586-3.422L3.164-5.2A.719.719,0,0,1,2.941-5.7a.783.783,0,0,1,.164-.516.711.711,0,0,1,.48-.281l3.422-.492Z"
                                                    transform="translate(-2.941 10.5)" fill="#ff6a00"></path>
                                            </svg>

                                          </span>
                                                            </h3>
                                                        </div>
                                                        <form action="{{ route('front.dashboard.cart.add') }}"
                                                              method="post" class="t">
                                                            @csrf
                                                            <input type="hidden" name="quantity" value="1">
                                                            <input type="hidden" name="product_id" value="{{ $item->id }}">
                                                            <div class="cart-icon-img">
                                                                <a
                                                                    class="{{ $item->quantity == 0 ? 'disable' : '' }}  cartBtn">
                                                                    <svg id="Component_6_1" data-name="Component 6 – 1"
                                                                         xmlns="http://www.w3.org/2000/svg" width="32"
                                                                         height="32"
                                                                         viewBox="0 0 32 32">
                                                                        <rect id="Rectangle_50" data-name="Rectangle 50"
                                                                              width="32"
                                                                              height="32" rx="6" fill="#45cea2"></rect>
                                                                        <path id="Path_48396" data-name="Path 48396"
                                                                              d="M18-7.25a.723.723,0,0,0-.219-.531A.723.723,0,0,0,17.25-8H15.156l-3.344-4.594A.967.967,0,0,0,11.156-13a.978.978,0,0,0-.75.188.967.967,0,0,0-.406.656.978.978,0,0,0,.188.75L12.688-8H5.313l2.5-3.406A.978.978,0,0,0,8-12.156a.967.967,0,0,0-.406-.656A.978.978,0,0,0,6.844-13a.967.967,0,0,0-.656.406L2.844-8H.75a.723.723,0,0,0-.531.219A.723.723,0,0,0,0-7.25v.5a.723.723,0,0,0,.219.531A.723.723,0,0,0,.75-6H1L1.813-.281a1.5,1.5,0,0,0,.516.922A1.471,1.471,0,0,0,3.313,1H14.688a1.471,1.471,0,0,0,.984-.359,1.5,1.5,0,0,0,.516-.922L17-6h.25a.723.723,0,0,0,.531-.219A.723.723,0,0,0,18-6.75ZM9.75-1.75a.723.723,0,0,1-.219.531A.723.723,0,0,1,9-1a.723.723,0,0,1-.531-.219A.723.723,0,0,1,8.25-1.75v-3.5a.723.723,0,0,1,.219-.531A.723.723,0,0,1,9-6a.723.723,0,0,1,.531.219.723.723,0,0,1,.219.531Zm3.5,0a.723.723,0,0,1-.219.531A.723.723,0,0,1,12.5-1a.723.723,0,0,1-.531-.219.723.723,0,0,1-.219-.531v-3.5a.723.723,0,0,1,.219-.531A.723.723,0,0,1,12.5-6a.723.723,0,0,1,.531.219.723.723,0,0,1,.219.531Zm-7,0a.723.723,0,0,1-.219.531A.723.723,0,0,1,5.5-1a.723.723,0,0,1-.531-.219A.723.723,0,0,1,4.75-1.75v-3.5a.723.723,0,0,1,.219-.531A.723.723,0,0,1,5.5-6a.723.723,0,0,1,.531.219.723.723,0,0,1,.219.531Z"
                                                                              transform="translate(7 22)" fill="#fff"></path>
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>

                                                <div class="content-crad-feat">
                                                    <h3 class="tittle-card-nameee">{{translate($item->name)}}</h3>

                                                    <h2 class="price-tittle"><span class="cut-price-title">
                                                            @if ($item->offer_percentage > 0)
                                                                {{ getPrice($item->price, $currency) }}
                                                            @endif
                                                        </span>
                                                        <span class="grenn-tittle-p">{{ getPrice($item->discounted_price, $currency) }} /</span>{{__('Meter')}}
                                                    </h2>
                                                    <h3 class="tittle-supplier-name">
                                                        {{__('Supplier:')}} <span
                                                            class="name-sub-sup">{{ translate($item->store->supplier_name) ?? ''}}</span>
                                                    </h3>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                @empty
                                    @include('front.common.alert-empty', ['message' => __('No Product found.')])
                                @endforelse
                            </div>
                            {{-- {{ $products->withQueryString()->links('front.common.pagination', ['paginator' => $products]) }}--}}
                        </div>
                        <div class="tab-pane fade  show {{ request()->has('reviews')  ? 'active' : ''  }}"
                             aria-labelledby="pills-profile-tab">
                            <div class="supplier-detail-ratingg">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div
                                            class="rating-heading-button-dt-p mb-15 d-flex align-items-center justify-content-between">
                                            <h2 class="tittle">{{__('Ratings and Reviews')}}</h2>
                                            <div class="modal-button-rating">
                                                @if(Auth::check())
                                                    @if(Auth::user()->isUser() && count($supplier->orderDetails) > 0)
                                                        <a href="" class="btn btn-black w-100" data-toggle="modal"
                                                           data-target="#exampleModalCenter-1">{{__('Write a review')}}</a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        @if(count($reviews) > 0)
                                            @forelse($reviews as $key => $stores )
                                                <div class="review-list-box rating-page-cls d-flex ">
                                                    <div class="review-img">
                                                        @if(isset($stores->user))
                                                            <img
                                                                src="{!! imageUrl(url($stores->user->image_url),50,50,100,1) !!}"
                                                                class="img-fluid" alt="">
                                                        @endif
                                                    </div>
                                                    <div class="review-detail-box">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div
                                                                class="tittle-left-starts-icons d-flex align-items-center">
                                                                <div class="title">{{$stores->user->user_name}}</div>
                                                                <div class="starts-tittle-icon">
                                                                    <span
                                                                        class="tittle">{{getStarRating($stores->rating)}}</span>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                         width="14.631"
                                                                         height="14" viewBox="0 0 14.631 14">
                                                                        <path id="Path_48396" data-name="Path 48396"
                                                                              d="M9.953-11.758a.832.832,0,0,1,.479-.437.931.931,0,0,1,.629,0,.832.832,0,0,1,.479.438l1.777,3.609,3.992.574a.83.83,0,0,1,.561.328.913.913,0,0,1,.191.6.839.839,0,0,1-.26.574L14.9-3.254,15.586.738a.864.864,0,0,1-.123.615.8.8,0,0,1-.506.369.843.843,0,0,1-.629-.082L10.746-.219,7.164,1.641a.843.843,0,0,1-.629.082.8.8,0,0,1-.506-.369A.864.864,0,0,1,5.906.738L6.59-3.254,3.691-6.07a.839.839,0,0,1-.26-.574.913.913,0,0,1,.191-.6.83.83,0,0,1,.561-.328l3.992-.574Z"
                                                                              transform="translate(-3.431 12.25)"
                                                                              fill="#ff6a00"></path>
                                                                    </svg>
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="reviw-time">{{ Carbon\Carbon::parse($stores->updated_at)->diffForHumans()}}</div>
                                                        </div>
                                                        <div class="des clearfix">
                                                            {!! nl2br($stores->review) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                            @endforelse
                                        @else
                                            <div class="col-sm-12">
                                                <div class="review">
                                                    <div class="alert alert-danger"
                                                         role="alert">
                                                        {{__('No Reviews')}}
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(Auth::check())
        @if(Auth::user()->isUser() && count($supplier->orderDetails) > 0)
            <div class="modal fade custom-modal-pa-all" id="exampleModalCenter-1" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <form method="post" action="{{route('front.dashboard.store.create-reviews')}}" id="reviewForm">
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
                                                <input name="review" class="ctm-input border-full-cst" required
                                                       placeholder="{{__('Comment')}}">
                                                <div class="review-text"></div>
                                                <input type="hidden" name="order_detail_id"
                                                       value="{{$supplier->orderDetails[0]->id ?? ''}}">
                                                <input type="hidden" name="store_id"
                                                       value="{{$supplier->id ?? ''}}">
                                                <input type="hidden" name="user_id"
                                                       value="{{auth()->user()->id}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer mt-25">
                                <button type="button" class="btn btn-primary w-100">{{__('Submit')}}</button>
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

            var url_string = window.location.href;
            var url = new URL(url_string);

            var paramValue = url.searchParams.get("page");
            var check_keyword = url.searchParams.get("keyword");
            var check_products = url.searchParams.get("products");
            if (paramValue) {
                var pag = paramValue;
            } else {
                var pag = 1;
            }

            $(document).on('change', ".filter", function (e) {
                e.preventDefault();
                let status = $('.filter :selected').val();
                window.location.href = '?products=' + true + '&' + status + '=true' + '&order_by=' + status + '&page=' + pag;
            });

            $(document).on('click', "#products", function () {
                window.location.href = '?products=' + true + '&page=' + pag;
            });

            $(document).on('click', "#reviews", function () {
                window.location.href = '?reviews=' + true + '&page=' + pag;
            });

            $('#reviewForm').validate({
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
                $("#products_tab").removeClass('active');

                if (window.location.href.indexOf("reviews=true") > -1) {
                    console.log("d")
                } else {
                    $("#reviews").click();
                }

                $(".rating-btn").click();
                $('html, body').animate({
                    scrollTop: $(".rating-area").offset().top
                }, 1500);
            }

            $("#clear-search").on('click', function () {
                let new_Url = url.href.replace("&keyword=", "");
                window.location.href = new_Url;
            });

            $("#supplier-search").on('click', function () {
                let SubName = $('#keyword').val();
                if (check_keyword == null) {
                    if (check_products == null) {
                        var new_Url = url + "?keyword=" + SubName;
                    } else {
                        var new_Url = url + "&keyword=" + SubName;
                    }
                } else {

                    if (check_products == null) {
                        var n_Url = url.href.split('keyword=')[0];
                        var new_Url = n_Url + "?keyword=" + SubName;
                    } else {
                        var n_Url = url.href.split('&keyword=')[0];
                        var new_Url = n_Url + "&keyword=" + SubName;
                    }
                    var new_Url = n_Url + "&keyword=" + SubName;

                }
                window.location.href = new_Url;
            });

            $("#keyword").keypress(function (event) {
                if (event.which == 13) {
                    let SubName = $('#keyword').val();
                    if (check_keyword == null) {
                        if (check_products == null) {
                            var new_Url = url + "?keyword=" + SubName;
                        } else {
                            var new_Url = url + "&keyword=" + SubName;
                        }
                    } else {
                        var n_Url = url.href.split('keyword=')[0];
                        if (check_products == null) {
                            var new_Url = n_Url + "?keyword=" + SubName;
                        } else {
                            var new_Url = n_Url + "&keyword=" + SubName;
                        }
                    }
                    window.location.href = new_Url;
                }
            });
        });
    </script>
@endPush
