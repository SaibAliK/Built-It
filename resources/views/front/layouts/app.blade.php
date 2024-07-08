<!DOCTYPE html>
<html lang="en" class="mt-front">

<head>
    <meta charset="utf-8">
    <title>{!! __(config('settings.company_name')) !!}</title>

    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <link rel="icon" href="{{ asset('assets/front/img/Favicon.jpg') }}" type="image/gif">
    <!-- Main Stylesheet File -->

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>


    <link href="{{asset('assets/front/css/main.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css"/>
    <link href="{{asset('assets/front/slick/slick.css')}}" rel="stylesheet">
    <link href="{{asset('assets/front/slick/slick-theme.css')}}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css"
          rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css"/>
    <style>
        .error {
            color: #c60909 !important;
            font-size: 1.4rem;
        }
    </style>
    @stack('stylesheet-page-level')

</head>


<body class="rt">

<div id="main-app">
    <div>
        @include('front.common.header')
        @yield('content')
        @include('front.common.footer')
    </div>
</div>

<!-- end footer html -->

<script>
    window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
                'baseUrl' => url('/').'/'.config('app.locale')."/"/*url(config('app.locale')).'/'*/,
                'apiUrl' => url('/').'/'.config('app.locale')."/api/"/*url(config('app.locale')).'/'*/,
                'base' => env('APP_URL'),
                 'locale' => config('app.locale'),
                 'user_id' => (isset($userDate)?$userData['id']:''),
                'user_notification_setting' => (isset($userData)?$userData->settings:''),
                'user_token' => $userData['token'] ?? "",
                'websocketEndpoint' => env('PUSHER_APP_PATH'),
                'websocketPort'=>env('PUSHER_APP_PORT'),
                'websocketKey'=>env('PUSHER_APP_KEY'),
                'websocketCluster'=>env('PUSHER_APP_CLUSTER'),
                'is_user'=> isset($userData)?$userData->isUser():false,
                'is_logged_in'=> isset($userData),
                'session_id'=> session()->getId(),
                 'translations'=>  cache('translations')
            ]) !!};
