@extends('front.layouts.app')
@section('content')
    @include('front.common.breadcrumb')
    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                @include('front.dashboard.common.left-sidebar')
                <change-password></change-password>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        {{--$('#changePasswordForm').validate();--}}
        {{--$.validator.messages.equalTo = function (param, input) {--}}
        {{--    return '{{__('The password confirmation does not match.')}}';--}}
        {{--}--}}

        {{--$('.fa-eye-slash').click(function () {--}}
        {{--    if ($(this).hasClass('fa-eye')) {--}}
        {{--        $(this).removeClass('fa-eye');--}}
        {{--        $(this).addClass('fa-eye-slash');--}}
        {{--        $(this).parent().siblings().attr('type', 'password');--}}
        {{--    } else {--}}
        {{--        $(this).removeClass('fa-eye-slash');--}}
        {{--        $(this).addClass('fa-eye');--}}
        {{--        $(this).parent().siblings().attr('type', 'text');--}}
        {{--    }--}}
        {{--});--}}
    </script>
@endpush
