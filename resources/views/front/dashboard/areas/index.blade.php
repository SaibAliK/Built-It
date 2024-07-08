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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="add-area-button-page">
                                <a href="{{route('front.dashboard.areas.create')}}"
                                   class="btn btn-primary w-100">{{__('Add New Area')}}</a>
                            </div>
                        </div>

                        @if(!empty($data))
                            @forelse($data as $address)
                                <div class="col-md-6 col-lg-6">
                                    <div class="delivery-areas-dasboard-card">
                                        <div class="left-side-tittles">
                                            <h2 class="tittle-name text-truncate">
                                                @if($address->area)
                                                    {{ucfirst(translate($address->area->name)) ?? ''}}
                                                @else
                                                    N/A
                                                    {{--    {{ucfirst(translate($address->area->name)) ?? ''}}--}}
                                                @endif
                                            </h2>
                                            <h3 class="tittle-of-price text-truncate">{{getPrice(getPriceObject($address->price),$currency)}}</h3>
                                        </div>
                                        <div class="right-side-block-icons">
                                            <div class="box-1-icon">
                                                <a href="{{route('front.dashboard.areas.edit',['id'=>$address->id])}}">
                                                    <svg id="Component_5_1" data-name="Component 5 – 1"
                                                         xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                         viewBox="0 0 25 25">
                                                        <rect id="Rectangle_50" data-name="Rectangle 50" width="25"
                                                              height="25"
                                                              rx="5" fill="#45cea2"/>
                                                        <path id="Path_48396" data-name="Path 48396"
                                                              d="M3.145-9.98a.262.262,0,0,0-.191-.082.262.262,0,0,0-.191.082L-3.227-3.992-3.5-1.477a.531.531,0,0,0,.15.451.531.531,0,0,0,.451.15l2.516-.273L5.605-7.137a.262.262,0,0,0,.082-.191.262.262,0,0,0-.082-.191Zm4.43-.629a1.1,1.1,0,0,1,.3.766,1,1,0,0,1-.3.738l-.984.984a.262.262,0,0,1-.191.082.262.262,0,0,1-.191-.082L3.746-10.582a.262.262,0,0,1-.082-.191.262.262,0,0,1,.082-.191l.984-.984a1,1,0,0,1,.738-.3,1.1,1.1,0,0,1,.766.3ZM2.625-2.789a.26.26,0,0,1,.109-.219L3.828-4.1a.274.274,0,0,1,.342-.082.3.3,0,0,1,.205.3V.438a1.266,1.266,0,0,1-.383.93,1.266,1.266,0,0,1-.93.383H-6.562a1.266,1.266,0,0,1-.93-.383,1.266,1.266,0,0,1-.383-.93V-9.187a1.266,1.266,0,0,1,.383-.93,1.266,1.266,0,0,1,.93-.383h7.82a.3.3,0,0,1,.3.205.274.274,0,0,1-.082.342L.383-8.832a.3.3,0,0,1-.219.082H-6.125V0h8.75Z"
                                                              transform="translate(12.5 18)" fill="#fff"/>
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="box-1-icon">
                                                <a href="javascript:void(0)"
                                                   data-Ur="{{route('front.dashboard.areas.destroy',['id'=>$address->id])}}"
                                                   class="delete-area">
                                                    <svg id="Component_5_1" data-name="Component 5 – 1"
                                                         xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                         viewBox="0 0 25 25">
                                                        <rect id="Rectangle_50" data-name="Rectangle 50" width="25"
                                                              height="25"
                                                              rx="5" fill="#45cea2"/>
                                                        <path id="Path_48396" data-name="Path 48396"
                                                              d="M-6.125-9.953a.316.316,0,0,0,.1.232.316.316,0,0,0,.232.1H5.8a.316.316,0,0,0,.232-.1.316.316,0,0,0,.1-.232v-.766a.633.633,0,0,0-.191-.465.633.633,0,0,0-.465-.191H2.406l-.246-.52a.614.614,0,0,0-.246-.26.693.693,0,0,0-.355-.1H-1.559a.693.693,0,0,0-.355.1.614.614,0,0,0-.246.26l-.246.52H-5.469a.633.633,0,0,0-.465.191.633.633,0,0,0-.191.465ZM5.25-8.422a.316.316,0,0,0-.1-.232.316.316,0,0,0-.232-.1H-4.922a.316.316,0,0,0-.232.1.316.316,0,0,0-.1.232V.438a1.266,1.266,0,0,0,.383.93,1.266,1.266,0,0,0,.93.383H3.938a1.266,1.266,0,0,0,.93-.383A1.266,1.266,0,0,0,5.25.438ZM-2.187-6.562V-.437a.426.426,0,0,1-.123.314A.426.426,0,0,1-2.625,0a.426.426,0,0,1-.314-.123.426.426,0,0,1-.123-.314V-6.562a.426.426,0,0,1,.123-.314A.426.426,0,0,1-2.625-7a.426.426,0,0,1,.314.123A.426.426,0,0,1-2.187-6.562Zm2.625,0V-.437a.426.426,0,0,1-.123.314A.426.426,0,0,1,0,0,.426.426,0,0,1-.314-.123.426.426,0,0,1-.437-.437V-6.562a.426.426,0,0,1,.123-.314A.426.426,0,0,1,0-7a.426.426,0,0,1,.314.123A.426.426,0,0,1,.438-6.562Zm2.625,0V-.437a.426.426,0,0,1-.123.314A.426.426,0,0,1,2.625,0a.426.426,0,0,1-.314-.123.426.426,0,0,1-.123-.314V-6.562a.426.426,0,0,1,.123-.314A.426.426,0,0,1,2.625-7a.426.426,0,0,1,.314.123A.426.426,0,0,1,3.063-6.562Z"
                                                              transform="translate(12.5 18)" fill="#fff"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                @include('front.common.alert-empty',['message'=>__('No Delivery Areas found.')])
                            @endforelse
                        @endif
                    </div>

                </div>
            </div>

        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.delete-area').on('click', function () {
                let Urs = $(this).attr('data-Ur');
                swal({
                        title: "{{ __('Are you sure you want to delete this?') }}",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#1C4670",
                        confirmButtonText: "{{ __('Delete') }}",
                        cancelButtonText: "{{ __('No') }}",
                        closeOnConfirm: false,
                        closeOnCancel: false,
                        showLoaderOnConfirm: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                type: "GET",
                                url: Urs
                            }).done(function (res) {
                                toastr.success(
                                    "{{ __('Area has been deleted successfully!') }}");
                                swal.close()
                                window.location.href = "{{ route('front.dashboard.areas.index') }}";
                            }).fail(function (res) {
                                toastr.error("{{ __('Areas could not be deleted.') }}");
                                swal.close()
                            });
                        } else {
                            swal.close();
                        }
                    });
            })
        });
    </script>
@endpush