</script>
<!-- JavaScript Libraries -->
{{--<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
        integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
{{-- <script src="{{asset('assets/front/js/jquery-3.5.1.slim.min.js')}}"></script> --}}
<script src="{{ asset('js/app.js') }}"></script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
<script src="{{asset('assets/front/js/popover.js')}}"></script>
<script src="{{asset('assets/front/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/front/slick/slick.min.js')}}"></script>
<script src="{{asset('assets/front/js/index.js')}}"></script>
<script src="{{asset('assets/front/js/custom.js')}}"></script>
<script src="{{ asset('assets/front/js/jquery.validate.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNcTmnS323hh7tSQzFdwlnB4EozA3lwcA&libraries=places&language={{ $locale }}&callback=initAutocomplete"></script>
<script src="{{ asset('assets/front/js/lightbox.min.js') }}"></script>


<script>
    $(document).ready(function () {
        $('.colorpicker').colorpicker();

        $('.js-example-basic-single').select2();
        $(".custom-select2").select2();
    });

    function getval(sel) {
        debugger;
        let url = `${window.Laravel.baseUrl}save-user-data`;
        let area_id = sel.value;
        $.ajax({
            url: url, // if you say $(this) here it will refer to the ajax call not $('.company2')
            data: {
                id: area_id,
            },
            type: 'GET',
            dataType: "json",

            success: function (result) {
                location.reload();

            }
        });
    }
</script>
<script>
    jQuery(document).ready(function ($) {
        initMap();
    });

    // Map JS code
    function initMap() {
        var CustomMapStyles = [
            {
                "featureType": "all",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "weight": "2.00"
                    }
                ]
            },
            {
                "featureType": "all",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "color": "#9c9c9c"
                    }
                ]
            },
            {
                "featureType": "all",
                "elementType": "labels.text",
                "stylers": [
                    {
                        "visibility": "on"
                    }
                ]
            },
            {
                "featureType": "landscape",
                "elementType": "all",
                "stylers": [
                    {
                        "color": "#000"
                    }
                ]
            },
            {
                "featureType": "landscape",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#ffffff"
                    }
                ]
            },
            {
                "featureType": "landscape.man_made",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#ffffff"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "all",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "all",
                "stylers": [
                    {
                        "saturation": -100
                    },
                    {
                        "lightness": 45
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#eeeeee"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#7b7b7b"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "labels.text.stroke",
                "stylers": [
                    {
                        "color": "#ffffff"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "all",
                "stylers": [
                    {
                        "visibility": "simplified"
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "all",
                "stylers": [
                    {
                        "color": "#46bcec"
                    },
                    {
                        "visibility": "on"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#c8d7d4"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#070707"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "labels.text.stroke",
                "stylers": [
                    {
                        "color": "#ffffff"
                    }
                ]
            }

        ];


        // The location of Uluru
        const uluru = {lat: -25.344, lng: 131.031};
        // The map, centered at Uluru
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 4,
            styles: CustomMapStyles,
            center: uluru,


        });
        // The marker, positioned at Uluru
        const marker = new google.maps.Marker({
            position: uluru,
            map: map,
        });
    }

    window.initMap = initMap;
</script>
<script>
    jQuery.extend(jQuery.validator.messages, {
        required: "{{__('This field is required.')}}",
        email: "{{__('Please enter a valid email address.')}}",
        url: "{{__('Please enter a valid URL.')}}",
        date: "{{__('Please enter a valid date.')}}",
        number: "{{__('Please enter a valid number.')}}",
        digits: "{{__('Please enter only digits.')}}",
        tel: "{{__('Please enter only digits.')}}",
        equalTo: "{{__('Please enter the same value again.')}}",
        accept: "{{__('Please enter a value with a valid extension.')}}",
        maxlength: jQuery.validator.format("{{__('Please enter no more than')}} {0} {{__('characters.')}}"),
        minlength: jQuery.validator.format("{{__('Please enter at least')}} {0} {{__('characters.')}}"),
        max: jQuery.validator.format("{{__('Please enter a value less than or equal to {0}.')}}"),
        min: jQuery.validator.format("{{__('Please enter a value greater than or equal to {0}.')}}")
    });

    $.validator.addMethod("noSpace", function (value, element) {
        return this.optional(element) || value === "NA" ||
            value.match(/\S/);
    }, "This field cannot be empty");


    jQuery.validator.addMethod("tel", function (value, element) {
        return this.optional(element) || /^(?:\+|00)[0-9]+$/.test(value);
    }, "{{ __('Please enter international number starting with + sign OR 00.') }}");


    jQuery.validator.methods.email = function (value, element) {
        return this.optional(element) || /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/i.test(value);
    }

    jQuery.validator.setDefaults({
        errorPlacement: function (error, element) {
            error.insertAfter(element.parent());
        }
    });

</script>

<script>

    var locale = "{{app()->getLocale()}}";
    var currency = "{{$currency}}";
    var isEnableRtl = "{{$locale}}" == 'en' ? false : true;

    $(document).ready(function () {

        $(document).on('click', '.cartBtn', function (e) {
            e.preventDefault();
            $(this).closest('form').submit()

        });

        toastr.options = {
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "rtl": isEnableRtl,
            "closeButton": false
        }
        if ("{!! session()->has('status') !!}") {
            toastr.success('{{__('success')}}', "{!! session()->get('status') !!}")
        }
        if ("{!! session()->has('err') !!}") {
            toastr.error('{{__('alert')}}', "{!! session()->get('err') !!}")
        }
    });

</script>
<script>
    function imageUrl(path, width, height, quality, crop) {
        if (typeof (width) === 'undefined')
            width = null;
        if (typeof (height) === 'undefined')
            height = null;
        if (typeof (quality) === 'undefined')
            quality = null;
        if (typeof (crop) === 'undefined')
            crop = null;

        var basePath = window.Laravel.base;
        var url = null;
        if (!width && !height) {
            url = path;
        } else {
            // url = basePath + '/images/timthumb.php?src=' +basePath+ path; // IMAGE_LOCAL_PATH
            url = basePath + '/images/timthumb.php?src=' + path; // IMAGE_LIVE_PATH
            if (width !== null) {
                url += '&w=' + width;
            }
            if (height !== null && height > 0) {
                url += '&h=' + height;
            }
            if (crop !== null) {
                url += "&zc=" + crop;
            } else {
                url += "&zc=1";
            }
            if (quality !== null) {
                url += '&q=' + quality + '&s=1';
            } else {
                url += '&q=95&s=1';
            }
        }
        return url;
    }

    function jsTranslate(data) {

        if (locale == 'ar' && data['ar'] !== undefined && data['ar'] != '') {
            return data['ar']
        } else {
            return data['en'];
        }
        return data['en']

    }

    function imageUrlJs(path, width, height, quality, crop) {
        if (typeof (width) === 'undefined')
            width = null;
        if (typeof (height) === 'undefined')
            height = null;
        if (typeof (quality) === 'undefined')
            quality = null;
        if (typeof (crop) === 'undefined')
            crop = null;

        var basePath = window.Laravel.base;
        var url = null;
        if (!width && !height) {
            url = path;
        } else {
            if (path.includes(basePath)) {
                path = path.replace(basePath, '');
            }
            url = basePath + '/images/timthumb.php?src=' + path;
            if (width !== null) {
                url += '&w=' + width;
            }
            if (height !== null && height > 0) {
                url += '&h=' + height;
            }
            if (crop !== null) {
                url += "&zc=" + crop;
            } else {
                url += "&zc=1";
            }
            if (quality !== null) {
                url += '&q=' + quality + '&s=1';
            } else {
                url += '&q=95&s=1';
            }
        }
        return url;
    }

    function getStarRating($value) {
        $star_rate = $value;
        if ($value > 0 && $value < 0.5) {
            $star_rate = 0.5;
        }
        if ($value > 0.5 && $value < 1) {
            $star_rate = 1.0;
        }
        if ($value > 1 && $value < 1.5) {
            $star_rate = 1.5;
        }
        if ($value > 1.5 && $value < 2) {
            $star_rate = 2.0;
        }
        if ($value > 2 && $value < 2.5) {
            $star_rate = 2.5;
        }
        if ($value > 2.5 && $value < 3) {
            $star_rate = 3.0;
        }
        if ($value > 3 && $value < 3.5) {
            $star_rate = 3.5;
        }
        if ($value > 3.5 && $value < 4) {
            $star_rate = 4;
        }
        if ($value > 4 && $value < 4.5) {
            $star_rate = 4.5;
        }
        if ($value > 4.5 && $value < 5) {


            $star_rate = 5.0;
        }
        if ($value > 5) {
            $star_rate = 5.0;
        }

        return $star_rate;
    }
</script>
@stack('scripts')
</body>

</html>

