@extends('front.layouts.app')
@push('stylesheet-page-level')
    <style>

    </style>
@endpush

@section('content')
    @include('front.common.breadcrumb')

    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                @include('front.dashboard.common.left-sidebar')
                <div class="col-lg-8 col-md-8">
                    <form action="{{route('front.dashboard.color.save')}}" method="post" id="colorForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 ">
                                <div class="inputs-border-wrapper ">
                                    <div class="input-style">
                                        <div class="wrapper-input">
                                        <span class="icon-front-input">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16.001" height="16"
                                                 viewBox="0 0 16.001 16">
                                                <path id="Icon_ionic-ios-color-palette"
                                                      data-name="Icon ionic-ios-color-palette"
                                                      d="M19.753,16.329a1.946,1.946,0,0,0-1.025-.375,1.525,1.525,0,0,1-.942-.417,1.155,1.155,0,0,1,0-1.825l1.262-1.121a4.057,4.057,0,0,0,0-6.217A8.011,8.011,0,0,0,13.724,4.5,10.073,10.073,0,0,0,7.108,7a7.3,7.3,0,0,0,0,11.188A9.755,9.755,0,0,0,13.478,20.5h.071a9.377,9.377,0,0,0,6.2-2.183A1.407,1.407,0,0,0,19.753,16.329Zm-12.921-5.5a1.333,1.333,0,1,1,1.333,1.333A1.332,1.332,0,0,1,6.833,10.833ZM8.5,16.083A1.333,1.333,0,1,1,9.833,14.75,1.332,1.332,0,0,1,8.5,16.083Zm2.667-6.708A1.333,1.333,0,1,1,12.5,8.042,1.332,1.332,0,0,1,11.166,9.375Zm3,9.125a2,2,0,1,1,2-2A2,2,0,0,1,14.166,18.5Zm1-8.667A1.333,1.333,0,1,1,16.5,8.5,1.332,1.332,0,0,1,15.166,9.833Z"
                                                      transform="translate(-4.498 -4.5)" fill="#45cea2"/>
                                              </svg>
                                        </span>
                                            <input type="text" name="heading_color"
                                                   value="{{empty(old('heading_color')) ? (!empty($color->heading_color) ? $color->heading_color : old('heading_color')) :old('heading_color')}}"
                                                   class="ctm-input colorpicker" placeholder="{{__('Heading Color')}}">
                                            <input type="hidden" name="store_id" value="{{auth()->user()->id}}">
                                        </div>
                                    </div>
                                    <div class="input-style">
                                        <div class="wrapper-input">
                                        <span class="icon-front-input">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16.001" height="16"
                                                 viewBox="0 0 16.001 16">
                                                <path id="Icon_ionic-ios-color-palette"
                                                      data-name="Icon ionic-ios-color-palette"
                                                      d="M19.753,16.329a1.946,1.946,0,0,0-1.025-.375,1.525,1.525,0,0,1-.942-.417,1.155,1.155,0,0,1,0-1.825l1.262-1.121a4.057,4.057,0,0,0,0-6.217A8.011,8.011,0,0,0,13.724,4.5,10.073,10.073,0,0,0,7.108,7a7.3,7.3,0,0,0,0,11.188A9.755,9.755,0,0,0,13.478,20.5h.071a9.377,9.377,0,0,0,6.2-2.183A1.407,1.407,0,0,0,19.753,16.329Zm-12.921-5.5a1.333,1.333,0,1,1,1.333,1.333A1.332,1.332,0,0,1,6.833,10.833ZM8.5,16.083A1.333,1.333,0,1,1,9.833,14.75,1.332,1.332,0,0,1,8.5,16.083Zm2.667-6.708A1.333,1.333,0,1,1,12.5,8.042,1.332,1.332,0,0,1,11.166,9.375Zm3,9.125a2,2,0,1,1,2-2A2,2,0,0,1,14.166,18.5Zm1-8.667A1.333,1.333,0,1,1,16.5,8.5,1.332,1.332,0,0,1,15.166,9.833Z"
                                                      transform="translate(-4.498 -4.5)" fill="#45cea2"/>
                                              </svg>

                                        </span>
                                            <input type="text" name="text_color"
                                                   value="{{empty(old('text_color')) ? (!empty($color->text_color) ? $color->text_color : old('text_color')) :old('text_color')}}"
                                                   class="ctm-input colorpicker" placeholder="{{__('Text Color')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inputs-border-wrapper ">
                                    <div class="input-style">
                                        <div class="wrapper-input">
                                        <span class="icon-front-input">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16.001" height="16"
                                                 viewBox="0 0 16.001 16">
                                                <path id="Icon_ionic-ios-color-palette"
                                                      data-name="Icon ionic-ios-color-palette"
                                                      d="M19.753,16.329a1.946,1.946,0,0,0-1.025-.375,1.525,1.525,0,0,1-.942-.417,1.155,1.155,0,0,1,0-1.825l1.262-1.121a4.057,4.057,0,0,0,0-6.217A8.011,8.011,0,0,0,13.724,4.5,10.073,10.073,0,0,0,7.108,7a7.3,7.3,0,0,0,0,11.188A9.755,9.755,0,0,0,13.478,20.5h.071a9.377,9.377,0,0,0,6.2-2.183A1.407,1.407,0,0,0,19.753,16.329Zm-12.921-5.5a1.333,1.333,0,1,1,1.333,1.333A1.332,1.332,0,0,1,6.833,10.833ZM8.5,16.083A1.333,1.333,0,1,1,9.833,14.75,1.332,1.332,0,0,1,8.5,16.083Zm2.667-6.708A1.333,1.333,0,1,1,12.5,8.042,1.332,1.332,0,0,1,11.166,9.375Zm3,9.125a2,2,0,1,1,2-2A2,2,0,0,1,14.166,18.5Zm1-8.667A1.333,1.333,0,1,1,16.5,8.5,1.332,1.332,0,0,1,15.166,9.833Z"
                                                      transform="translate(-4.498 -4.5)" fill="#45cea2"/>
                                              </svg>


                                        </span>
                                            <input type="text" name="icons_color"
                                                   value="{{empty(old('icons_color')) ? (!empty($color->icons_color) ? $color->icons_color : old('icons_color')) :old('icons_color')}}"
                                                   class="ctm-input colorpicker" placeholder="{{__('Icons Color')}}">
                                        </div>
                                    </div>
                                    <div class="input-style">
                                        <div class="wrapper-input">
                                        <span class="icon-front-input">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16.001" height="16"
                                                 viewBox="0 0 16.001 16">
                                                <path id="Icon_ionic-ios-color-palette"
                                                      data-name="Icon ionic-ios-color-palette"
                                                      d="M19.753,16.329a1.946,1.946,0,0,0-1.025-.375,1.525,1.525,0,0,1-.942-.417,1.155,1.155,0,0,1,0-1.825l1.262-1.121a4.057,4.057,0,0,0,0-6.217A8.011,8.011,0,0,0,13.724,4.5,10.073,10.073,0,0,0,7.108,7a7.3,7.3,0,0,0,0,11.188A9.755,9.755,0,0,0,13.478,20.5h.071a9.377,9.377,0,0,0,6.2-2.183A1.407,1.407,0,0,0,19.753,16.329Zm-12.921-5.5a1.333,1.333,0,1,1,1.333,1.333A1.332,1.332,0,0,1,6.833,10.833ZM8.5,16.083A1.333,1.333,0,1,1,9.833,14.75,1.332,1.332,0,0,1,8.5,16.083Zm2.667-6.708A1.333,1.333,0,1,1,12.5,8.042,1.332,1.332,0,0,1,11.166,9.375Zm3,9.125a2,2,0,1,1,2-2A2,2,0,0,1,14.166,18.5Zm1-8.667A1.333,1.333,0,1,1,16.5,8.5,1.332,1.332,0,0,1,15.166,9.833Z"
                                                      transform="translate(-4.498 -4.5)" fill="#45cea2"/>
                                              </svg>


                                        </span>
                                            <input type="text" name="background_color"
                                                   value="{{empty(old('background_color')) ? (!empty($color->background_color) ? $color->background_color : old('background_color')) :old('background_color')}}"
                                                   class="ctm-input colorpicker" placeholder="{{__('Icons BG Color')}}">
                                        </div>
                                    </div>
                                </div>
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
    </section>

@endsection

@push('scripts')
    <script>
        $('#colorForm').validate({
            ignore: '',
            rules: {
                'description[en]': {
                    required: true,
                    noSpace: true,
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") === "city_id") {
                    $("#city").html(error);
                } else {
                    error.insertAfter(element);
                }
            }
        });
    </script>
@endpush
