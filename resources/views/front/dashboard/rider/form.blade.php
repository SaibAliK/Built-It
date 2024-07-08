<div class="col-lg-12 col-md-12">
    <div class="box-shadow-cards-login change-pass-side-form-block">
        <form action="{{ route('front.dashboard.update.riders', ['id' => $id]) }}"
              id="ridersForm" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" value="{!! $user->id ?? 0 !!}" name="user_id">
            <input type="text" name="is_verified" value="1" hidden>
            <input type="text" name="company_id" value="{{ auth()->user()->id }}"
                   hidden>
            <input type="text" name="user_type" value="rider" hidden>

            <div class="row">
                <div class="col-md-12 mb-2">
                    <h3 class="manage-rider-edit-tittle-form mb-12">{{__('Confidential Info')}}</h3>
                    <div class="inputs-border-wrapper">
                        <div class="input-style">
                            <div class="wrapper-input">
                                <span class="icon-front-input">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="17.546" height="13.786"
                                         viewBox="0 0 17.546 13.786">
                                        <path id="Icon_ionic-md-mail" data-name="Icon ionic-md-mail"
                                              d="M18.125,5.625H3.921A1.676,1.676,0,0,0,2.25,7.3V17.74a1.676,1.676,0,0,0,1.671,1.671h14.2A1.676,1.676,0,0,0,19.8,17.74V7.3A1.676,1.676,0,0,0,18.125,5.625Zm-.209,3.551-6.893,4.6L4.13,9.176V7.5l6.893,4.6,6.893-4.6Z"
                                              transform="translate(-2.25 -5.625)" fill="#45cea2"></path>
                                    </svg>
                                </span>
                                <input class="ctm-input" type="email" name="email" value="{{ $rider->email }}"
                                       placeholder="{{ __('Email') }}" required>
                            </div>
                        </div>
                        @if ($id == 0)
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
                                    <input class="ctm-input" type="password" name="password"
                                           placeholder="{{ __('Password*') }}" required>
                                    <div class="icon-eye d-flex align-items-center justify-content-center">
                                        <h2 class="pass-show-tittle">{{__('Show')}}</h2>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if ($id != 0)
                            <input type="text" name="id" value="{{ $id }}" hidden>
                        @endif

                    </div>

                </div>
                <div class="col-12">
                    <h3 class="manage-rider-edit-tittle-form mb-12">{{__('Basic Info')}}</h3>
                    <div class="inputs-border-wrapper">
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
                                <input class="ctm-input" type="text" placeholder=" Rider Name* "
                                       name="supplier_name[en]" id="name" value="{!! $rider->supplier_name['en'] !!}"
                                       required>
                            </div>
                            @include('front.common.alert', ['input' => 'supplier_name[en]'])
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
                                <input class="ctm-input" type="tel" placeholder="{{ __('Phone No*') }}" name="phone"
                                       id="phone" value="{{ $rider->phone }}" required>
                            </div>
                            @include('front.common.alert', ['input' => 'phone'])
                        </div>

                        <div class="input-style">
                            <div class="wrapper-input">
                                <span class="icon-front-input">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13.5" height="18"
                                         viewBox="0 0 13.5 18">
                                        <path id="Path_48396" data-name="Path 48396"
                                              d="M-.7,1.9A.807.807,0,0,0,0,2.25.807.807,0,0,0,.7,1.9L3.059-1.477q1.758-2.531,2.32-3.41a9.9,9.9,0,0,0,1.09-2.127A6.4,6.4,0,0,0,6.75-9a6.515,6.515,0,0,0-.914-3.375,6.879,6.879,0,0,0-2.461-2.461A6.515,6.515,0,0,0,0-15.75a6.515,6.515,0,0,0-3.375.914,6.879,6.879,0,0,0-2.461,2.461A6.515,6.515,0,0,0-6.75-9a6.4,6.4,0,0,0,.281,1.986,9.9,9.9,0,0,0,1.09,2.127q.563.879,2.32,3.41Q-1.617.563-.7,1.9ZM0-6.187a2.708,2.708,0,0,1-1.986-.826A2.708,2.708,0,0,1-2.812-9a2.708,2.708,0,0,1,.826-1.986A2.708,2.708,0,0,1,0-11.812a2.708,2.708,0,0,1,1.986.826A2.708,2.708,0,0,1,2.813-9a2.708,2.708,0,0,1-.826,1.986A2.708,2.708,0,0,1,0-6.187Z"
                                              transform="translate(6.75 15.75)" fill="#45cea2"/>
                                    </svg>
                                </span>
                                <input type="text" name="address" class="ctm-input" placeholder="{{ __('Address') }}"
                                       value="{{ $rider->address }}" id="address-1" readonly required
                                       data-latitude="#latitude-1" data-longitude="#longitude-1"
                                       data-address="#address-1" data-target="#register-map-model"
                                       data-toggle="modal">
                                <input type="hidden" name="latitude" id="latitude-1"
                                       class="latitude"
                                       value="{{ $rider->latitude }}">>
                                <input type="hidden" name="longitude" id="longitude-1"
                                       class="longitude"
                                       value="{{ $rider->longitude }}">
                            </div>
                            @include('front.common.alert', ['input' => 'address'])
                        </div>
                    </div>


                    <!-- select 2 -->
                    <div class="input-style phone-dropdown custom-drop-contact ">
                        <!-- custom select -->
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
                            <select class="js-example-basic-single" name="city_id" required>
                                <option value="">{{__('Please Select city')}}</option>
                                @foreach ($cities as $city)
                                    @if ($city->parent_id == 0)
                                        <option
                                            value="{{ $city->id }}" {{ $rider->city_id == $city->id ? 'selected' : '' }}>
                                            {{ $city->name['en'] }}
                                        </option>
                                    @endif
                                @endforeach

                            </select>
                            @include('front.common.alert', ['input' => 'city'])
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mt-3">
                <div class="upload-sec d-flex align-items-center flex-wrap">
                    <!-- image upload -->
                    @include('front.common.image-upload', [
                        'imageTitle' => __('Display Picture'),
                        'inputName' => 'image',
                        'isRequired' => 1,
                        'allowVideo' => 0,
                        'imageNumber' => 1,
                        'permanentlyDelete' => 'dd',
                        'allowDelete' => 1,
                         'displayImageSrc' => imageUrl(
                            old('image', $rider->image),
                            113,
                            113,
                            95,
                            1
                        ),
                        'value' => old('image', $rider->image),
                    ])
                </div>
            </div>
            <div class="col-12 mt-3">
                <div class="upload-sec d-flex align-items-center flex-wrap">
                    <!-- image upload -->
                @include('front.common.image-upload-multi', [
                                       'imageTitle' => __('Trade License'),
                                       'inputName' => 'id_card_images',
                                       'isRequired' => 0,
                                       'allowVideo' => 0,
                                       'imageNumber' => 2,
                                       'numberOfImages' => 2,
                                       'allowDeleteApi' => 1,
                                       'displayImageSrc' => old('id_card_images',  $rider->id_card_images),
                                        'value' => old('id_card_images', $rider->id_card_images),
           ])
                <!-- image upload -->
                </div>
            </div>
            <div class="col-12 mt-3">
                <div class="edit-profile-button-dash">
                    <button type="submit"
                            class="btn btn-primary w-100">  {{ $id == 0 ? 'Add Rider' : 'Update Rider' }}</button>
                </div>
            </div>
        </form>
    </div>
</div>

