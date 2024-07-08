@push('stylesheet-end')
    <style>
        .pac-container {
            z-index: 9999 !important;
        }
    </style>
@endpush

<div class="modal fade size-chart-modal" id="map-model-address" tabindex="-1" role="dialog"
     aria-labelledby="product-detail-modalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content px-20 py-20">

            <div class="col-12 mt-2">
                <div class="input-style position-relative mb-3">
                    <label class="d-flex">{{__('Address')}}<span class="text-danger">*</span></label>
                    <input type="text" name="address" id="model-address" readonly
                           value="{{ empty(old('address')) ? (!empty($data->address) ? $data->address:old('address')) :old('address') }}"
                           class="ctm-input text-left address-padds-register address" dir="ltr"
                           placeholder="{{ __('Please select address') }}" required>
                    <input type="hidden" name="latitude" id="model-latitude" class="latitude"
                           value="{{empty(old('latitude'))? (!empty($data->latitude)?$data->latitude:old('latitude')) :old('latitude')}}">
                    <input type="hidden" name="longitude" id="model-longitude" class="longitude"
                           value="{{empty(old('longitude'))? (!empty($data->longitude)?$data->longitude:old('longitude')) :old('longitude')}}">
                </div>

            </div>
            <div class="map-mark-btn-relative">
                <div id="map" class="map-height-hah" style="height: 400px">
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
                    <span>{{__('close')}} </span>
                </button>

            </div>

        </div>
    </div>
</div>


@push('scripts')


    <script>

        function saveMapInformation() {
            $('.latitude').val($('#model-latitude').val());
            $('.longitude').val($('#model-longitude').val());
            $('.address').val($('#model-address').val());
            $('#register-map-model').modal('toggle');
        }

        $(document).ready(function () {

            if ('{{!empty($data->id)}}') {
                debugger;
                getArea('{{$data->area_id}}')
            } else {
                $('#area_div').hide();
                $('.area-map').hide();
            }

            $('#city').on('change', function () {
                debugger;
                $('.area-map').hide();
                var id = $('#city').val();
                getCityArea(id)
            });
            $('#area').on('change', function () {
                $('.area-map').show();
            });
            $('#shipping-address-form1').validate();
            $('.edit-address').on('click', function () {
                var id = $(this).val();
                $.ajax({
                    url: window.Laravel.baseUrl + 'dashboard/get-address/' + id,
                    type: 'get',
                    datatype: 'html',
                })
                    .done(function (data) {
                        $('#edit-id').val(data['id'])
                        $('#edit-name').val(data['address_name'])
                        $('#edit-phone').val(data['user_phone'])
                        $('#address').val(data['address'])
                        $('#edit-details').text(data['address_description'])
                        $('#latitude').val(data['latitude'])
                        $('#longitude').val(data['longitude'])
                        getCityArea(data['city_id'])
                        $('#city').val(data['city_id']);
                        $('#city').trigger('change');
                        getArea(data['area_id']);
                        setTimeout(function () {
                            $('#area').val(data['area_id']);
                            $('#area').trigger('change');
                        }, 2000);
                    })
                    .fail(function (jqXHR, ajaxOptions, thrownError) {
                        alert('Something went wrong.');
                    });
            });

            $('#area').on('select2:select', function (e) {
                var id = $('#area').val();
                getArea(id);
                debugger;
            });

            //function here
            function getArea(id) {

                $.ajax({
                    url: window.Laravel.baseUrl + 'dashboard/area/' + id,
                    type: 'get',
                    datatype: 'html',
                })
                    .done(function (data) {
                        debugger;
                        initMap(data)
                    })
                    .fail(function (jqXHR, ajaxOptions, thrownError) {
                        alert('Something went wrong.');
                    });
            }

            function getCityArea(id) {
                debugger;
                $.ajax({
                    url: window.Laravel.apiUrl + 'city/' + id + '/' + '{{$store_id}}',
                    type: 'get',
                    datatype: 'html',
                })
                    .done(function (data) {
                        debugger;
                        console.log(data)
                        $('#area_div').show();
                        $('#area').html(data);
                    })
                    .fail(function (jqXHR, ajaxOptions, thrownError) {
                        alert('Something went wrong.');
                    });
            }

            function checkPolygon(count_point, polygon_x, polygon_y, lat, long) {
                let i = 0;
                let j = 0;
                let c = 0;
                for (i = 0, j = count_point; i < count_point; j = i++) {
                    if (((polygon_y[i] > lat != (polygon_y[j] > lat)) &&
                        (long < (polygon_x[j] - polygon_x[i]) * (lat - polygon_y[i]) / (polygon_y[j] -
                            polygon_y[i]) + polygon_x[i])))
                        c = !c;
                }
                console.log(c)
                return c;
            }

            function initMap(area) {

                debugger;
                let polygon = area.polygon;
                let polygon_x = polygon.map(latlng => latlng.lng)
                let polygon_y = polygon.map(latlng => latlng.lat)
                let count_point = polygon.length - 1;
                let lat = polygon[0].lat
                let lng = polygon[0].lng
                // console.log(polygon, polygon_x, polygon_y, count_point, lat, lng)
                if ({{$id}} > 0 && area.id == '{{$data->area_id}}') {
                    lat = parseFloat('{{$data->latitude}}');
                    lng = parseFloat('{{$data->longitude}}');
                }
                if (this.id > 0) {
                    lat = parseFloat(this.latitude)
                    lng = parseFloat(this.longitude)
                }
                //  var lastPosition = new google.maps.LatLng(lat, lng);
                // debugger
                var map = new google.maps.Map(
                    document.getElementById("map"), {
                        center: new google.maps.LatLng(lat, lng),
                        zoom: 13,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    });
                var marker = new google.maps.Marker({
                    position: {
                        lat: lat,
                        lng: lng
                    },
                    map: map,
                    draggable: true
                });
                const region = new google.maps.Polygon({
                    map: map,
                    clickable: false,
                    paths: polygon,
                });
                var bounds = new google.maps.LatLngBounds();
                for (let latlng of polygon) {
                    bounds.extend(new google.maps.LatLng(latlng.lat, latlng.lng));
                }
                map.fitBounds(bounds);
                setTimeout(() => {
                    map.setZoom(10)
                }, 100)
                let that = this
                google.maps.event.addListener(marker, 'dragend', function () {
                    debugger;
                    var position = marker.getPosition();

                    if (bounds.contains(position)) {
                        debugger;
                        lastPosition = position
                        var lat = marker.getPosition().lat();
                        var lng = marker.getPosition().lng();
                        const latlng = {
                            lat: parseFloat(lat),
                            lng: parseFloat(lng),
                        };
                        const geocoder = new google.maps.Geocoder();
                        geocoder.geocode({
                            location: latlng
                        }, (results, status) => {
                            if (status === "OK") {
                                if (results[0]) {
                                    debugger;
                                    that.address = results[0].formatted_address;
                                    that.latitude = marker.getPosition().lat();
                                    that.longitude = marker.getPosition().lng();
                                    $('#model-address').val(that.address)
                                    $('#model-latitude').val(that.latitude)
                                    $('#model-longitude').val(that.longitude)
                                } else {
                                    window.alert("No results found");
                                }
                            } else {
                                window.alert("Geocoder failed due to: " + status);
                            }
                        });
                    } else {
                        // map.setZoom(15);
                        marker.setPosition(lastPosition)

                    }

                });

            }


        })
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNcTmnS323hh7tSQzFdwlnB4EozA3lwcA&libraries=places&language={{ $locale }}">
    </script>

@endpush
