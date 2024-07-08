@extends('front.layouts.app')


@section('content')

{{-- Banner Section--}}
<section class="home-banner-image-sec">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="content-on-banner-home">
                    <h3 class="tittle">{{__('Sale up')}} <span class="color-green">{{__('80%')}}</span> {{__('off')}}
                    </h3>
                    <h4 class="tittle-2-ban">{{__('THE BEST CLOTHING DAY SALES 2022')}}</h4>
                </div>
                <div class="categories-dro-input-main">
                    <div class="banner-custon-cate-inner">
                        <div class="input-style phone-dropdown custom-drop-contact">
                            <!-- custom select -->
                            <div class="custom-selct-icons-arow position-relative">
                                <img src="{{asset('assets/front/img/arrow-down.png')}}" class="img-fluid arrow-abs">
                                <select class="js-example-basic-single" id="category_id" name="category_id">
                                    <option selected value="">{{__('Select Category')}}</option>
                                    @foreach ($categories as $item)
                                    @if ($item->parent_id == 0)
                                    <option value="{{ $item->id }}" @if (old('category_id')==$item->id) selected @endif>
                                        {{ translate($item->name) }}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                            <!-- end custom-select -->
                        </div>
                        <div class="input-style w-100">
                            <div class="input-btn-search-ban">
                                <input type="text" class="ctm-input" name="name" id="product_keyword"
                                    placeholder="{{__('Search keyword')}}">
                                <button type="button" class="btn btn-primary" id="banner_btn_submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path id="Path_11" data-name="Path 11"
                                            d="M49.438,58.778A9.17,9.17,0,0,0,55.4,56.6l7.248,7.248a.85.85,0,0,0,1.2,0,.85.85,0,0,0,0-1.2L56.6,55.4a9.324,9.324,0,1,0-7.165,3.375Zm0-16.98A7.641,7.641,0,1,1,41.8,49.438,7.649,7.649,0,0,1,49.438,41.8Z"
                                            transform="translate(-40.099 -40.099)" fill="#fff" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="bnanner-slider-secc">
    <div class="innner-slider-banner">
        <div class="slider-parent-top-block">
            <div class="left-box-navigation-of-slider">
                <h3 class="nav-tittle-number">
                    <span class="second-number-tittle start">0</span>
                    <span class="seprater">/</span>
                    <span class="second-number-tittle end">0</span>
                </h3>
                <div class="slider-arrows">
                    <button type="button" class="arrow-left-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="5.633" height="8.75" viewBox="0 0 5.633 8.75">
                            <path id="Path_48395" data-name="Path 48395"
                                d="M.875-5.715a.633.633,0,0,0-.191.465.633.633,0,0,0,.191.465L4.594-1.066a.633.633,0,0,0,.465.191.633.633,0,0,0,.465-.191l.6-.629a.633.633,0,0,0,.191-.465.633.633,0,0,0-.191-.465L3.5-5.25,6.125-7.9a.547.547,0,0,0,.191-.437A.633.633,0,0,0,6.125-8.8l-.6-.629a.633.633,0,0,0-.465-.191.633.633,0,0,0-.465.191Z"
                                transform="translate(-0.684 9.625)" fill="#fff" />
                        </svg>
                    </button>
                    <button type="button" class="arrow-right-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="5.633" height="8.75" viewBox="0 0 5.633 8.75">
                            <path id="Path_48396" data-name="Path 48396"
                                d="M.875-5.715a.633.633,0,0,0-.191.465.633.633,0,0,0,.191.465L4.594-1.066a.633.633,0,0,0,.465.191.633.633,0,0,0,.465-.191l.6-.629a.633.633,0,0,0,.191-.465.633.633,0,0,0-.191-.465L3.5-5.25,6.125-7.9a.547.547,0,0,0,.191-.437A.633.633,0,0,0,6.125-8.8l-.6-.629a.633.633,0,0,0-.465-.191.633.633,0,0,0-.465.191Z"
                                transform="translate(6.316 -0.875) rotate(180)" fill="#fff" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="slider-card-banner slides">
                @foreach ($categories as $item)
                <div class="card-cloth-ban-main">
                    <div class="inner-wrapper-card-coloth">
                        <div class="image-block-card-c">
                            <img src="{{imageUrl($item->image,135,150,90,1)}}" class="img-fluid" alt="image">
                        </div>
                        <div class="right-content-of-card">
                            <h3 class="tittle-name text-truncate">{{translate($item->name)}}</h3>
                            <a href="#" class="detail-more-anchr">{{__('Browse detail')}}
                                <svg xmlns="http://www.w3.org/2000/svg" width="10.5" height="10.219"
                                    viewBox="0 0 10.5 10.219">
                                    <path id="Path_48395" data-name="Path 48395"
                                        d="M4.477-8.93a.476.476,0,0,0-.176.4.586.586,0,0,0,.176.4l2.813,2.7H.563a.542.542,0,0,0-.4.164.542.542,0,0,0-.164.4v.75a.542.542,0,0,0,.164.4.542.542,0,0,0,.4.164H7.289L4.477-.867a.586.586,0,0,0-.176.4.476.476,0,0,0,.176.4l.516.516a.566.566,0,0,0,.8,0L10.336-4.1a.542.542,0,0,0,.164-.4.542.542,0,0,0-.164-.4L5.789-9.445a.542.542,0,0,0-.4-.164.542.542,0,0,0-.4.164Z"
                                        transform="translate(0 9.609)" fill="#666" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="top-rated-conter-sec">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-lg-3">
                <div class="toprated-image-block">
                    <img src="{{asset('assets/front/img/top-rated-img.jpg')}}" class="img-fluid" alt="image">
                    <div class="tittle-decp-rate w-75">
                        <h2 class="tittle-ratedd text-truncate">{{__('Top Rated')}}</h2>
                        <h2 class="tittle-ratedd-2 text-truncate">{{__('Products')}}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-9">
                <div class="parent-image-counter">
                    <div class="counter-block-home">
                        <div class="tittle-decp-rate w-75">
                            <h2 class="tittle-ratedd text-truncate">{{__('Upcoming')}}</h2>
                            <h2 class="tittle-ratedd-2 text-truncate">{{__('Spring Collection')}}</h2>
                        </div>
                        <div id="countdown">
                            <ul class="ul-counter">
                                <li class="items-li-counter"><span id="days"></span>{{__('days')}}</li>
                                <li class="items-li-counter"><span id="hours"></span>{{__('Hours')}}</li>
                                <li class="items-li-counter"><span id="minutes"></span>{{__('Mins')}}</li>
                                <li class="items-li-counter"><span id="seconds"></span>{{__('Secs')}}</li>
                            </ul>
                        </div>
                    </div>
                    <div></div>
                    <div class="right-image-offer-main">
                        <img src="{{asset('assets/front/img/sale-image-home.jpg')}}" class="img-fluid" alt="image">
                        <div class="offer-label-image">
                            <img src="{{asset('assets/front/img/sales-offer-label.png')}}" class="img-fluid"
                                alt="image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="feature-home-sec">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="custom-heading-all-">
                    <h2 class="tittles-name">{{__('Featured Products')}}</h2>
                </div>
            </div>
            <!-- card html here -->
            @forelse($products as $item)
            <div class="col-md-4 col-lg-3">
                <div class="feature-product-card-main">
                    <div class="inner-wrapper-feat">
                        <div class="image-block-fet">
                            <a href="#">
                                @if (str_contains($item->imageType(), 'video'))
                                <video width="263" height="260" controls>
                                    <source src="{{ $item->default_image }}" type="video/mp4">
                                </video>
                                @else
                                <img src="{!! imageUrl($item->default_image, 263, 260, 90, 1) !!}" class="img-fluid"
                                    alt="">
                                @endif
                            </a>
                            <div class="cart-icon-start-parent">
                                <div class="labe-starts">
                                    @if ($item->average_rating > 0)
                                    <h3 class="tittle"> {{ number_format($item->average_rating, 1) }}<span
                                            class="star-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12.541" height="12"
                                                viewBox="0 0 12.541 12">
                                                <path id="Path_48396" data-name="Path 48396"
                                                    d="M8.531-10.078a.713.713,0,0,1,.41-.375.8.8,0,0,1,.539,0,.713.713,0,0,1,.41.375l1.523,3.094,3.422.492a.711.711,0,0,1,.48.281.783.783,0,0,1,.164.516.719.719,0,0,1-.223.492L12.773-2.789,13.359.633a.74.74,0,0,1-.105.527.688.688,0,0,1-.434.316.723.723,0,0,1-.539-.07L9.211-.187,6.141,1.406a.723.723,0,0,1-.539.07.688.688,0,0,1-.434-.316A.74.74,0,0,1,5.063.633l.586-3.422L3.164-5.2A.719.719,0,0,1,2.941-5.7a.783.783,0,0,1,.164-.516.711.711,0,0,1,.48-.281l3.422-.492Z"
                                                    transform="translate(-2.941 10.5)" fill="#ff6a00" />
                                            </svg>
                                        </span>
                                    </h3>
                                    @else
                                    <h3 class="tittle">0<span class="star-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12.541" height="12"
                                                viewBox="0 0 12.541 12">
                                                <path id="Path_48396" data-name="Path 48396"
                                                    d="M8.531-10.078a.713.713,0,0,1,.41-.375.8.8,0,0,1,.539,0,.713.713,0,0,1,.41.375l1.523,3.094,3.422.492a.711.711,0,0,1,.48.281.783.783,0,0,1,.164.516.719.719,0,0,1-.223.492L12.773-2.789,13.359.633a.74.74,0,0,1-.105.527.688.688,0,0,1-.434.316.723.723,0,0,1-.539-.07L9.211-.187,6.141,1.406a.723.723,0,0,1-.539.07.688.688,0,0,1-.434-.316A.74.74,0,0,1,5.063.633l.586-3.422L3.164-5.2A.719.719,0,0,1,2.941-5.7a.783.783,0,0,1,.164-.516.711.711,0,0,1,.48-.281l3.422-.492Z"
                                                    transform="translate(-2.941 10.5)" fill="#ff6a00" />
                                            </svg>
                                        </span>
                                    </h3>
                                    @endif
                                </div>
                                <form action="{{ route('front.dashboard.cart.add') }}" method="post" class="t">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <input type="hidden" name="product_id" value="{{ $item->id }}">
                                    <div class="cart-icon-img">
                                        <a class="{{ $item->quantity == 0 ? 'disable' : '' }}  cartBtn">
                                            <svg id="Component_6_1" data-name="Component 6 – 1"
                                                xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                                viewBox="0 0 32 32">
                                                <rect id="Rectangle_50" data-name="Rectangle 50" width="32" height="32"
                                                    rx="6" fill="#45cea2"></rect>
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
                                    @if($item->offer_percentage > 0)
                                    {{ getPrice($item->discounted_price, $currency) }}
                                    @endif
                                </span>
                                <span class="grenn-tittle-p">
                                    {{getPrice($item->discounted_price,$currency)}}
                                    /</span>{{__('Meter')}}
                            </h2>
                            <h3 class="tittle-supplier-name">
                                {{__('Supplier:')}} <span class="name-sub-sup">{{ translate($item->store->supplier_name)
                                    ?? '' }}</span>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            @include('front.common.alert-empty', ['message' => __('No Products found.')])
            @endforelse
        </div>
    </div>
