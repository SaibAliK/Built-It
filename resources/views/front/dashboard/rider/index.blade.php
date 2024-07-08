@extends('front.layouts.app')
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
                                <a href="{!! route('front.dashboard.edit.riders', 0) !!}"
                                   class="btn btn-primary w-100">{{__('Add Rider')}}</a>
                            </div>
                        </div>
                        @forelse ($riders as $rider)
                            <div class="col-md-6">
                                <div class="manage-rider-dasboard-card">
                                    <div class="image-block-left-sider">
                                        <div class="rider-manage-img">
                                            <img src="{{ imageUrl($rider->image_url, 58, 58, 95, 1) }}"
                                                 class="img-fluid"
                                                 alt="image">
                                        </div>
                                        <div class="left-side-tittles">
                                            <h2 class="tittle-name text-truncate">{{ translate($rider->supplier_name) }}</h2>
                                            <h3 class="tittle-of-loc text-truncate">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10.5" height="14"
                                                     viewBox="0 0 10.5 14">
                                                    <path id="Path_48396" data-name="Path 48396"
                                                          d="M4.7,1.477a.627.627,0,0,0,.547.273A.627.627,0,0,0,5.8,1.477L7.629-1.148Q9-3.117,9.434-3.8a7.7,7.7,0,0,0,.848-1.654A4.977,4.977,0,0,0,10.5-7a5.067,5.067,0,0,0-.711-2.625,5.35,5.35,0,0,0-1.914-1.914A5.067,5.067,0,0,0,5.25-12.25a5.067,5.067,0,0,0-2.625.711A5.35,5.35,0,0,0,.711-9.625,5.067,5.067,0,0,0,0-7,4.977,4.977,0,0,0,.219-5.455,7.7,7.7,0,0,0,1.066-3.8q.438.684,1.8,2.652Q3.992.438,4.7,1.477ZM5.25-4.812a2.106,2.106,0,0,1-1.545-.643A2.106,2.106,0,0,1,3.063-7a2.106,2.106,0,0,1,.643-1.545A2.106,2.106,0,0,1,5.25-9.187a2.106,2.106,0,0,1,1.545.643A2.106,2.106,0,0,1,7.438-7a2.106,2.106,0,0,1-.643,1.545A2.106,2.106,0,0,1,5.25-4.812Z"
                                                          transform="translate(0 12.25)" fill="#45cea2"/>
                                                </svg>
                                                {{ $rider->address }}
                                            </h3>

                                        </div>

                                    </div>

                                    <div class="right-side-block-icons">
                                        <div class="box-1-icon">
                                            <a href="{{ route('front.dashboard.edit.riders', $rider->id) }}">
                                                <svg id="Component_5_1" data-name="Component 5 – 1"
                                                     xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                     viewBox="0 0 25 25">
                                                    <rect id="Rectangle_50" data-name="Rectangle 50" width="25"
                                                          height="25"
                                                          rx="5" fill="#45cea2"></rect>
                                                    <path id="Path_48396" data-name="Path 48396"
                                                          d="M3.145-9.98a.262.262,0,0,0-.191-.082.262.262,0,0,0-.191.082L-3.227-3.992-3.5-1.477a.531.531,0,0,0,.15.451.531.531,0,0,0,.451.15l2.516-.273L5.605-7.137a.262.262,0,0,0,.082-.191.262.262,0,0,0-.082-.191Zm4.43-.629a1.1,1.1,0,0,1,.3.766,1,1,0,0,1-.3.738l-.984.984a.262.262,0,0,1-.191.082.262.262,0,0,1-.191-.082L3.746-10.582a.262.262,0,0,1-.082-.191.262.262,0,0,1,.082-.191l.984-.984a1,1,0,0,1,.738-.3,1.1,1.1,0,0,1,.766.3ZM2.625-2.789a.26.26,0,0,1,.109-.219L3.828-4.1a.274.274,0,0,1,.342-.082.3.3,0,0,1,.205.3V.438a1.266,1.266,0,0,1-.383.93,1.266,1.266,0,0,1-.93.383H-6.562a1.266,1.266,0,0,1-.93-.383,1.266,1.266,0,0,1-.383-.93V-9.187a1.266,1.266,0,0,1,.383-.93,1.266,1.266,0,0,1,.93-.383h7.82a.3.3,0,0,1,.3.205.274.274,0,0,1-.082.342L.383-8.832a.3.3,0,0,1-.219.082H-6.125V0h8.75Z"
                                                          transform="translate(12.5 18)" fill="#fff"></path>
                                                </svg>
                                            </a>
                                        </div>

                                        <div class="box-1-icon">
                                            <a href="javascript:void(0)" class="delete-btn-manage"  data-id="{{ $rider->id }}">
                                                <svg id="Component_5_1" data-name="Component 5 – 1"
                                                     xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                                     viewBox="0 0 25 25">
                                                    <rect id="Rectangle_50" data-name="Rectangle 50" width="25"
                                                          height="25"
                                                          rx="5" fill="#45cea2"></rect>
                                                    <path id="Path_48396" data-name="Path 48396"
                                                          d="M-6.125-9.953a.316.316,0,0,0,.1.232.316.316,0,0,0,.232.1H5.8a.316.316,0,0,0,.232-.1.316.316,0,0,0,.1-.232v-.766a.633.633,0,0,0-.191-.465.633.633,0,0,0-.465-.191H2.406l-.246-.52a.614.614,0,0,0-.246-.26.693.693,0,0,0-.355-.1H-1.559a.693.693,0,0,0-.355.1.614.614,0,0,0-.246.26l-.246.52H-5.469a.633.633,0,0,0-.465.191.633.633,0,0,0-.191.465ZM5.25-8.422a.316.316,0,0,0-.1-.232.316.316,0,0,0-.232-.1H-4.922a.316.316,0,0,0-.232.1.316.316,0,0,0-.1.232V.438a1.266,1.266,0,0,0,.383.93,1.266,1.266,0,0,0,.93.383H3.938a1.266,1.266,0,0,0,.93-.383A1.266,1.266,0,0,0,5.25.438ZM-2.187-6.562V-.437a.426.426,0,0,1-.123.314A.426.426,0,0,1-2.625,0a.426.426,0,0,1-.314-.123.426.426,0,0,1-.123-.314V-6.562a.426.426,0,0,1,.123-.314A.426.426,0,0,1-2.625-7a.426.426,0,0,1,.314.123A.426.426,0,0,1-2.187-6.562Zm2.625,0V-.437a.426.426,0,0,1-.123.314A.426.426,0,0,1,0,0,.426.426,0,0,1-.314-.123.426.426,0,0,1-.437-.437V-6.562a.426.426,0,0,1,.123-.314A.426.426,0,0,1,0-7a.426.426,0,0,1,.314.123A.426.426,0,0,1,.438-6.562Zm2.625,0V-.437a.426.426,0,0,1-.123.314A.426.426,0,0,1,2.625,0a.426.426,0,0,1-.314-.123.426.426,0,0,1-.123-.314V-6.562a.426.426,0,0,1,.123-.314A.426.426,0,0,1,2.625-7a.426.426,0,0,1,.314.123A.426.426,0,0,1,3.063-6.562Z"
                                                          transform="translate(12.5 18)" fill="#fff"></path>
                                                </svg>
                                            </a>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        @empty
                            @include('front.common.alert-empty', [
                                'message' => 'No Rider added',
                            ])
                            <h2 class="mx-auto"></h2>
                        @endforelse
                    </div>
                    {{ $riders->links('front.common.pagination', ['paginator' => $riders]) }}
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script>
        $(document).ready(function () {

            $('.delete-btn-manage').on('click', function (e) {
                var id = $(this).data('id');
                swal({
                        title: "Are you sure you want to delete this?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#1C4670",
                        confirmButtonText: "Delete",
                        cancelButtonText: "No",
                        closeOnConfirm: false,
                        closeOnCancel: false,
                        showLoaderOnConfirm: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                type: 'get',
                                url: window.Laravel.baseUrl + "dashboard/riders/delete/" + id,
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': window.Laravel.csrfToken
                                }
                            })
                                .done(function (res) {
                                    debugger;
                                    toastr.success("Rider Deleted Successfully!");
                                    location.reload();
                                })
                                .fail(function (res) {
                                    toastr.error("Something is Wrong!");
                                    location.reload();

                                });
                        } else {
                            swal.close();
                        }
                    });
            });
        })
    </script>
@endpush

