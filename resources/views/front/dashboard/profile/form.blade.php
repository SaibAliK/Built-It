@extends('front.layouts.app')
@section('content')
    @include('front.common.breadcrumb')
    <section class="suppiler-profile edit-profile mt-100 mb-70">
        <div class="container">
            <div class="row">
                @include('front.dashboard.common.left-sidebar')
                <div class="col-lg-8 col-md-8">
                    <div class="box-shadow-cards-login dasboard-side-form-block">
                        <form class="w-100" id='editProfileForm' method="post"
                              action="{!! route('front.dashboard.update.profile') !!}">
                            @csrf
                            <input type="hidden" name="user_type" value="{{ $user->user_type }}">
                            <div class="row">
                                <div class="col-12">
                                    <div class="inputs-border-wrapper">
                                        @if ($user->isUser())
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
                                                    <input class="ctm-input" required type="text"
                                                           placeholder="{{ __('Full Name') }}" name="user_name"
                                                           id="name" value="{{old('user_name', $user->user_name)}}"
                                                           required>
                                                </div>
                                                @include('front.common.alert', ['input' => 'user_name'])
                                            </div>
                                        @else
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
                                                    <input class="ctm-input" type="text"
                                                           placeholder="{{ __('Supplier Name') }}"
                                                           name="supplier_name[en]" id="name"
                                                           value="{{ old('supplier_name.en', $user->supplier_name['en']) }}"
                                                           required>
                                                </div>
                                                @include('front.common.alert', ['input' => 'supplier_name[en]'])
                                            </div>
                                        @endif
                                        <div class="input-style">
                                            <div class="wrapper-input">
                                            <span class="icon-front-input">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="17.546" height="13.786"
                                                     viewBox="0 0 17.546 13.786">
                                                    <path id="Icon_ionic-md-mail" data-name="Icon ionic-md-mail"
                                                          d="M18.125,5.625H3.921A1.676,1.676,0,0,0,2.25,7.3V17.74a1.676,1.676,0,0,0,1.671,1.671h14.2A1.676,1.676,0,0,0,19.8,17.74V7.3A1.676,1.676,0,0,0,18.125,5.625Zm-.209,3.551-6.893,4.6L4.13,9.176V7.5l6.893,4.6,6.893-4.6Z"
                                                          transform="translate(-2.25 -5.625)" fill="#45cea2"/>
                                                </svg>
                                            </span>
                                                <input class="ctm-input" readonly type="email"
                                                       placeholder="{{ __('Email*') }}" name="email" id="email"
                                                       value="{{old('user_name', $user->email)}}" required>
                                            </div>
                                            @include('front.common.alert', ['input' => 'email'])
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
                                                <input class="ctm-input" type="tel" placeholder="{{ __('Phone No*') }}"
                                                       maxlength="14" name="phone" id="phone"
                                                       value="{{old('user_name', $user->phone)}}" required>
                                            </div>
                                            @include('front.common.alert', ['input' => 'phone'])
                                        </div>
                                        <div class="input-style">
                                            <div class="wrapper-input">
                                            <span class="icon-front-input" data-latitude="#latitude-1"
                                                  data-longitude="#longitude-1" data-address="#address-1"
                                                  data-target="#register-map-model" data-toggle="modal">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13.5" height="18"
                                                     viewBox="0 0 13.5 18">
                                                    <path id="Path_48396" data-name="Path 48396"
                                                          d="M-.7,1.9A.807.807,0,0,0,0,2.25.807.807,0,0,0,.7,1.9L3.059-1.477q1.758-2.531,2.32-3.41a9.9,9.9,0,0,0,1.09-2.127A6.4,6.4,0,0,0,6.75-9a6.515,6.515,0,0,0-.914-3.375,6.879,6.879,0,0,0-2.461-2.461A6.515,6.515,0,0,0,0-15.75a6.515,6.515,0,0,0-3.375.914,6.879,6.879,0,0,0-2.461,2.461A6.515,6.515,0,0,0-6.75-9a6.4,6.4,0,0,0,.281,1.986,9.9,9.9,0,0,0,1.09,2.127q.563.879,2.32,3.41Q-1.617.563-.7,1.9ZM0-6.187a2.708,2.708,0,0,1-1.986-.826A2.708,2.708,0,0,1-2.812-9a2.708,2.708,0,0,1,.826-1.986A2.708,2.708,0,0,1,0-11.812a2.708,2.708,0,0,1,1.986.826A2.708,2.708,0,0,1,2.813-9a2.708,2.708,0,0,1-.826,1.986A2.708,2.708,0,0,1,0-6.187Z"
                                                          transform="translate(6.75 15.75)" fill="#45cea2"/>
                                                </svg>
                                            </span>
                                                <input type="text" name="address" class="ctm-input"
                                                       placeholder="{{ __('Address') }}"
                                                       value="{{old('address', $user->address)}}" id="address-1"
                                                       readonly required data-latitude="#latitude-1"
                                                       data-longitude="#longitude-1" data-address="#address-1"
                                                       data-target="#register-map-model" data-toggle="modal">
                                                <input type="hidden" name="latitude" id="latitude-1" class="latitude"
                                                       value="{{ old('latitude', $user->latitude) }}">
                                                <input type="hidden" name="longitude" id="longitude-1" class="longitude"
                                                       value="{{ old('longitude', $user->longitude) }}">
                                            </div>
                                            @include('front.common.alert', ['input' => 'address'])
                                        </div>

                                        @if (!$user->isUser())
                                        <!-- select 2 -->
                                            <div class="input-style phone-dropdown custom-drop-contact ">
                                                <!-- custom select -->
                                                <div class="custom-selct-icons-arow position-relative">
                                            <span class="icon-front-input">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="13.5" height="18"
                                                     viewBox="0 0 13.5 18">
                                                    <path id="Path_48396" data-name="Path 48396"
                                                          d="M-.7,1.9A.807.807,0,0,0,0,2.25.807.807,0,0,0,.7,1.9L3.059-1.477q1.758-2.531,2.32-3.41a9.9,9.9,0,0,0,1.09-2.127A6.4,6.4,0,0,0,6.75-9a6.515,6.515,0,0,0-.914-3.375,6.879,6.879,0,0,0-2.461-2.461A6.515,6.515,0,0,0,0-15.75a6.515,6.515,0,0,0-3.375.914,6.879,6.879,0,0,0-2.461,2.461A6.515,6.515,0,0,0-6.75-9a6.4,6.4,0,0,0,.281,1.986,9.9,9.9,0,0,0,1.09,2.127q.563.879,2.32,3.41Q-1.617.563-.7,1.9ZM0-6.187a2.708,2.708,0,0,1-1.986-.826A2.708,2.708,0,0,1-2.812-9a2.708,2.708,0,0,1,.826-1.986A2.708,2.708,0,0,1,0-11.812a2.708,2.708,0,0,1,1.986.826A2.708,2.708,0,0,1,2.813-9a2.708,2.708,0,0,1-.826,1.986A2.708,2.708,0,0,1,0-6.187Z"
                                                          transform="translate(6.75 15.75)" fill="#45cea2"></path>
                                                </svg>
                                            </span>
                                                    <img src="{{asset('assets/front/img/arrow-down-2.png')}}"
                                                         class="img-fluid arrow-abs">
                                                    <select class="js-example-basic-single" name="city_id">
                                                        @forelse($cities as $city)
                                                            @if ($city->parent_id == 0)
                                                                <option value="{{ $city->id }}"
                                                                    {{empty(old('city_id')) ? (!empty($user->city_id ) ? ($user->city_id == $city->id ? "selected" : '') : '') :old('city_id')}}>
                                                                    {{ translate($city->name) }}
                                                                </option>
                                                            @endif
                                                        @empty
                                                            <option value="" disabled selected>{{ __('Select City') }}
                                                            </option>
                                                        @endforelse

                                                    </select>
                                                </div>
                                                <!-- end custom-select -->
                                            </div>

                                            <!-- end select 2 -->
                                            <div class="input-style">
                                                <div class="wrapper-input">
                                            <span class="icon-front-input">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="15.5" height="15.5"
                                                     viewBox="0 0 15.5 15.5">
                                                    <path id="Path_48396" data-name="Path 48396"
                                                          d="M0-13.75A7.513,7.513,0,0,1,3.875-12.7,7.831,7.831,0,0,1,6.7-9.875,7.513,7.513,0,0,1,7.75-6,7.513,7.513,0,0,1,6.7-2.125,7.831,7.831,0,0,1,3.875.7,7.513,7.513,0,0,1,0,1.75,7.513,7.513,0,0,1-3.875.7,7.831,7.831,0,0,1-6.7-2.125,7.513,7.513,0,0,1-7.75-6,7.513,7.513,0,0,1-6.7-9.875,7.831,7.831,0,0,1-3.875-12.7,7.513,7.513,0,0,1,0-13.75Zm0,3.438a1.261,1.261,0,0,0-.922.391A1.261,1.261,0,0,0-1.312-9a1.261,1.261,0,0,0,.391.922A1.261,1.261,0,0,0,0-7.687a1.261,1.261,0,0,0,.922-.391A1.261,1.261,0,0,0,1.313-9a1.261,1.261,0,0,0-.391-.922A1.261,1.261,0,0,0,0-10.312ZM1.75-2.375v-.75a.362.362,0,0,0-.109-.266A.362.362,0,0,0,1.375-3.5H1V-6.625a.362.362,0,0,0-.109-.266A.362.362,0,0,0,.625-7h-2a.362.362,0,0,0-.266.109.362.362,0,0,0-.109.266v.75a.362.362,0,0,0,.109.266.362.362,0,0,0,.266.109H-1v2h-.375a.362.362,0,0,0-.266.109.362.362,0,0,0-.109.266v.75a.362.362,0,0,0,.109.266A.362.362,0,0,0-1.375-2h2.75a.362.362,0,0,0,.266-.109A.362.362,0,0,0,1.75-2.375Z"
                                                          transform="translate(7.75 13.75)" fill="#45cea2"/>
                                                </svg>
                                            </span>
                                                    <textarea type="text" name="about[en]"
                                                              value="{{ old('about.en', $user->about['en']) }}"
                                                              placeholder="About" required
                                                              class="ctm-input">{{ translate($user->about) }}</textarea>

                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mt-4"></div>
                                    @include('front.common.image-upload',[
                                    'imageTitle'=>__('Display Picture'),
                                    'inputName'=>'image',
                                    'isRequired' => $user->isSupplier(),
                                    'allowVideo' => 0,
                                    'imageNumber'=>1,
                                    'allowDelete'=>!$user->isSupplier(),
                                    'permanentlyDelete'=>0,
                                    'recommend_size'=> '457 x 457',
                                    'displayImageSrc'=>imageUrl(old('image', auth()->user()->image), 158, 158, 100, 1),
                                    'value'=>old('image', $user->image)
                                    ])

                                    @if (!$user->isUser())
                                        <div class="mt-4"></div>
                                        @include('front.common.image-upload-multi', [
                                        'imageTitle' => __('Trade License'),
                                        'inputName' => 'id_card_images',
                                        'isRequired' => 1,
                                        'allowVideo' => 0,
                                        'imageNumber' => 2,
                                        'numberOfImages' => 2,
                                        'allowDeleteApi' => 1,
                                        'displayImageSrc' => old('id_card_images', auth()->user()->id_card_images),
                                        'value' => old('id_card_images', auth()->user()->id_card_images),
                                        ])
                                    @endif
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="edit-profile-button-dash">
                                        <button type="submit" class="btn btn-primary w-100">{{__('Update')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('front.common.map-modal')
@endsection

@push('scripts')
    <script>
        $('#editProfileForm').validate({
            ignore: '',
            rules: {
                user_name: {
                    required: true,
                    noSpace: true
                },
                id_card_images: {
                    required: true
                },
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element);
            }
        });
    </script>

@endpush
