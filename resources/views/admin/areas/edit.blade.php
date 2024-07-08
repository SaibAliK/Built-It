@extends('admin.layouts.app')
@section('breadcrumb')
    @include('admin.common.breadcrumbs')
@endsection

@push('stylesheet-page-level')
@endpush

@push('script-page-level')
    <!-- Map code for areas -->
    <script>
        let id = "{{$area->id}}";
        var latitude = {{isset($area->latitude)?$area->latitude: 25.204841}};
        var longitude = {{isset($area->longitude)?$area->longitude:55.270896}};
        var mapId = 'map';
        var searchId = 'address';
        var latElement = 'latitude';
        var lngElement = 'longitude';
        var polygon = '{!! old('polygon',$area->polygon) !!}'
        var map; // Global declaration of the map
        var drawingManager;

        function initialize() {
            var myLatlng = new google.maps.LatLng(latitude, longitude);
            var myOptions = {
                zoom: 13,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }

            map = new google.maps.Map(document.getElementById(mapId), myOptions);
            drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_CENTER,
                    drawingModes: [
                        google.maps.drawing.OverlayType.POLYGON
                    ]
                },
                polygonOptions: {
                    editable: true,
                }
            });
            drawingManager.setMap(map);

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
                $("#latitude").val(lat);
                $("#longitude").val(lng);
            });

            google.maps.event.addListener(marker, 'dragend', function () {
                var lat = marker.getPosition().lat();
                var lng = marker.getPosition().lng();
                const latlng = {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng),
                };
                $("#latitude").val(latlng.lat);
                $("#longitude").val(latlng.lng);
                // document.getElementById("latitude").value = latlng.lat;
                // document.getElementById("longitude").value = latlng.lng;

                console.log();
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

            google.maps.event.addListener(drawingManager, "overlaycomplete", function (event) {
                var newShape = event.overlay;
                newShape.type = event.type;
            });

            google.maps.event.addListener(drawingManager, "overlaycomplete", function (event) {
                overlayClickListener(event.overlay);
                // console.log(event.overlay.getPath().getArray())
                let markers = event.overlay.getPath().getArray();
                let markersArray = [];
                for (let marker of markers) {
                    let markerObject = {};
                    markerObject.lat = marker.lat();
                    markerObject.lng = marker.lng();
                    markersArray.push(markerObject)
                }
                console.log(JSON.stringify(markersArray));
                $('#vertices').val(JSON.stringify(markersArray));
                $('#polygon').val(JSON.stringify(markersArray));
            });

        }

        function overlayClickListener(overlay) {
            google.maps.event.addListener(overlay, "mouseup", function (event) {
                $('#vertices').val(overlay.getPath().getArray());
            });
        }

        function initializePolygon() {
            map = new google.maps.Map(document.getElementById(mapId), {
                center: {lat: latitude, lng: longitude},
                zoom: 13,
                mapTypeId: 'roadmap'
            });


            var myLatlng = new google.maps.LatLng(latitude, longitude);

            var myOptions = {
                zoom: 13,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            }

            //draw polygon on edit.
            const triangleCoords = JSON.parse(polygon);
            console.log(triangleCoords);

            // Construct the polygon.
            const bermudaTriangle = new google.maps.Polygon({
                paths: triangleCoords,
                strokeColor: "#FF0000",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#FF0000",
                fillOpacity: 0.35,
            });
            console.log(bermudaTriangle);
            bermudaTriangle.setMap(map);
            console.log(map);
            var marker2 = new google.maps.Marker({
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
                    marker2.setPosition(place.geometry.location);
                }

                map.fitBounds(bounds);
                map.setZoom(15);

            });

            google.maps.event.addListener(marker2, 'position_changed', function () {

                var lat = marker2.getPosition().lat();
                var lng = marker2.getPosition().lng();
                $("#latitude").val(lat);
                $("#longitude").val(lng);
            });

            google.maps.event.addListener(marker2, 'dragend', function () {
                var lat = marker2.getPosition().lat();
                var lng = marker2.getPosition().lng();
                const latlng = {
                    lat: parseFloat(lat),
                    lng: parseFloat(lng),
                };
                $("#latitude").val(latlng.lat);
                $("#longitude").val(latlng.lng);

                console.log();
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

        function recreateMap() {
            if (id > 0) {
                initializePolygon()
            } else {
                initialize()
            }
            // initializePolygon()
            // initialize()

            document.getElementById(searchId).value = '';
            if (polygon.length > 0) {
                $('#polygon').val(polygon);
                $('#vertices').val(polygon);
            }
        }

        function editMap() {
            initialize()
            document.getElementById(searchId).value = '';
            $('#polygon').val(JSON.stringify([]));
            $('#vertices').val(JSON.stringify([]));
        }

    </script>

    @if($area->id > 0)
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNcTmnS323hh7tSQzFdwlnB4EozA3lwcA&libraries=drawing,places&callback=initializePolygon"></script>
    @else
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBNcTmnS323hh7tSQzFdwlnB4EozA3lwcA&libraries=drawing,places&callback=initialize"></script>
    @endif

@endpush

@section('content')
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div class="m-portlet m-portlet--full-height m-portlet--tabs">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-tabs m-tabs m-tabs-line   m-tabs-line--left m-tabs-line--primary"
                            role="tablist">

                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#tab_en" role="tab"
                                   id="test1">
                                    <i class="flaticon-share m--hide"></i>
                                    English
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_en">
                        @include('admin.areas.form', ['languageId' => $locales['0']])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