</section>

<section class="welcome-home-sec">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="welcome-image-content-parent">
                    <div class="left-side-image-block">
                        <div class="image-block-welcome ">
                            <img src="{{asset('assets/front/img/welcome-image.jpg')}}" class="img-fluid" alt="image">
                        </div>
                    </div>
                    <div class="right-side-content-welcome">
                        <h2 class="tittle-wel">{{__('Welcome to Seven')}}</h2>
                        <h3 class="retailer-marking-tittle">{{__('The retailer is marking down')}}</h3>
                        <h4 class="price-discount-tittle">up to<span class="size-increase-7">{{__('70%')}}</span>
                            {{__('off')}}</h4>
                        <div class="desb-p-wel">
                            <p>
                                Nulla dolor ligula, auctor at velit non, pharetra interdum diam. Donec pellentesque
                                mattis risus, sed efficitur nunc tristique varius. Interdum et malesuada fames ac
                                ante
                                ipsum primis in faucibus.Donec vitae nunc viverra augue suscipit pulvinar sed in mi.
                                Interdum et malesuada fames ac ante ipsum primis in faucibus.
                            </p>

                        </div>
                        <div class="button-welcome-home">
                            <a href="{{route('front.auth.register.foam',['supplier'])}}"
                                class="btn btn-primary">{{__('Become a Seller')}}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="map-near-by-parent">
        <div class="custom-heading-all-">
            <h2 class="tittles-name">{{__('Nearby Suppliers')}}</h2>
        </div>

        <div class="custom-input-map-near">
            <div class="near-input-plus">
                <div class="input-style">
                    <div class="loc-icon-input-near">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13.5" height="18" viewBox="0 0 13.5 18">
                            <path id="Path_48396" data-name="Path 48396"
                                d="M6.047,1.9a.807.807,0,0,0,.7.352.807.807,0,0,0,.7-.352L9.809-1.477q1.758-2.531,2.32-3.41a9.9,9.9,0,0,0,1.09-2.127A6.4,6.4,0,0,0,13.5-9a6.515,6.515,0,0,0-.914-3.375,6.879,6.879,0,0,0-2.461-2.461A6.515,6.515,0,0,0,6.75-15.75a6.515,6.515,0,0,0-3.375.914A6.879,6.879,0,0,0,.914-12.375,6.515,6.515,0,0,0,0-9,6.4,6.4,0,0,0,.281-7.014a9.9,9.9,0,0,0,1.09,2.127q.563.879,2.32,3.41Q5.133.563,6.047,1.9Zm.7-8.086a2.708,2.708,0,0,1-1.986-.826A2.708,2.708,0,0,1,3.938-9a2.708,2.708,0,0,1,.826-1.986,2.708,2.708,0,0,1,1.986-.826,2.708,2.708,0,0,1,1.986.826A2.708,2.708,0,0,1,9.563-9a2.708,2.708,0,0,1-.826,1.986A2.708,2.708,0,0,1,6.75-6.187Z"
                                transform="translate(0 15.75)" fill="#45cea2" />
                        </svg>
                    </div>
                    <input type="text" class="ctm-input" name="" id="address"
                        placeholder="{{__('Enter shop address')}}">
                    <input type="hidden" name="latitude" id="latitude" value="{{old('latitude')}}">
                    <input type="hidden" name="longitude" id="longitude" value="{{old('longitude')}}">
                    <button type="submit" class="btn btn-primary" id="supplier-search">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path id="Path_11" data-name="Path 11"
                                d="M49.438,58.778A9.17,9.17,0,0,0,55.4,56.6l7.248,7.248a.85.85,0,0,0,1.2,0,.85.85,0,0,0,0-1.2L56.6,55.4a9.324,9.324,0,1,0-7.165,3.375Zm0-16.98A7.641,7.641,0,1,1,41.8,49.438,7.649,7.649,0,0,1,49.438,41.8Z"
                                transform="translate(-40.099 -40.099)" fill="#fff" />
                        </svg>
                    </button>
                    <button type="button" id="clear-search" class="btn btn-primary d-none"><i
                            class="fas fa-times"></i></button>
                </div>
                <div class="loacator-icon-maap">
                    <a href="">
                        <svg id="Component_5_1" data-name="Component 5 – 1" xmlns="http://www.w3.org/2000/svg"
                            width="50" height="50" viewBox="0 0 50 50">
                            <rect id="Rectangle_41" data-name="Rectangle 41" width="50" height="50" rx="8"
                                fill="#45cea2" />
                            <path id="Union_5" data-name="Union 5"
                                d="M-957.5-3464.045a4.544,4.544,0,0,0-4.545,4.545,4.544,4.544,0,0,0,4.545,4.546,4.544,4.544,0,0,0,4.545-4.546A4.544,4.544,0,0,0-957.5-3464.045Zm10.159,3.409a10.221,10.221,0,0,0-9.023-9.023v-1.2A1.14,1.14,0,0,0-957.5-3472a1.14,1.14,0,0,0-1.136,1.136v1.2a10.221,10.221,0,0,0-9.023,9.023h-1.2A1.14,1.14,0,0,0-970-3459.5a1.14,1.14,0,0,0,1.136,1.136h1.2a10.221,10.221,0,0,0,9.023,9.023v1.2A1.14,1.14,0,0,0-957.5-3447a1.14,1.14,0,0,0,1.136-1.136v-1.2a10.221,10.221,0,0,0,9.023-9.023h1.2A1.14,1.14,0,0,0-945-3459.5a1.14,1.14,0,0,0-1.136-1.136Zm-10.159,9.091a7.949,7.949,0,0,1-7.955-7.955,7.949,7.949,0,0,1,7.955-7.954,7.949,7.949,0,0,1,7.955,7.954A7.949,7.949,0,0,1-957.5-3451.545Z"
                                transform="translate(983 3485)" fill="#fff" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>


        <div id="map"></div>

        <!-- slider- area block here -->
        <div class="uppe-top-parent-height">
            <div class="top-parent-slider-near">
                <div class="inner-wrapper-for-slider">
                    <div class="near-slider" id="suppliers-slider">
                        @forelse($stores as $item)
                        <div class="near-card-main-p">
                            <div class="inner-wrapper-card-con">
                                <div class="image-block-near">
                                    <img src="{{ imageUrl($item->image_url, 68, 68, 90, 1) }}" class="img-fluid" alt="">
                                </div>
                                <div class="content-right-side-near">
                                    <h3 class="tittle text-truncate"> {{ translate($item->supplier_name) }}</h3>
                                    <div class="marker-tittle-p">
                                        <span class="lock-span-mar">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10.5" height="14"
                                                viewBox="0 0 10.5 14">
                                                <path id="Path_48396" data-name="Path 48396"
                                                    d="M4.7,1.477a.627.627,0,0,0,.547.273A.627.627,0,0,0,5.8,1.477L7.629-1.148Q9-3.117,9.434-3.8a7.7,7.7,0,0,0,.848-1.654A4.977,4.977,0,0,0,10.5-7a5.067,5.067,0,0,0-.711-2.625,5.35,5.35,0,0,0-1.914-1.914A5.067,5.067,0,0,0,5.25-12.25a5.067,5.067,0,0,0-2.625.711A5.35,5.35,0,0,0,.711-9.625,5.067,5.067,0,0,0,0-7,4.977,4.977,0,0,0,.219-5.455,7.7,7.7,0,0,0,1.066-3.8q.438.684,1.8,2.652Q3.992.438,4.7,1.477ZM5.25-4.812a2.106,2.106,0,0,1-1.545-.643A2.106,2.106,0,0,1,3.063-7a2.106,2.106,0,0,1,.643-1.545A2.106,2.106,0,0,1,5.25-9.187a2.106,2.106,0,0,1,1.545.643A2.106,2.106,0,0,1,7.438-7a2.106,2.106,0,0,1-.643,1.545A2.106,2.106,0,0,1,5.25-4.812Z"
                                                    transform="translate(0 12.25)" fill="#45cea2" />
                                            </svg>
                                        </span>
                                        <h2 class="address-tittle">{{ $item->address }}</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        @include('front.common.alert-empty', ['message' => __('No Suppliers found.')])
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="app-sec-home">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="parent-left-img-appp">
                    <div class="image-left-mob-app">
                        <img src="{{asset('assets/front/img/app-mobile-img.png')}}" class="img-fluid" alt="image">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="app-content-right-sev">
                    <h3 class="tittle-1">{{__('Get Seven Mobile Application')}}</h3>
                    <h4 class="tittle-2">{{__('Application is easy to use and very user friendly')}}</h4>
                    <div class="social-app-logoss">
                        <div class="box-1-app">
                            <a href="{!! config('settings.android_app') !!}" target="_blank">
                                <img src="{{asset('assets/front/img/PlayStore.png')}}" class="img-fluid" alt="iamge">
                            </a>
                        </div>
                        <div class="box-1-app">
                            <a href="{!! config('settings.ios_app') !!}" target="_blank">
                                <img src="{{asset('assets/front/img/Apple.png')}}" class="img-fluid" alt="iamge">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        initAutocompleteHomePage();
    });

    var latitude = {{ config('settings.latitude') }};
    var longitude = {{ config('settings.longitude') }};
    var locations = @json($locations);
    var searchId_1 = 'address';
    let latElement_1 = 'latitude';
    let lngElement_1 = 'longitude';
    let lat = {{ config('settings.latitude') }};
    let lng = {{ config('settings.longitude') }};
    let allStores = @json($stores);

    $(document).ready(function () {

        $(document).on('click', "#banner_btn_submit", function () {
            var product_name = $("#product_keyword").val();
            var category_id = $('#category_id :selected').val();
            window.location.href = `${window.location.href}/products?keyword=${product_name}&category_id=${category_id}`
        });


        $('#product_keyword').keypress(function (event) {
            if (event.which == 13) {
                var product_name = $("#product_keyword").val();
                var category_id = $('#category_id :selected').val();
                window.location.href = `${window.location.href}/products?keyword=${product_name}&category_id=${category_id}`
            }
        });

        getCurrentPositions();

        function getCurrentPositions() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition1, positionError1);
            } else {
                toastr.error("{{ __('Sorry, your browser does not support HTML5 geolocation.') }}");
            }
        }

        function positionError1() {
            errorInLocation = true;
            toastr.error("{{ __('Geolocation is not enabled. Please enable to use this feature') }}");
        }

        function showPosition1(position) {
            lat = position.coords.latitude ?? '';
            lng = position.coords.longitude ?? '';

            $.ajax({
                url: "{{ route('front.put_in_session') }}",
                method: "get",
                dataType: "JSON",
                data: {
                    latitude: lat,
                    longitude: lng
                },
                success: function (res) {
                    console.log("Good")
                }
            })

            getSuppliers(lat, lng);
        }

        $('#supplier-search').click(function () {
            var latitude = $('#latitude').val();
            var longitude = $('#longitude').val();
            var keyword = $('#address').val();
            if (keyword !== '') {
                $("#supplier-search").hide();
                $('#clear-search').show();
                getbyNAme(keyword, latitude, longitude);
            }
        });

        function getbyNAme(keyword, longitude, latitude) {
            $.ajax({
                url: "{{ route('front.supplier_map_search') }}",
                method: "get",
                dataType: "JSON",
                data: {
                    address: keyword,
                    longitude: longitude,
                    latitude: latitude,
                },
                success: function (res) {
                    $('#clear-search').removeClass('d-none');
                    html = '';
                    if (res.locations.length === 0) {
                        toastr.error("{{ __('Sorry, no record found.') }}");
                    }
                    mapDraws(res.locations, parseFloat(latitude), parseFloat(longitude));
                    sppliersSliderDraw(res.stores.data);
                }
            })
        }

        $('#address').keypress(function (event) {
            if (event.which == 13) {
                var latitude = $('#latitude').val();
                var longitude = $('#longitude').val();
                var keyword = $('#address').val();

                if (keyword !== '') {
                    getbyNAme(keyword, latitude, longitude);
                    $('#clear-search').removeClass('d-none');
                    $("#supplier-search").hide();
                }
            }
        });

        $('#clear-search').click(function () {
            $('#address').val('');
            getSuppliers(lat, lng);
            mapDraws(locations, locations[0][1], locations[0][2]);
            $('#clear-search').hide();
            $('#supplier-search').show();
        });

        function getSuppliers(latitude, longitude) {
            if (first_time) {
                no_of_search = 0;
                first_time = false;
            } else {
                no_of_search += 1;
            }
            $.ajax({
                url: "{{ route('front.supplier_map_search') }}",
                method: "get",
                dataType: "JSON",
                data: {
                    latitude: latitude,
                    longitude: longitude
                },
                success: function (res) {
                    html = '';
                    if (res.locations.length === 0) {
                        html +=
                            ` <div class="warning-set-pop-card"><div class="alert alert-warning alert-ctm"> {!! __('Sorry, No store found in this area') !!}</div></div>`;
                        $('.popular-stadiums-card').html(html);
                    }
                    if (latitude == '') {
                        latitude = {{ config('settings.latitude') }
                    }
                }
                        if(longitude == '') {
                longitude = {{ config('settings.longitude') }
            }
        }
        sppliersSliderDraw(res.stores.data);
        $("#overlay").fadeOut(300);
    }
                })
            }

    function sppliersSliderDraw(suppliers) {
        html = '';
        if (suppliers.length > 0) {

            for (var i = 0; i < suppliers.length; i++) {
                html += `<div class="near-card-main-p show_popup">
              <div class="inner-wrapper-card-con ">
                <div class="image-block-near">
                  <img src="` + imageUrlJs(suppliers[i].image_url, 68, 68, 100, 1) + `" class="img-fluid" alt="">
                </div>
                <div class="content-right-side-near">
                  <h3 class="tittle text-truncate"> ${jsTranslate(suppliers[i].supplier_name)}</h3>
                  <div class="marker-tittle-p">
                    <span class="lock-span-mar">
                      <svg xmlns="http://www.w3.org/2000/svg" width="10.5" height="14" viewBox="0 0 10.5 14">
                        <path id="Path_48396" data-name="Path 48396" d="M4.7,1.477a.627.627,0,0,0,.547.273A.627.627,0,0,0,5.8,1.477L7.629-1.148Q9-3.117,9.434-3.8a7.7,7.7,0,0,0,.848-1.654A4.977,4.977,0,0,0,10.5-7a5.067,5.067,0,0,0-.711-2.625,5.35,5.35,0,0,0-1.914-1.914A5.067,5.067,0,0,0,5.25-12.25a5.067,5.067,0,0,0-2.625.711A5.35,5.35,0,0,0,.711-9.625,5.067,5.067,0,0,0,0-7,4.977,4.977,0,0,0,.219-5.455,7.7,7.7,0,0,0,1.066-3.8q.438.684,1.8,2.652Q3.992.438,4.7,1.477ZM5.25-4.812a2.106,2.106,0,0,1-1.545-.643A2.106,2.106,0,0,1,3.063-7a2.106,2.106,0,0,1,.643-1.545A2.106,2.106,0,0,1,5.25-9.187a2.106,2.106,0,0,1,1.545.643A2.106,2.106,0,0,1,7.438-7a2.106,2.106,0,0,1-.643,1.545A2.106,2.106,0,0,1,5.25-4.812Z" transform="translate(0 12.25)" fill="#45cea2"/>
                      </svg>
                    </span>
                    <h2 class="address-tittle">${suppliers[i].address}</h2>
                  </div>
                </div>
              </div>
               <div class="card-click-show-main closset_div"><div class="inner-wrapper-2nd-card">
                        <div class="image-block-2nd-card"><img src="` + imageUrlJs(suppliers[i].image_url, 273, 157, 100, 1) + `" alt="image" class="img-fluid"></div> <div class="conetnt-2nd-card">
                        <h3 class="tittle text-truncate">${jsTranslate(suppliers[i].supplier_name)}</h3> <div class="marker-tittle-p"><span class="lock-span-mar"><svg xmlns="http://www.w3.org/2000/svg" width="10.5" height="14" viewBox="0 0 10.5 14">
                        <path id="" data-name="Path 48396" d="M4.7,1.477a.627.627,0,0,0,.547.273A.627.627,0,0,0,5.8,1.477L7.629-1.148Q9-3.117,9.434-3.8a7.7,7.7,0,0,0,.848-1.654A4.977,4.977,0,0,0,10.5-7a5.067,5.067,0,0,0-.711-2.625,5.35,5.35,0,0,0-1.
                        914-1.914A5.067,5.067,0,0,0,5.25-12.25a5.067,5.067,0,0,0-2.625.711A5.35,5.35,0,0,0,.711-9.625,5.067,5.067,0,0,0,0-7,4.977,4.977,0,0,0,.219-5.455,7.7,7.7,0,0,0,1.066-3.8q.438.684,1.8,2.652Q3.992.438,4.7,1.477ZM5.25-4.812a2.106,2.
                        106,0,0,1-1.545-.643A2.106,2.106,0,0,1,3.063-7a2.106,2.106,0,0,1,.643-1.545A2.106,2.106,0,0,1,5.25-9.187a2.106,2.106,0,0,1,1.545.643A2.106,2.106,0,0,1,7.438-7a2.106,2.106,0,0,1-.643,1.545A2.106,2.106,0,0,1,5.25-4.812Z"
                        transform="translate(0 12.25)" fill="#45cea2"></path></svg></span> <h2 class="address-tittle text-truncate">${suppliers[i].address}</h2></div> <div class="star-rating-area contact-us d-flex">
                        <div class="ratilike ng-binding pl-0">${suppliers[i].rating}</div> <div rel="` + getStarRating(suppliers[i].rating) + `" class="rating-static clearfix"><label title="Awesome - 5 stars" class="full"></label> <label title="Pretty good - 4.5 stars" class="half"></label>
                        <label title="Pretty good - 4 stars" class="full"></label> <label title="Meh - 3.5 stars" class="half"></label> <label title="Meh - 3 stars" class="full"></label> <label title="Kinda bad - 2.5 stars" class="half">
                        </label> <label title="Kinda bad - 2 stars" class="full"></label> <label title="Meh - 1.5 stars" class="half"></label> <label title="You can do better - 1 star" class="full"></label> <label title="You can do better - 0.5 stars"
                        class="half"></label></div></div> <div class="phone-number-parent"><span class="span-svg"><svg id="" data-name="Component 5 – 1" xmlns="http://www.w3.org/2000/svg" width="9.999" height="14.524" viewBox="0 0 9.999 14.524">
                        <path id="" data-name="Path 29" d="M70.082,10.893a.352.352,0,0,1-.3-.161s0-.007-.007-.011a7.182,7.182,0,0,1-.782-3.459A7.177,7.177,0,0,1,69.774,3.8l.007-.011a.351.351,0,0,1,.3-.161V0c-2.005,0-3.631,3.251-3.631,7.262s1.626,7.262,
                        3.631,7.262Z" transform="translate(-66.451)" fill="#45cea2"></path> <path id="" data-name="Path 30" d="M187.661.436A.436.436,0,0,0,187.225,0h-1.307V3.631h1.307a.436.436,0,0,0,.436-.436Z" transform="translate(-181.851)" fill="#45cea2">
                        </path> <path id="" data-name="Path 31" d="M187.661,320.436a.436.436,0,0,0-.436-.436h-1.307v3.631h1.307a.436.436,0,0,0,.436-.436Z" transform="translate(-181.851 -309.107)" fill="#45cea2"></path> <path id="" data-name="Path 32" d="M237.6
                        63,176.049l-.545-.545a1.156,1.156,0,0,0,0-1.634l.545-.545a1.926,1.926,0,0,1,0,2.724Z" transform="translate(-231.308 -167.425)" fill="#45cea2"></path> <path id="" data-name="Path 33" d="M269.637,146.247l-.545-.545a2.692,2.692,0,0,0,
                        0-3.811l.545-.545a3.462,3.462,0,0,1,0,4.9Z" transform="translate(-262.193 -136.534)" fill="#45cea2"></path> <path id="" data-name="Path 34" d="M301.629,116.434l-.545-.545a4.23,4.23,0,0,0,0-5.989l.545-.545a5,5,0,0,1,0,7.079Z"
                        transform="translate(-293.097 -105.633)" fill="#45cea2"></path></svg></span> <h3 dir="ltr" class="phone-tittle">${suppliers[i].phone}</h3></div></div></div></div>
            </div>`;
            }
        } else {
            html += ` <div class="alert alert-warning alert-ctm"> {!! __('Sorry, no record found') !!}  </div>  `;
        }
        $('#suppliers-slider').html(html);
    }

    $(document).on('click', '.show_popup', function () {
        $("#suppliers-slider").find(".intro-show").addClass("d-none")
        $(this).children().closest(".closset_div").addClass("intro-show").removeClass('d-none');
    });

    mapDraws(locations, locations[0][1], locations[0][2]);

    var no_of_search = 0
    var first_time = true;

    function mapDraws(locations, latitude, longitude, zoom = 2) {
        var marker_icon = "{!! url('assets/front/img/location.png') !!}";
        var marker_icon1 = "{!! url('assets/front/img/location.png') !!}";
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: zoom,
            center: {
                lat: +latitude,
                lng: +longitude
            },
            mapTypeControl: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            styles: [{
                "elementType": "geometry",
                "stylers": [{
                    "color": "#f5f5f5"
                }]
            },
            {
                "elementType": "labels.icon",
                "stylers": [{
                    "visibility": "off"
                }]
            },
            {
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#616161"
                }]
            },
            {
                "elementType": "labels.text.stroke",
                "stylers": [{
                    "color": "#f5f5f5"
                }]
            },
            {
                "featureType": "administrative.land_parcel",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#bdbdbd"
                }]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#eeeeee"
                }]
            },
            {
                "featureType": "poi",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#757575"
                }]
            },
            {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#e5e5e5"
                }]
            },
            {
                "featureType": "poi.park",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9e9e9e"
                }]
            },
            {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#ffffff"
                }]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#757575"
                }]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#dadada"
                }]
            },
            {
                "featureType": "road.highway",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#616161"
                }]
            },
            {
                "featureType": "road.local",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9e9e9e"
                }]
            },
            {
                "featureType": "transit.line",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#e5e5e5"
                }]
            },
            {
                "featureType": "transit.station",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#eeeeee"
                }]
            },
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#c9c9c9"
                }]
            },
            {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9e9e9e"
                }]
            }
            ]
        });

        var infowindow = new google.maps.InfoWindow();
        let infoWindow = infowindow;

        $('#current_location').on("click", () => {
            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };

                        infoWindow.setPosition(pos);
                        infoWindow.setContent("Location found.");
                        infoWindow.open(map);
                        map.setCenter(pos);
                    },
                    () => {
                        handleLocationError(true, infoWindow, map.getCenter());
                    }
                );
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
        });

        if (locations.length > 0) {
            let marker, i, times_search;
            times_search = no_of_search;
            var image = "{!! url('assets/front/img/location.png') !!}"

            for (i = 0; i < locations.length; i++) {
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    icon: image,
                    map: map,
                    zoom: 2,
                });
                google.maps.event.addListener(marker, 'click', (function (marker, i, times_search) {
                    return function () {
                        infowindow.setContent(jsTranslate(locations[i][0]));
                        infowindow.open(map, marker);
                        map.setCenter(new google.maps.LatLng(locations[i][1], locations[i][2]));
                    }
                })(marker, i));
            }
        }
    }

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(
            browserHasGeolocation ?
                "Error: The Geolocation service failed." :
                "Error: Your browser doesn't support geolocation."
        );
        infoWindow.open(map);
    }
        });

    function initAutocompleteHomePage() {
        var map = new google.maps.Map(document.getElementById('address'), {
            zoom: 2,
            center: {
                lat: latitude,
                lng: longitude
            },
            // mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapTypeId: 'roadmap',
            styles: [{
                "elementType": "geometry",
                "stylers": [{
                    "color": "#f5f5f5"
                }]
            },
            {
                "elementType": "labels.icon",
                "stylers": [{
                    "visibility": "off"
                }]
            },
            {
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#616161"
                }]
            },
            {
                "elementType": "labels.text.stroke",
                "stylers": [{
                    "color": "#f5f5f5"
                }]
            },
            {
                "featureType": "administrative.land_parcel",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#bdbdbd"
                }]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#eeeeee"
                }]
            },
            {
                "featureType": "poi",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#757575"
                }]
            },
            {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#e5e5e5"
                }]
            },
            {
                "featureType": "poi.park",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9e9e9e"
                }]
            },
            {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#ffffff"
                }]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#757575"
                }]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#dadada"
                }]
            },
            {
                "featureType": "road.highway",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#616161"
                }]
            },
            {
                "featureType": "road.local",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9e9e9e"
                }]
            },
            {
                "featureType": "transit.line",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#e5e5e5"
                }]
            },
            {
                "featureType": "transit.station",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#eeeeee"
                }]
            },
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [{
                    "color": "#c9c9c9"
                }]
            },
            {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#9e9e9e"
                }]
            }
            ]
        });

        var marker = new google.maps.Marker({
            position: {
                lat: latitude,
                lng: longitude
            },
            map: map,
            draggable: true
        });

        var searchBox = new google.maps.places.SearchBox(document.getElementById(searchId_1));
        google.maps.event.addListener(searchBox, 'places_changed', function () {
            var places = searchBox.getPlaces();
            var bounds = new google.maps.LatLngBounds();
            var i, place;
            for (i = 0; place = places[i]; i--) {
                bounds.extend(place.geometry.location);
                marker.setPosition(place.geometry.location);
            }

            map.fitBounds(bounds);
            map.setZoom(10);

        });

        google.maps.event.addListener(marker, 'position_changed', function () {

            var lat = marker.getPosition().lat();
            var lng = marker.getPosition().lng();
            document.getElementById(latElement_1).value = lat;
            document.getElementById(lngElement_1).value = lng;
        });
    }
</script>
@endpush