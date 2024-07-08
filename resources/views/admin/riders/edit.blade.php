@extends('admin.layouts.app')
@section('breadcrumb')
    @include('admin.common.breadcrumbs')
@endsection

@push('stylesheet-page-level')
@endpush

@push('script-page-level')
    <script>
        jQuery.validator.addMethod("tel", function(value, element) {
            // allow any non-whitespace characters as the host part
            // return this.optional(element) || /((\(\d{3,4}\)|\d{3,4}-)\d{4,9}(-\d{1,5}|\d{0}))|(\d{4,12})/.test(value);
            return this.optional(element) || /^(?:\+)[0-9]/.test(value);
            // return this.optional(element) || /^((?:[+?+]+)(?:\s?\d{0})(?:\s?\d{9}))$/.test(value);
        }, "Please enter international number starting with + sign.");

        $('#storeForm').validate({

            ignore: '',

            rules: {
                phone: {
                    required: true,
                    tel: true
                },
            },
        });
    </script>

    <script>
        function geocodePosition(pos) {
            geocoder.geocode({
                latLng: pos
            }, function(responses) {
                if (responses && responses.length > 0) {
                    $("#searchmap").val(responses[0].formatted_address);
                }
            });
        }

        var latitude = {!! $user->id > 0 ? (empty($user->latitude) ? 31.55460609999999 : $user->latitude) : 31.55460609999999 !!};
        var longitude = {!! $user->id > 0 ? (empty($user->longitude) ? 74.35715809999999 : $user->longitude) : 74.35715809999999 !!};

        function initAutocomplete(mapId, searchId, long, latit) {
            var map = new google.maps.Map(document.getElementById(mapId), {
                center: {
                    lat: latitude,
                    lng: longitude
                },
                zoom: 13,
                mapTypeId: 'roadmap'
            });
            var marker = new google.maps.Marker({
                position: {
                    lat: latitude,
                    lng: longitude
                },
                map: map,
                draggable: true
            });
            var searchBox = new google.maps.places.SearchBox(document.getElementById(searchId));
            google.maps.event.addListener(searchBox, 'places_changed', function() {

                var places = searchBox.getPlaces();
                var bounds = new google.maps.LatLngBounds();
                var i, place;

                for (i = 0; place = places[i]; i--) {
                    bounds.extend(place.geometry.location);
                    marker.setPosition(place.geometry.location);
                }

                map.fitBounds(bounds);
                map.setZoom(15);

            });

            google.maps.event.addListener(marker, 'position_changed', function() {

                var lat = marker.getPosition().lat();
                var lng = marker.getPosition().lng();
                geocodePosition(marker.getPosition());
                document.getElementById(latit).value = lat;
                document.getElementById(long).value = lng;

            });
        }

        $(document).ready(function() {
            setTimeout(function() {
                initAutocomplete('map', 'searchmap', 'longitude', 'latitude');
            }, 400);
        });
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNcTmnS323hh7tSQzFdwlnB4EozA3lwcA&libraries=places&callback=initAutocomplete">
    </script>
    <script>
        var geocoder = new google.maps.Geocoder();
    </script>
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div class="m-portlet m-portlet--full-height m-portlet--tabs  ">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-tabs m-tabs m-tabs-line   m-tabs-line--left m-tabs-line--primary" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#tab_en" role="tab"
                                    id="test1">
                                    <i class="flaticon-share m--hide"></i>
                                    Rider
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">

                    <div class="tab-pane active" id="tab_en">
                        @include('admin.riders.form')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
