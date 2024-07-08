@extends('front.layouts.app')
@section('content')
    @include('front.common.breadcrumb')

    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                @include('front.dashboard.common.left-sidebar')
                <div class="col-lg-8 col-md-8">
                    <div class="row">
                        @include('front.dashboard.rider.form')
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('front.common.map-modal')
@endsection

@push('scripts')
    <script>

        $('.fa-eye-slash').click(function () {
            if ($(this).hasClass('fa-eye')) {
                $(this).removeClass('fa-eye');
                $(this).addClass('fa-eye-slash');
                $(this).parent().siblings().attr('type', 'password');
            } else {
                $(this).removeClass('fa-eye-slash');
                $(this).addClass('fa-eye');
                $(this).parent().siblings().attr('type', 'text');
            }
        });

        jQuery.validator.addMethod("tel", function (value, element) {
            return this.optional(element) || /^(?:\+)[0-9]/.test(value);
        }, "Please enter international number starting with + sign.");

        $("#ridersForm").validate({
            ignore: '',
            errorPlacement: function (error, element) {
                if (element.attr("name") == "password" || element.attr("name") == "address" || element.attr(
                    "name") == "trade_license" || element.attr("name") == "city_id") {
                    error.insertAfter(element.parent().parent());
                } else {
                    error.insertAfter(element.parent());
                }
            },
        });
    </script>
@endpush
