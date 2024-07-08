<form id="modalForm" action="{{route('front.dashboard.address.store',$data->id ?? 0)}}" method="post">
    @csrf
    <div class="modal-body px-0">
        <div class="inputs-border-wrapper mb-25">
            <div class="input-style">
                <div class="wrapper-input">
                        <span class="icon-front-input">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14.425" height="14.425"
                               viewBox="0 0 14.425 14.425">
                              <path id="Icon_awesome-user-alt" data-name="Icon awesome-user-alt"
                                    d="M7.212,8.114A4.057,4.057,0,1,0,3.155,4.057,4.058,4.058,0,0,0,7.212,8.114Zm3.606.9H9.266a4.9,4.9,0,0,1-4.108,0H3.606A3.606,3.606,0,0,0,0,12.622v.451a1.353,1.353,0,0,0,1.352,1.352h11.72a1.353,1.353,0,0,0,1.352-1.352v-.451A3.606,3.606,0,0,0,10.819,9.016Z"
                                    fill="#45cea2"/>
                            </svg>
                        </span>
                    <input type="text" class="ctm-input" name="name"
                           required
                           value="{{empty(old('name'))? (!empty($data->name)?$data->name:old('name')) :old('name')}}"
                           placeholder="{{__('Name*')}}">
                </div>
                @include('front.common.alert', ['input' => 'name'])
            </div>
            <div class="input-style">
                <div class="wrapper-input">
                        <span class="icon-front-input">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14.375" height="14.375"
                               viewBox="0 0 14.375 14.375">
                              <path id="Icon_awesome-phone-alt" data-name="Icon awesome-phone-alt"
                                    d="M13.965,10.158,10.82,8.811A.674.674,0,0,0,10.034,9l-1.393,1.7A10.407,10.407,0,0,1,3.667,5.731l1.7-1.393a.672.672,0,0,0,.194-.786L4.214.408a.678.678,0,0,0-.772-.39L.522.691A.674.674,0,0,0,0,1.348,13.026,13.026,0,0,0,13.028,14.375a.674.674,0,0,0,.657-.522l.674-2.92a.682.682,0,0,0-.393-.775Z"
                                    transform="translate(0 0)" fill="#45cea2"/>
                            </svg>


                        </span>
                    <input type="text" name="user_phone" required
                           value="{{empty(old('user_phone'))? (!empty($data->user_phone)?$data->user_phone:old('user_phone')) :old('user_phone')}}"
                           id="user_phone" class="ctm-input" placeholder="{{__('Phone No*')}}">
                </div>
                @include('front.common.alert', ['input' => 'user_phone'])
            </div>

            <!-- select 2 -->
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

                    <select class="js-example-basic-single city_ids"
                            id="city" name="city_id" required>
                        <option selected="true" disabled="disabled" value="">
                            {{ __('Select City') }}</option>
                        @forelse($cities as $city)
                            <option
                                value="{{$city->id}}"
                                {{empty(old('city_id')) ? (!empty($data->city_id ) ? ($data->city_id == $city->id ? "selected" : '') : '') :old('city_id')}}>{{translate($city->name)}}</option>
                        @empty
                            <option selected disabled="disabled"
                                    value="">{{__('No City have been created')}}</option>
                        @endforelse
                    </select>
                </div>
                <div class="cityIDError"></div>
                @error('city_id')
                <label id="city_id-error" class="error" for="full_name">{{ $message }}</label>
                @enderror
            </div>
        @include('front.common.alert', ['input' => 'city_id'])

        <!-- select 2 -->
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
                    <select class="custom-select2" name="area_id" id="area" required>
                        <option selected disabled="disabled" value="">
                            {{ __('Select Delivery Areas') }}</option>
                        @forelse($areas as $area)
                            <option value="{{ $area->id }}"
                                {{ empty(old('area_id')) ? (!empty($data->area_id) ? ($data->area_id == $area->id ? 'selected' : '') : '') : old('area_id') }}>
                                {{ translate($area->name) ?? '' }}</option>
                        @empty
                            <option value="" disabled selected>{{ __('Select Category') }}
                            </option>
                        @endforelse
                    </select>
                </div>
            </div>


            <div class="input-style">
                <div class="wrapper-input" data-target="#map-model-address" data-toggle="modal">
                        <span class="icon-front-input">
                          <svg xmlns="http://www.w3.org/2000/svg" width="13.5" height="18" viewBox="0 0 13.5 18">
                              <path id="Path_48396" data-name="Path 48396"
                                    d="M-.7,1.9A.807.807,0,0,0,0,2.25.807.807,0,0,0,.7,1.9L3.059-1.477q1.758-2.531,2.32-3.41a9.9,9.9,0,0,0,1.09-2.127A6.4,6.4,0,0,0,6.75-9a6.515,6.515,0,0,0-.914-3.375,6.879,6.879,0,0,0-2.461-2.461A6.515,6.515,0,0,0,0-15.75a6.515,6.515,0,0,0-3.375.914,6.879,6.879,0,0,0-2.461,2.461A6.515,6.515,0,0,0-6.75-9a6.4,6.4,0,0,0,.281,1.986,9.9,9.9,0,0,0,1.09,2.127q.563.879,2.32,3.41Q-1.617.563-.7,1.9ZM0-6.187a2.708,2.708,0,0,1-1.986-.826A2.708,2.708,0,0,1-2.812-9a2.708,2.708,0,0,1,.826-1.986A2.708,2.708,0,0,1,0-11.812a2.708,2.708,0,0,1,1.986.826A2.708,2.708,0,0,1,2.813-9a2.708,2.708,0,0,1-.826,1.986A2.708,2.708,0,0,1,0-6.187Z"
                                    transform="translate(6.75 15.75)" fill="#45cea2"/>
                            </svg>
                        </span>
                    <input type="text" class="address ctm-input auth-input adjus-ad"
                           name="address" required
                           placeholder="{{ __('Address') }}" id="address" readonly
                           value="{{ empty(old('address')) ? (!empty($data->address) ? $data->address:old('address')) :old('address') }}"
                           data-target="#register-map-model" data-toggle="modal"
                           data-latitude="#latitude" data-longitude="#longitude"
                           data-address="#address">
                </div>
                <input type="hidden" name="latitude" id="latitude-4" class="latitude"
                       value="{{empty(old('latitude'))? (!empty($data->latitude)?$data->latitude:old('latitude')) :old('latitude')}}">
                <input type="hidden" name="longitude" id="longitude-4" class="longitude"
                       value="{{empty(old('longitude'))? (!empty($data->longitude)?$data->longitude:old('longitude')) :old('longitude')}}">
                @include('front.common.alert', ['input' => 'address'])
            </div>

            <div class="input-style">
                <div class="wrapper-input">
                               <span class="icon-front-input">
                          <svg xmlns="http://www.w3.org/2000/svg" width="15.5" height="15.5" viewBox="0 0 15.5 15.5">
                              <path id="Path_48396" data-name="Path 48396"
                                    d="M0-13.75A7.513,7.513,0,0,1,3.875-12.7,7.831,7.831,0,0,1,6.7-9.875,7.513,7.513,0,0,1,7.75-6,7.513,7.513,0,0,1,6.7-2.125,7.831,7.831,0,0,1,3.875.7,7.513,7.513,0,0,1,0,1.75,7.513,7.513,0,0,1-3.875.7,7.831,7.831,0,0,1-6.7-2.125,7.513,7.513,0,0,1-7.75-6,7.513,7.513,0,0,1-6.7-9.875,7.831,7.831,0,0,1-3.875-12.7,7.513,7.513,0,0,1,0-13.75Zm0,3.438a1.261,1.261,0,0,0-.922.391A1.261,1.261,0,0,0-1.312-9a1.261,1.261,0,0,0,.391.922A1.261,1.261,0,0,0,0-7.687a1.261,1.261,0,0,0,.922-.391A1.261,1.261,0,0,0,1.313-9a1.261,1.261,0,0,0-.391-.922A1.261,1.261,0,0,0,0-10.312ZM1.75-2.375v-.75a.362.362,0,0,0-.109-.266A.362.362,0,0,0,1.375-3.5H1V-6.625a.362.362,0,0,0-.109-.266A.362.362,0,0,0,.625-7h-2a.362.362,0,0,0-.266.109.362.362,0,0,0-.109.266v.75a.362.362,0,0,0,.109.266.362.362,0,0,0,.266.109H-1v2h-.375a.362.362,0,0,0-.266.109.362.362,0,0,0-.109.266v.75a.362.362,0,0,0,.109.266A.362.362,0,0,0-1.375-2h2.75a.362.362,0,0,0,.266-.109A.362.362,0,0,0,1.75-2.375Z"
                                    transform="translate(7.75 13.75)" fill="#45cea2"/>
                            </svg>
                        </span>
                    <input name="address_description" id="address_description" required
                           placeholder="{{ __('Write here') }}"
                           value="{{ empty(old('address_description')) ? (!empty($data->address_description) ? $data->address_description : old('address_description')) : old('address_description') }}"
                           class="ctm-input">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        @if(!empty($data->id))
            <button type="button" id="submitModal" class="btn btn-primary w-100">{{__('Update')}}</button>
        @else
            <button type="button" id="submitModal" class="btn btn-primary w-100">{{__('Add')}}</button>
        @endif
    </div>
