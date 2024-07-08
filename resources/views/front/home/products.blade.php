@extends('front.layouts.app')
@section('content')
    @include('front.common.breadcrumb')
    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-lg-3">

                    <form class="w-100" action="{{ route('front.products') }}" id="formSubmit">
                        <div class="product-lisitng-left-filter">
                            <div class="input-style">
                                <input type="text" class="ctm-input p-left-o" name="keyword"
                                       value="{{ $request->keyword ?? '' }}" placeholder="{{__('Search by Keyword')}}">
                                <button type="submit" class="btn btn-primary search-pro">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path id="Path_11" data-name="Path 11"
                                              d="M49.438,58.778A9.17,9.17,0,0,0,55.4,56.6l7.248,7.248a.85.85,0,0,0,1.2,0,.85.85,0,0,0,0-1.2L56.6,55.4a9.324,9.324,0,1,0-7.165,3.375Zm0-16.98A7.641,7.641,0,1,1,41.8,49.438,7.649,7.649,0,0,1,49.438,41.8Z"
                                              transform="translate(-40.099 -40.099)" fill="#fff"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="inputs-border-wrapper mt-3">
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
                                        <select class="js-example-basic-single" name="category_id" id="onCateChange">
                                            <option value="" readonly="" selected disabled>{{ __('Category') }}</option>
                                            @forelse($categories as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $request->category_id == $item->id ? 'selected' : '' }}>
                                                    {{ translate($item->name) }}</option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                                <div class="input-style phone-dropdown custom-drop-contact">
                                    <div class="custom-selct-icons-arow position-relative">
                                        <span class="icon-front-input">
                              <svg xmlns="http://www.w3.org/2000/svg" width="13.5" height="18" viewBox="0 0 13.5 18">
                                  <path id="Path_48396" data-name="Path 48396"
                                        d="M-.7,1.9A.807.807,0,0,0,0,2.25.807.807,0,0,0,.7,1.9L3.059-1.477q1.758-2.531,2.32-3.41a9.9,9.9,0,0,0,1.09-2.127A6.4,6.4,0,0,0,6.75-9a6.515,6.515,0,0,0-.914-3.375,6.879,6.879,0,0,0-2.461-2.461A6.515,6.515,0,0,0,0-15.75a6.515,6.515,0,0,0-3.375.914,6.879,6.879,0,0,0-2.461,2.461A6.515,6.515,0,0,0-6.75-9a6.4,6.4,0,0,0,.281,1.986,9.9,9.9,0,0,0,1.09,2.127q.563.879,2.32,3.41Q-1.617.563-.7,1.9ZM0-6.187a2.708,2.708,0,0,1-1.986-.826A2.708,2.708,0,0,1-2.812-9a2.708,2.708,0,0,1,.826-1.986A2.708,2.708,0,0,1,0-11.812a2.708,2.708,0,0,1,1.986.826A2.708,2.708,0,0,1,2.813-9a2.708,2.708,0,0,1-.826,1.986A2.708,2.708,0,0,1,0-6.187Z"
                                        transform="translate(6.75 15.75)" fill="#45cea2"></path>
                              </svg>
                          </span>
                                        <img src="{{asset('assets/front/img/arrow-down-2.png')}}" alt=""
                                             class="img-fluid arrow-abs">
                                        <select class="js-example-basic-single" name="subcategory_id"
                                                id="subcategory_id">
                                            <option value="" readonly="" selected disabled>{{ __('SubCategory') }}
                                            </option>
                                            @if (request()->get('subcategory_id') != null)
                                                @foreach ($sub_categories as $subcategory)
                                                    <option
                                                        {{ request()?->get('subcategory_id') == $subcategory->id ? 'selected' : '' }}
                                                        value="{{ $subcategory->id }}">
                                                        {!! translate($subcategory->name) !!}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>

                                <div class="input-style phone-dropdown custom-drop-contact">
                                    <div class="custom-selct-icons-arow position-relative">
                                        <span class="icon-front-input">
                              <svg xmlns="http://www.w3.org/2000/svg" width="13.5" height="18" viewBox="0 0 13.5 18">
                                  <path id="Path_48396" data-name="Path 48396"
                                        d="M-.7,1.9A.807.807,0,0,0,0,2.25.807.807,0,0,0,.7,1.9L3.059-1.477q1.758-2.531,2.32-3.41a9.9,9.9,0,0,0,1.09-2.127A6.4,6.4,0,0,0,6.75-9a6.515,6.515,0,0,0-.914-3.375,6.879,6.879,0,0,0-2.461-2.461A6.515,6.515,0,0,0,0-15.75a6.515,6.515,0,0,0-3.375.914,6.879,6.879,0,0,0-2.461,2.461A6.515,6.515,0,0,0-6.75-9a6.4,6.4,0,0,0,.281,1.986,9.9,9.9,0,0,0,1.09,2.127q.563.879,2.32,3.41Q-1.617.563-.7,1.9ZM0-6.187a2.708,2.708,0,0,1-1.986-.826A2.708,2.708,0,0,1-2.812-9a2.708,2.708,0,0,1,.826-1.986A2.708,2.708,0,0,1,0-11.812a2.708,2.708,0,0,1,1.986.826A2.708,2.708,0,0,1,2.813-9a2.708,2.708,0,0,1-.826,1.986A2.708,2.708,0,0,1,0-6.187Z"
                                        transform="translate(6.75 15.75)" fill="#45cea2"></path>
                              </svg>
                          </span>
                                        <img src="{{asset('assets/front/img/arrow-down-2.png')}}" alt=""
                                             class="img-fluid arrow-abs">
                                        <select class="js-example-basic-single" name="store_id">
                                            <option value="" readonly="" selected disabled>{{ __('Supplier Name') }}
                                            </option>
                                            @forelse($suppliers as $item)
                                                <option value="{{ $item->id }}">{{ translate($item->supplier_name) }}
                                                </option>
                                            @empty
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                                <input type="hidden" name="abc" value="true" id="sorting">
                                <input type="hidden" name="sorting" value="" id="sorting_text">

                                <div class="input-style">
                                    <div class="wrapper-input">
                                    <span class="icon-front-input">
                          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16"
                               height="16" viewBox="0 0 16 16">
                            <image id="colours_1_" data-name="colours (1)" width="16" height="16"
                                   xlink:href="data:image/png;base64,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"/>
                          </svg>
                        </span>
                                        <input type="text" name="price" class="ctm-input" placeholder="{{__('Price')}}">
                                    </div>
                                </div>

                                <div class="input-style">
                                    <div class="wrapper-input">
                                        <span class="icon-front-input">
                          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="16"
                               height="20.267" viewBox="0 0 16 20.267">
                            <image id="_2900961-200" data-name="2900961-200" width="16" height="20.267"
                                   xlink:href="data:image/png;base64,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"/>
                          </svg>
                        </span>
                                        <input type="text" class="ctm-input" placeholder="{{__('Color')}}">
                                    </div>
                                </div>
                            </div>

                            <div class="filter-button-pro-list mt-3">
                                <button type="submit" class="btn btn-primary w-100">{{__('apply')}}</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-md-8 col-lg-9">
                    <div class="product-list-page-drop">
                        <div class="lisitng-bread-prouct-p">
                            <ul class="bread-crumb-p-list">
                                {{--                                <li class="list-items-1">Plain Fabrics</li>--}}
                                {{--                                <li class="list-items-2">--}}
                                {{--                            <span class="arrow-chero">--}}
                                {{--                                <svg xmlns="http://www.w3.org/2000/svg" width="8.212" height="14.362"--}}
                                {{--                                     viewBox="0 0 8.212 14.362">--}}
                                {{--                                    <path id="Icon_ionic-ios-arrow-forward" data-name="Icon ionic-ios-arrow-forward"--}}
                                {{--                                          d="M16.983,13.375,11.548,7.944a1.022,1.022,0,0,1,0-1.45,1.035,1.035,0,0,1,1.454,0l6.158,6.153a1.024,1.024,0,0,1,.03,1.415l-6.183,6.2a1.027,1.027,0,0,1-1.454-1.45Z"--}}
                                {{--                                          transform="translate(-11.246 -6.196)" fill="#222"/>--}}
                                {{--                                  </svg>--}}

                                {{--                            </span>--}}
                                {{--                                    Lawn Fabric--}}
                                {{--                                </li>--}}
                            </ul>
                        </div>
                        <div class="invoice-detail-main">
                            <div class="dropdown invoice">
                                <button type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false"
                                        class="m-usd w-100">
                                    @if ($request->sorting == 'all')
                                        {{ __('All') }}
                                    @elseif($request->sorting == 'offer')
                                        {{ __('Offer') }}
                                    @elseif($request->sorting == 'low_to_high')
                                        {{ __('Price (Low to High)') }}
                                    @elseif($request->sorting == 'high_to_low')
                                        {{ __('Price (High to Low)') }}
                                    @elseif($request->sorting == 'a_to_z')
                                        {{ __('Name (A - Z)') }}
                                    @elseif($request->sorting == 'z_to_a')
                                        {{ __('Name (Z-A)') }}
                                    @elseif($request->sorting == 'rate_high_to_low')
                                        {{__('Rating: High to Low')}}
                                    @elseif($request->sorting == 'rate_low_to_high')
                                        {{__('Rating: Low to High')}}
                                    @else
                                        {{ __('All') }}
                                    @endif
                                    <span class="drop-incon-p-list">
                            <svg id="Component_5_1" data-name="Component 5 – 1" xmlns="http://www.w3.org/2000/svg"
                                 width="40" height="40" viewBox="0 0 40 40">
                                <rect id="Rectangle_41" data-name="Rectangle 41" width="40" height="40" rx="4"
                                      fill="#45cea2"/>
                                <g id="sort-down" transform="translate(8.681 10.789)">
                                  <path id="Path_48360" data-name="Path 48360"
                                        d="M6.406,4.5a.781.781,0,0,1,.781.781V20.892a.781.781,0,1,1-1.561,0V5.281A.781.781,0,0,1,6.406,4.5Z"
                                        transform="translate(-2.501 -4.5)" fill="#fff" fill-rule="evenodd"/>
                                  <path id="Path_48361" data-name="Path 48361"
                                        d="M8.7,17.906a.781.781,0,0,1,0,1.105L5.58,22.134a.781.781,0,0,1-1.105,0L1.352,19.012a.782.782,0,0,1,1.105-1.105l2.57,2.571L7.6,17.906a.781.781,0,0,1,1.105,0Zm2.57-1.009a.781.781,0,0,1,.781-.781h4.683a.781.781,0,1,1,0,1.561H12.052A.781.781,0,0,1,11.272,16.9Zm0-4.683a.781.781,0,0,1,.781-.781h7.806a.781.781,0,0,1,0,1.561H12.052A.781.781,0,0,1,11.272,12.214Zm0-4.683a.781.781,0,0,1,.781-.781H22.981a.781.781,0,0,1,0,1.561H12.052A.781.781,0,0,1,11.272,7.531Zm0,14.05a.781.781,0,0,1,.781-.781h1.561a.781.781,0,0,1,0,1.561H12.052A.781.781,0,0,1,11.272,21.581Z"
                                        transform="translate(-1.123 -5.189)" fill="#fff" fill-rule="evenodd"/>
                                </g>
                              </svg>

                        </span>
                                </button>
                                <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                                    <a href="#" class="dropdown-item sort" data-sorting="offer">{{__('Offer')}}</a>
                                    <a href="#" class="dropdown-item sort" data-sorting="a_to_z">{{__('Name: A-Z')}}</a>
                                    <a href="#" class="dropdown-item sort" data-sorting="z_to_a">{{__('Name: Z-A')}}</a>
                                    <a href="#" class="dropdown-item sort"
                                       data-sorting="high_to_low">{{__('Price: High to Low')}}</a>
                                    <a href="#" class="dropdown-item sort" data-sorting="low_to_high">{{__('Price: Low to
                                        High')}}</a>
                                    <a href="#" class="dropdown-item sort"
                                       data-sorting="rate_high_to_low">{{__('Rating: High to Low')}}</a>
                                    <a href="#" class="dropdown-item sort"
                                       data-sorting="rate_low_to_high">{{__('Rating: Low to High')}}</a>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="row">
                        @forelse($products as $item)
                            <div class="col-md-6 col-lg-4">
                                <div class="feature-product-card-main">
                                    <div class="inner-wrapper-feat">
                                        <div class="image-block-fet">
                                            <a href="{{ route('front.product.detail', $item->id) }}">
                                                @if (str_contains($item->imageType(), 'video'))
                                                    <video width="337" height="269" controls>
                                                        <source src="{{ $item->default_image }}" type="video/mp4">
                                                    </video>
                                                @else
                                                    <img src="{!! imageUrl($item->default_image, 263, 260, 95, 1) !!}"
                                                         class="img-fluid"
                                                         alt="">
                                                @endif
                                            </a>

                                            @if ($item->is_featured)
                                                <div class="green-label-badge">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="27"
                                                         viewBox="0 0 15 27">
                                                        <path id="Path_48351" data-name="Path 48351"
                                                              d="M0,0H15V27L7.313,21.393,0,27Z" fill="#45cea2"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                            {{--    @dd(session()->all())--}}
                                            <div class="cart-icon-start-parent">
                                                <div class="labe-starts">
                                                    @if($item->average_rating > 0)
                                                        <h3 class="tittle">{{ getStarRating($item->average_rating) }}
                                                            <span class="star-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12.541" height="12"
                                     viewBox="0 0 12.541 12">
                                  <path id="Path_48396" data-name="Path 48396"
                                        d="M8.531-10.078a.713.713,0,0,1,.41-.375.8.8,0,0,1,.539,0,.713.713,0,0,1,.41.375l1.523,3.094,3.422.492a.711.711,0,0,1,.48.281.783.783,0,0,1,.164.516.719.719,0,0,1-.223.492L12.773-2.789,13.359.633a.74.74,0,0,1-.105.527.688.688,0,0,1-.434.316.723.723,0,0,1-.539-.07L9.211-.187,6.141,1.406a.723.723,0,0,1-.539.07.688.688,0,0,1-.434-.316A.74.74,0,0,1,5.063.633l.586-3.422L3.164-5.2A.719.719,0,0,1,2.941-5.7a.783.783,0,0,1,.164-.516.711.711,0,0,1,.48-.281l3.422-.492Z"
                                        transform="translate(-2.941 10.5)" fill="#ff6a00"></path>
                                </svg>
                              </span>
                                                        </h3>
                                                    @else
                                                        <h3 class="tittle">0
                                                            <span class="star-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12.541" height="12"
                                     viewBox="0 0 12.541 12">
                                  <path id="Path_48396" data-name="Path 48396"
                                        d="M8.531-10.078a.713.713,0,0,1,.41-.375.8.8,0,0,1,.539,0,.713.713,0,0,1,.41.375l1.523,3.094,3.422.492a.711.711,0,0,1,.48.281.783.783,0,0,1,.164.516.719.719,0,0,1-.223.492L12.773-2.789,13.359.633a.74.74,0,0,1-.105.527.688.688,0,0,1-.434.316.723.723,0,0,1-.539-.07L9.211-.187,6.141,1.406a.723.723,0,0,1-.539.07.688.688,0,0,1-.434-.316A.74.74,0,0,1,5.063.633l.586-3.422L3.164-5.2A.719.719,0,0,1,2.941-5.7a.783.783,0,0,1,.164-.516.711.711,0,0,1,.48-.281l3.422-.492Z"
                                        transform="translate(-2.941 10.5)" fill="#ff6a00"></path>
                                </svg>
                              </span>
                                                        </h3>
                                                    @endif
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
                                            <h3 class="tittle-card-nameee">{{ translate($item->name) }}</h3>
                                            <h2 class="price-tittle"><span class="cut-price-title">
                                                     @if ($item->offer_percentage > 0)
                                                        {{ getPrice($item->price, $currency) }}
                                                    @endif
                                                </span> <span
                                                    class="grenn-tittle-p">{{ getPrice($item->discounted_price, $currency) }} /</span>{{__('Meter')}}
                                            </h2>
                                            <h3 class="tittle-supplier-name">
                                                {{__('Supplier:')}} <span
                                                    class="name-sub-sup">{{ translate($item->store->supplier_name) ?? '' }}</span>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            @include('front.common.alert-empty', ['message' => __('No Product found.')])
                        @endforelse
                    </div>
                    {{ $products->withQueryString()->links('front.common.pagination', ['paginator' => $products]) }}
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            var url_string = window.location.href;
            var url = new URL(url_string);

            var paramValue = url.searchParams.get("page");
            if (paramValue) {
                var pag = paramValue;
            } else {
                var pag = 1;
            }


            $(document).on('click', ".sort", function (e) {
                e.preventDefault();
                let status = $(this).data('sorting');
                $('#sorting').attr('name', status);
                $('#sorting_text').val(status);
                $("#formSubmit").submit();
            });

            $(document).on("change", "#onCateChange", function (e) {
                e.preventDefault();
                var category_id = $("#onCateChange option:selected").val();
                $('#subcategory_id').empty();
                $.ajax({
                    type: "GET",
                    url: "{{ route('api.categories.sub-categories') }}/" + category_id,
                    dataType: 'json',
                    success: function (response) {
                        if (locale === 'ar') {
                            var area_data = response.data.collection.map((ar) => {
                                let area = {
                                    id: ar.id,
                                    text: ar.name['ar']
                                }
                                return area;
                            });
                            $("#subcategory_id").select2({
                                placeholder: "SubCategory",
                                data: area_data
                            });
                        } else {
                            var area_data = response.data.collection.map((ar) => {
                                let area = {
                                    id: ar.id,
                                    text: ar.name['en']
                                }
                                return area;
                            });
                            $("#subcategory_id").select2({
                                placeholder: "SubCategory",
                                data: area_data
                            });
                        }
                        $(".custom-select2").select2();
                    }
                });
            });
        });
    </script>
@endpush
