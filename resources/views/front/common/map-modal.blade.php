@push('stylesheet-end')
    <style>
        .pac-container {
            z-index: 9999 !important;
        }


    </style>
@endpush
<div class="modal fade size-chart-modal" id="register-map-model" tabindex="-1" role="dialog"
     aria-labelledby="product-detail-modalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content px-20 py-20">

            <div class="row">
                <div class="col-md-12 mb-25">
                    <h3 class="change-pass-tittle">{{__('Address')}}</h3>
                    <div class="input-style">
                        <div class="type-pass">
                            <span class="icon-front-input">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="17.006" height="19.435"
                                                 viewBox="0 0 17.006 19.435">
                                              <path id="Icon_awesome-lock" data-name="Icon awesome-lock"
                                                    d="M15.184,8.5h-.911V5.77a5.77,5.77,0,0,0-11.54,0V8.5H1.822A1.822,1.822,0,0,0,0,10.325v7.288a1.822,1.822,0,0,0,1.822,1.822H15.184a1.823,1.823,0,0,0,1.822-1.822V10.325A1.823,1.823,0,0,0,15.184,8.5Zm-3.948,0H5.77V5.77a2.733,2.733,0,0,1,5.466,0Z"
                                                    fill="#45cea2"></path>
                                            </svg>


                                          </span>
                            <input type="text" name="address" id="model-address"
                                   value="{{old('address')}}" dir="ltr"
                                   placeholder="{{__('Please select address')}}" required class="ctm-input border-full-cst">
                            <input type="hidden" name="latitude" id="model-latitude" class="latitude"
                                   value="{{old('latitude')}}">
                            <input type="hidden" name="longitude" id="model-longitude" class="longitude"
                                   value="{{old('longitude')}}">
                        </div>
                    </div>

                </div>
            </div>

            <div class="map-mark-btn-relative">
                <div id="model-map" class="map-height-hah" style="height: 400px">
                </div>
                <div class="map-mark-btn">
                    <a type="button" class="marker" id="mark" onclick="getCurrentPosition()">
                        <img src="{{ asset('assets/front/img/target.png') }}" class="img-fluid">
                    </a>
                </div>
            </div>

            <div class="d-flex justify-content-between modal-map-buttonss">
                <button type="button" onclick="saveMapInformation()" class="btn btn-secondary " class="close"
                        data-dismiss="modal" aria-label="Close">
                    <div class="">
                        <span>
                           {{__('confirm')}}
                        </span>
                    </div>
                </button>

                <button data-dismiss="modal" class="btn btn-primary">
                    <span>{{__('choose')}} </span>
                </button>

            </div>

        </div>
    </div>
</div>


@push('scripts')
    <script>
        var latitude = {{config('settings.latitude')}};
        var longitude = {{config('settings.longitude')}};
        var invokerElement;
        $(document).ready(function () {
            $('#register-map-model').on('show.bs.modal', function (event) {
                console.log("this is the invoker element =>", event.relatedTarget);
                console.log("this is the invoker element =>", $(event.relatedTarget).data('latitude'));
                invokerElement = $(event.relatedTarget);
                latitude = ($(invokerElement.data('latitude')).val()).length > 0 ? $(invokerElement.data(
                    'latitude')).val() : latitude;
                longitude = ($(invokerElement.data('longitude')).val()).length > 0 ? $(invokerElement.data(
                    'longitude')).val() : longitude;
                console.log(latitude, longitude);
                $('#model-address').val($(invokerElement.data('address')).val());
                initAutocomplete();
            });
        });

        var mapId = 'model-map';
        var searchId = 'model-address';
        var latElement = 'model-latitude';
        var lngElement = 'model-longitude';
        var allowGeoRecall = true;

        function getCurrentPosition() {
            $('#address').val('');
            $('#latitude').val('');
            $('#longitude').val('');
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, positionError);

            } else {
                toastr.error("Sorry, your browser does not support HTML5 geolocation.");
            }
        }

        function positionError() {
            toastr.error('Geolocation is not enabled. Please enable to use this feature');
        }

        function showPosition(position) {
            console.log('posiiton accepted')
            var google_map_pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
            var google_maps_geocoder = new google.maps.Geocoder();
            google_maps_geocoder.geocode(
                {'latLng': google_map_pos},
                function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK && results[0]) {
                        $('#model-address').val(results[0].formatted_address);
                        lat = [position.coords.latitude];
                        long = [position.coords.longitude];
                        latitude = lat;
                        longitude = long;
                        initAutocomplete();
                    }
                }
            );
            allowGeoRecall = false;
        }

        function initAutocomplete() {
            var map = new google.maps.Map(document.getElementById(mapId), {
                center: {lat: parseFloat(latitude), lng: parseFloat(longitude)},
                zoom: 13,
                mapTypeId: 'roadmap'
            });
            var marker = new google.maps.Marker({
                position: {
                    lat: parseFloat(latitude), lng: parseFloat(longitude)
                },
                map: map,
                draggable: true
            });
            var searchBox = new google.maps.places.SearchBox(document.getElementById(searchId));
            google.maps.event.addListener(searchBox, 'places_changed', function () {

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
            google.maps.event.addListener(marker, 'position_changed', function () {
                var lat = marker.getPosition().lat();
                var lng = marker.getPosition().lng();
                document.getElementById(latElement).value = lat;
                document.getElementById(lngElement).value = lng;
            });
            google.maps.event.addListener(marker, 'dragend', function () {
                var lat = marker.getPosition().lat();
                var lng = marker.getPosition().lng();
                const latlng = {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng),
                };

                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({location: latlng}, (results, status) => {
                    if (status === "OK") {
                        if (results[0]) {
                            document.getElementById(searchId).value = results[0].formatted_address
                        } else {
                            window.alert("No results found");
                        }
                    } else {
                        window.alert("Geocoder failed due to: " + status);
                    }
                });
            });

        }
    </script>

    <script>
        function saveMapInformation() {
            $(invokerElement.data('latitude')).val($('#model-latitude').val());
            $(invokerElement.data('longitude')).val($('#model-longitude').val());
            $(invokerElement.data('address')).val($('#model-address').val());
            $('#register-map-model').modal('toggle');
        }
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNcTmnS323hh7tSQzFdwlnB4EozA3lwcA&libraries=places&language={{$locale}}&callback=initAutocomplete"></script>

@endpush
