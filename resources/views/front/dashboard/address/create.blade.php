@extends('front.layouts.app')
@push('stylesheet-end')
    <style>
        .js-address-cancel {
            display: none !important;
        }
    </style>
@endpush

@section('content')

<section class="login-seca-all-page">
    <div class="container">
        <div class="row">
                @include('front.dashboard.common.left-sidebar')
                <div class="col-lg-8 col-md-8">
                    @include('front.dashboard.address.form')
                </div>
            </div>
        </div>
    </section>

    @include('front.common.map-modal-address')
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $("#active_address").val("true");
            $(".js-check-out-btn").on('click', function (e) {
                $("#check-out-form").submit();
            });
        });
    </script>
@endpush
