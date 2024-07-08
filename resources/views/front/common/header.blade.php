<header class="header-seven">
    <div class="wrapper-header-main">
        <div class="header-top-bar-parent">
            <div class="logo-main-acd">
                <img src="{{asset('assets/front/img/logo-seven.png')}}" class="img-fluid" alt="image">
            </div>
            <div class="mid-content-block-header">
                <div class="navigation-main-parent">
                    <div class="wrapper-navigation">
                        <ul class="ul-list-nav">
                            <li class="nav-links">
                                <a href="{{route('front.index')}}"
                                   class="{!! url()->current() == route('front.index') ? 'active' : '' !!}">{{__('home')}}</a>
                            </li>
                            <li class="nav-links ">
                                <a href="{{ route('front.products') }}"
                                   class="{!! url()->current() == route('front.products') ? 'active' : '' !!}">{{__('Products')}}</a>
                            </li>
                            <li class="nav-links">
                                <a href="{{route('front.categories')}}"
                                   class="{!! url()->current() == route('front.categories') ? 'active' : '' !!}">{{__('Categories')}}</a>
                            </li>
                            <li class="nav-links">
                                <a href="{{ route('front.suppliers') }}"
                                   class="{!! url()->current() == route('front.suppliers') ? 'active' : '' !!}">{{__('Stores')}}</a>
                            </li>
                            <li class="nav-link dropdown drop-down-morr">
                                <a class=" dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                                   role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{__('More')}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="9.682" height="5.646"
                                         viewBox="0 0 9.682 5.646">
                                        <path id="Path_49278" data-name="Path 49278"
                                              d="M11.948-26.519a.307.307,0,0,1,.1.223.307.307,0,0,1-.1.223L7.427-21.552a.307.307,0,0,1-.223.1.307.307,0,0,1-.223-.1L2.46-26.073a.307.307,0,0,1-.1-.223.307.307,0,0,1,.1-.223L2.945-27a.307.307,0,0,1,.223-.1.307.307,0,0,1,.223.1L7.2-23.192,11.016-27a.307.307,0,0,1,.223-.1.307.307,0,0,1,.223.1Z"
                                              transform="translate(-2.363 27.101)" fill="#333"/>
                                    </svg>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item"
                                       href="{{ route('front.pages', [config('settings.about_us')]) }}">{{__('About us')}}</a>
                                    <a class="dropdown-item"
                                       href="{{ route('front.pages', 'contact-us') }}">{{__('Contact us')}}</a>
                                    <a class="dropdown-item" href="{{route('front.gallery')}}">{{__('Gallery')}}</a>
                                    <a class="dropdown-item" href="{{route('front.articles')}}">{{__('Article')}}</a>
                                    <a class="dropdown-item" href="{{route('front.faqs')}}">{{__('Faqs')}}</a>
                                </div>
                            </li>

                            <!-- <li class="nav-link dropdown drop-down-morr">
                                <select class="select2" name="header_area"
                                        id="header-categories-select-2"
                                        onchange="getval(this);">
                                    <option value="">{{('Location')}}</option>
                                    @foreach ($cities as $city)
                                        <optgroup label="{{ translate($city->name) }}"></optgroup>

                                        @foreach ($city->areas as $area)
                                            <option value="{{ $area->id }}"
                                                {{ session()->get('area_id') == $area->id ? 'selected' : '' }}>
                                                {{ translate($area->name) }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </li> -->
                        </ul>
                        <div class="inner-wrapper-login-main">

                        <!-- new- code heree -->
                        <div class="slect-on-homeee">
                                <select class="select2" name="header_area"
                                        id="header-categories-select-2"
                                        onchange="getval(this);">
                                    <option value="">{{('Location')}}</option>
                                    @foreach ($cities as $city)
                                        <optgroup label="{{ translate($city->name) }}"></optgroup>

                                        @foreach ($city->areas as $area)
                                            <option value="{{ $area->id }}"
                                                {{ session()->get('area_id') == $area->id ? 'selected' : '' }}>
                                                {{ translate($area->name) }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                        </div>


                        <!-- end new code heree -->


                            @if (Auth::check())
                                @if(auth()->user()->isSupplier())
                                    <div class="butoon-header-s">
                                        <a href="{{route('front.auth.register.foam',['supplier'])}}"
                                           class="btn btn-primary">{{__('Become a Seller')}}</a>
                                    </div>
                                @endif
                            @endif
                            <div class="socila-bg-green-head">

                                @if (Auth::check())
                                    @if(auth()->user()->isUser())
                                        <div class="notification-block card-icon-home-ne">
                                            <div class="cart-box-main-p">
                                                <a href="{{route('front.dashboard.cart.index')}}">
                                                    <div class="cart-home-head-p">
                                                        <div class="icon-cart">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                 xmlns:xlink="http://www.w3.org/1999/xlink" width="64"
                                                                 height="64" viewBox="0 0 64 64">
                                                                <defs>
                                                                    <filter id="Rectangle_32" x="0" y="0" width="64"
                                                                            height="64"
                                                                            filterUnits="userSpaceOnUse">
                                                                        <feOffset dy="3" input="SourceAlpha"/>
                                                                        <feGaussianBlur stdDeviation="3" result="blur"/>
                                                                        <feFlood flood-opacity="0.161"/>
                                                                        <feComposite operator="in" in2="blur"/>
                                                                        <feComposite in="SourceGraphic"/>
                                                                    </filter>
                                                                </defs>
                                                                <g id="Component_2_1" data-name="Component 2 – 1"
                                                                   transform="translate(9 6)">
                                                                    <g transform="matrix(1, 0, 0, 1, -9, -6)"
                                                                       filter="url(#Rectangle_32)">
                                                                        <g id="Rectangle_32-2" data-name="Rectangle 32"
                                                                           transform="translate(9 6)" fill="#fff"
                                                                           stroke="#45cea2" stroke-width="1">
                                                                            <rect width="46" height="46" rx="8"
                                                                                  stroke="none"/>
                                                                            <rect x="0.5" y="0.5" width="45" height="45"
                                                                                  rx="7.5" fill="none"/>
                                                                        </g>
                                                                    </g>
                                                                    <path id="Path_44" data-name="Path 44"
                                                                          d="M24.75-9.969a.994.994,0,0,0-.3-.73,1,1,0,0,0-.73-.3H20.84l-4.6-6.316a1.329,1.329,0,0,0-.9-.559,1.345,1.345,0,0,0-1.031.258,1.329,1.329,0,0,0-.559.9,1.345,1.345,0,0,0,.258,1.031L17.445-11H7.3l3.438-4.684A1.345,1.345,0,0,0,11-16.715a1.329,1.329,0,0,0-.559-.9,1.345,1.345,0,0,0-1.031-.258,1.329,1.329,0,0,0-.9.559L3.91-11H1.031a.994.994,0,0,0-.73.3,1,1,0,0,0-.3.73v.688a1,1,0,0,0,.3.73.994.994,0,0,0,.73.3h.344L2.492-.387A2.056,2.056,0,0,0,3.2.881a2.023,2.023,0,0,0,1.354.494H20.2A2.023,2.023,0,0,0,21.549.881a2.056,2.056,0,0,0,.709-1.268L23.375-8.25h.344a1,1,0,0,0,.73-.3.994.994,0,0,0,.3-.73ZM13.406-2.406a1,1,0,0,1-.3.73,1,1,0,0,1-.73.3.994.994,0,0,1-.73-.3.994.994,0,0,1-.3-.73V-7.219a1,1,0,0,1,.3-.73,1,1,0,0,1,.73-.3,1,1,0,0,1,.73.3.994.994,0,0,1,.3.73Zm4.813,0a.994.994,0,0,1-.3.73,1,1,0,0,1-.73.3.994.994,0,0,1-.73-.3.994.994,0,0,1-.3-.73V-7.219a.994.994,0,0,1,.3-.73,1,1,0,0,1,.73-.3.994.994,0,0,1,.73.3.994.994,0,0,1,.3.73Zm-9.625,0a1,1,0,0,1-.3.73.994.994,0,0,1-.73.3,1,1,0,0,1-.73-.3,1,1,0,0,1-.3-.73V-7.219a1,1,0,0,1,.3-.73,1,1,0,0,1,.73-.3,1,1,0,0,1,.73.3,1,1,0,0,1,.3.73Z"
                                                                          transform="translate(11 31)" fill="#45cea2"/>
                                                                </g>
                                                            </svg>
                                                            <span class="counter-number-mian-box">
                                                        2
                                                    </span>
                                                        </div>
                                                        <div class="right-content-cart-head">
                                                            <h3 class="tittle-cart">{{__('Your Cart')}}
                                                            </h3>
                                                            <h3 class="price-total-show">20.00 AED</h3>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="notification-block">
                                        <div class="notification-home-pagee">
                                            <div class="dropdown eng-drop">
                                                <button type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                        aria-haspopup="true" aria-expanded="false" class="m-usd">
                                                    <a href="#" class="noti-anchor-home">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="34.043"
                                                             height="28.066" viewBox="0 0 16.043 20.066">
                                                            <path id="Path_58" data-name="Path 58"
                                                                  d="M17.021,30.994a2.364,2.364,0,0,0,2.412-2.658H14.6A2.365,2.365,0,0,0,17.021,30.994Z"
                                                                  transform="translate(-9.01 -10.928)" fill="#fff"/>
                                                            <path id="Path_59" data-name="Path 59"
                                                                  d="M22.6,18.79c-.772-1.018-2.292-1.615-2.292-6.174,0-4.679-2.066-6.56-3.992-7.011-.181-.045-.311-.105-.311-.3V5.164a1.224,1.224,0,1,0-2.447,0V5.31c0,.186-.13.251-.311.3-1.931.456-3.992,2.332-3.992,7.011,0,4.559-1.52,5.151-2.292,6.174a1,1,0,0,0,.8,1.595H21.808A1,1,0,0,0,22.6,18.79Z"
                                                                  transform="translate(-6.761 -3.93)" fill="#fff"/>
                                                        </svg>
                                                        <span class="counter-number"></span>
                                                    </a>
                                                </button>
                                                <notifications calledfrom="header"></notifications>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (Auth::check())
                                    <div class="login-register-head">
                                        <h2>
                                            <svg id="Component_5_1" data-name="Component 5 – 1"
                                                 xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 viewBox="0 0 16 16">
                                                <path id="Path_48395" data-name="Path 48395"
                                                      d="M8-5a4.343,4.343,0,0,1-2.25-.609A4.586,4.586,0,0,1,4.109-7.25,4.343,4.343,0,0,1,3.5-9.5a4.343,4.343,0,0,1,.609-2.25A4.586,4.586,0,0,1,5.75-13.391,4.343,4.343,0,0,1,8-14a4.343,4.343,0,0,1,2.25.609,4.586,4.586,0,0,1,1.641,1.641A4.343,4.343,0,0,1,12.5-9.5a4.343,4.343,0,0,1-.609,2.25A4.586,4.586,0,0,1,10.25-5.609,4.343,4.343,0,0,1,8-5Zm4,1a3.966,3.966,0,0,1,2.016.531,3.891,3.891,0,0,1,1.453,1.453A3.966,3.966,0,0,1,16,0V.5a1.447,1.447,0,0,1-.437,1.063A1.447,1.447,0,0,1,14.5,2H1.5A1.447,1.447,0,0,1,.438,1.563,1.447,1.447,0,0,1,0,.5V0A3.966,3.966,0,0,1,.531-2.016,3.891,3.891,0,0,1,1.984-3.469,3.966,3.966,0,0,1,4-4H5.719A5.427,5.427,0,0,0,8-3.5,5.427,5.427,0,0,0,10.281-4Z"
                                                      transform="translate(0 14)" fill="#fff"/>
                                            </svg>
                                            <a href="{{ route('front.auth.logout') }}"
                                               class="login-tittle">{{__('logout')}}</a>
                                            <span
                                                class="seprater-line"> /</span> <a
                                                href="{{ route('front.dashboard.index') }}"
                                                class="regis-tittle">{{__('Dashboard')}}</a>
                                        </h2>
                                    </div>
                                @else
                                    <div class="login-register-head">
                                        <h2>
                                            <svg id="Component_5_1" data-name="Component 5 – 1"
                                                 xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 viewBox="0 0 16 16">
                                                <path id="Path_48395" data-name="Path 48395"
                                                      d="M8-5a4.343,4.343,0,0,1-2.25-.609A4.586,4.586,0,0,1,4.109-7.25,4.343,4.343,0,0,1,3.5-9.5a4.343,4.343,0,0,1,.609-2.25A4.586,4.586,0,0,1,5.75-13.391,4.343,4.343,0,0,1,8-14a4.343,4.343,0,0,1,2.25.609,4.586,4.586,0,0,1,1.641,1.641A4.343,4.343,0,0,1,12.5-9.5a4.343,4.343,0,0,1-.609,2.25A4.586,4.586,0,0,1,10.25-5.609,4.343,4.343,0,0,1,8-5Zm4,1a3.966,3.966,0,0,1,2.016.531,3.891,3.891,0,0,1,1.453,1.453A3.966,3.966,0,0,1,16,0V.5a1.447,1.447,0,0,1-.437,1.063A1.447,1.447,0,0,1,14.5,2H1.5A1.447,1.447,0,0,1,.438,1.563,1.447,1.447,0,0,1,0,.5V0A3.966,3.966,0,0,1,.531-2.016,3.891,3.891,0,0,1,1.984-3.469,3.966,3.966,0,0,1,4-4H5.719A5.427,5.427,0,0,0,8-3.5,5.427,5.427,0,0,0,10.281-4Z"
                                                      transform="translate(0 14)" fill="#fff"/>
                                            </svg>
                                            <a href="{{ route('front.auth.login') }}"
                                               class="login-tittle">{{__('login')}}</a>
                                            <span
                                                class="seprater-line"> /</span> <a
                                                href="{{ route('front.auth.register') }}"
                                                class="regis-tittle">{{__('register')}}</a>
                                        </h2>
                                    </div>
                            @endif


                            <!-- end profile dropdown -->
                                <div class="inner-wrapper-language">
                                    <div class="languageee-ss">
                                        <div class="dropdown eng-drop">
                                            <button type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false" class="m-usd">
                                                <span class="none-mob-en">
                                          {{ $languages[$locale]['short_code'] }}
                                                </span>
                                                <i class="fas fa-caret-down"></i>
                                            </button>
                                            @php
                                                $language='';
                                                if ($locale =='ar') {
                                                    $language = 'العربية';
                                                } else {
                                                    $language = __('English');
                                                }
                                            @endphp
                                            <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                                                @foreach ($locales as $shortCode => $lang)
                                                    <a class="dropdown-item"
                                                       href="{{ $languages[$lang]['url'] }}">{{ $languages[$lang]['title'] }}</a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="languageee-ss">
                                        <div class="dropdown eng-drop cureeny-drop-ad">
                                            <button type="button" id="dropdownMenuButton" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false" class="m-usd">
                                                <span class="none-mob-en">
                                                    {{ session()->get('currency') ? session()->get('currency') : 'AED' }}
                                                </span>
                                                <i class="fas fa-caret-down"></i>
                                            </button>
                                            <div aria-labelledby="dropdownMenuButton" class="dropdown-menu">
                                                @forelse($currencies as $item)
                                                    <a class="dropdown-item"
                                                       href="{{route('front.change_currency',$item)}}">{{$item}}</a>
                                                @empty
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="menu-toggle-block-right">
                <div id="mySidenav" class="sidenav">
                    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
                    <div class="menu-toggle-ul">
                        <ul class="ul-list-nav">
                            <li class="nav-links">
                                <a href="{{route('front.index')}}" class="active">{{__('home')}}</a>
                            </li>
                            <li class="nav-links">
                                <a href="{{ route('front.products') }}">{{__('Products')}}</a>
                            </li>
                            <li class="nav-links">
                                <a href="{{route('front.categories')}}"
                                   class="{!! url()->current() == route('front.categories') ? 'active' : '' !!}">{{__('Categories')}}</a>
                            </li>
                            <li class="nav-links">
                                <a href="{{ route('front.suppliers') }}"
                                   class="{!! url()->current() == route('front.suppliers') ? 'active' : '' !!}">{{__('Stores')}}</a>
                            </li>

                            <li class="nav-links">
                                <a href="{{ route('front.pages', [config('settings.about_us')]) }}">{{__('About Us')}}</a>
                            </li>

                            <li class="nav-links">
                                <a href="{{ route('front.pages', 'contact-us') }}">{{__('Contact Us')}}</a>
                            </li>

                            <li class="nav-links">
                                <a href="{{route('front.gallery')}}">{{__('Gallery')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <span class="open-button-black-menu" onclick="openNav()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="68.885" height="27.819" viewBox="0 0 68.885 27.819">
                        <g id="Menu" transform="translate(-15 -45)">
                            <rect id="Rectangle_2464" data-name="Rectangle 2464" width="52.988" height="3.974"
                                  rx="1.987" transform="translate(15 68.845)" fill="#fff"/>
                            <rect id="Rectangle_2463" data-name="Rectangle 2463" width="52.988" height="3.974"
                                  rx="1.987" transform="translate(22.948 56.922)" fill="#fff"/>
                            <rect id="Rectangle_2462" data-name="Rectangle 2462" width="52.988" height="3.974"
                                  rx="1.987" transform="translate(30.896 45)" fill="#fff"/>
                        </g>
                    </svg>
                </span>
            </div>
        </div>
    </div>
</header>


@push('scripts')

    @if(\Auth::check())
        <script>
            var cart_count = @json($cartCount);
            if (cart_count != 0) {
                $('.counter-number-mian-box').html(cart_count);
            } else {
                $('.counter-number-mian-box').html(0);
            }


            $(document).ready(function () {
                getNotificationCount();
                window.Echo.channel(`seven-new-notification-` + window.Laravel.user_id)
                    .listen('.new-notification', (e) => {
                        getNotificationCount();
                        console.log('new-notification-event=>', e);
                    });
            });

            function getNotificationCount() {
                axios.get(`${window.Laravel.apiUrl}notifications-count`)
                    .then(response => {
                        if (response.data.success) {
                            let notificationCount = response.data.data.collection.notificationCount;
                            if (notificationCount > 0) {
                                $('.counter-number').html(notificationCount);
                            }
                            console.log("notification count=>", notificationCount)
                        } else {
                            console.error('Notifications Error =>', response)
                        }
                    })
            }

            function seenNotificationCount() {
                console.log("seen function is running ");
                axios.get(`${window.Laravel.apiUrl}notification-seen`)
                    .then(response => {
                        if (response.data.success) {
                            $('.counter-number').html(0);
                        } else {
                            console.error('Notifications Error =>', response)
                        }
                    })
            }

        </script>
    @endif
@endpush