</form>

@push('stylesheet-end')
    <style>
        .pac-container {
            z-index: 9999 !important;
        }
    </style>
@endpush
@push('scripts')
    <script>

        $(document).ready(function () {

            $('#modalForm').validate({
                ignore: '',
                rules: {
                    'name': {
                        required: true,
                        noSpace: true
                    },
                    'address': {
                        required: true,
                        noSpace: true
                    },
                    'city_id': {
                        required: true,
                        noSpace: true
                    },
                    'area_id': {
                        required: true,
                        noSpace: true
                    },
                    'address_description': {
                        required: true,
                        noSpace: true
                    },
                    'user_phone': {
                        required: true,
                        noSpace: true,
                        maxlength: 14
                    }
                },
                errorPlacement: function (error, element) {
                    console.log(element.attr('name'));
                    if (element.attr("name") == "terms_conditions") {
                        error.insertAfter(element.parent().siblings());
                    } else if (element.attr("name") == "city_id") {
                        $(".cityIDError").html(error);
                    } else if (element.attr("name") == "area_id") {
                        $(".areaIDError").html(error);
                    } else {
                        error.insertAfter(element);
                    }
                },
            });

            $(document).on('click', '#submitModal', function (e) {
                if ($("#modalForm").valid()) {
                    let lat_val = $("#latitude").val();
                    if (lat_val === "") {
                        $(".below_address_field").html(
                            '<label id="address-error" class="error" for="address">{{ __('Please select a valid address from the address from the map.') }}</label>'
                        );
                    } else {
                        $(this).prop('disabled', true);
                        e.preventDefault();
                        $("#modalForm").submit();
                    }
                }
            });

        });

        $(".custom-select2").select2();
        $(document).ready(function () {
            $('#shipping-address-form').validate();
        })
    </script>

@endpush
