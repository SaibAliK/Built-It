@extends('front.layouts.app')

@push('stylesheet-page-level')
@endpush

@section('content')
    @include('front.common.breadcrumb')
    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                @include('front.dashboard.common.left-sidebar')
                <div class="col-lg-8">
                    <div class="col-md-12">
                        <div class="button-and-select-manage">
                            <div class="add-new-product-manage">
                                <a href="{{ route('front.dashboard.address.create', 0) }}" type="button"
                                   id="exampleModalLongTitle"
                                   class="btn btn-primary">{{__('Add Address')}}</a>
                            </div>
                        </div>
                    </div>
                    @if (!empty($data))
                        @forelse($data as $item)
                            <form id="address-form-{{ $item->id }}"
                                  action="{{ route('front.dashboard.address.make-default') }}"
                                  method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{$item->id}}">
                                <div
                                    class="remember-me check-out-pageee-radio-se multi-radio-addresss  w-100">
                                    <label class="custom-radio w-100">
                                        <div class="check-out-page-address-inf">
                                            <div class="main-block-shpping-image-content">
                                                <div
                                                    class="shipping-dt-img d-flex align-items-center justify-content-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16.5"
                                                         height="22" viewBox="0 0 16.5 22">
                                                        <path id="Path_48396" data-name="Path 48396"
                                                              d="M7.391,2.32a.986.986,0,0,0,.859.43.986.986,0,0,0,.859-.43L11.988-1.8Q14.137-4.9,14.824-5.973a12.1,12.1,0,0,0,1.332-2.6A7.822,7.822,0,0,0,16.5-11a7.963,7.963,0,0,0-1.117-4.125,8.408,8.408,0,0,0-3.008-3.008A7.963,7.963,0,0,0,8.25-19.25a7.963,7.963,0,0,0-4.125,1.117,8.408,8.408,0,0,0-3.008,3.008A7.963,7.963,0,0,0,0-11,7.822,7.822,0,0,0,.344-8.572a12.1,12.1,0,0,0,1.332,2.6Q2.363-4.9,4.512-1.8,6.273.688,7.391,2.32ZM8.25-7.562a3.31,3.31,0,0,1-2.428-1.01A3.31,3.31,0,0,1,4.813-11a3.31,3.31,0,0,1,1.01-2.428,3.31,3.31,0,0,1,2.428-1.01,3.31,3.31,0,0,1,2.428,1.01A3.31,3.31,0,0,1,11.688-11a3.31,3.31,0,0,1-1.01,2.428A3.31,3.31,0,0,1,8.25-7.562Z"
                                                              transform="translate(0 19.25)"
                                                              fill="#45cea2"></path>
                                                    </svg>
                                                </div>
                                                <div class="right-content-shipping">
                                                    <h2 class="tittle-name-city">{{$item->address}}</h2>
                                                </div>

                                            </div>
                                            <h2 class="oder-dt-cus-head-ad">{{__('Name:')}} <span
                                                    class="span-time-head-2">  {{$item->name}}</span>
                                            </h2>
                                            <h2 class="oder-dt-cus-head-ad">{{__('Phone No:')}} <span
                                                    class="span-time-head-2"
                                                    dir="ltr"> {{$item->user_phone}}</span>
                                            </h2>
                                            <h2 class="oder-dt-cus-head-ad">{{__('City:')}} <span
                                                    class="span-time-head-2">  {{translate($item->city->name)}}</span>
                                            </h2>
                                        </div>
                                        @if ($item->default_address)
                                            <input type="radio" id="{{ $item->id }}"
                                                   class=""
                                                   checked data-id="{{ $item->id }}"
                                                   name="radio">
                                            <span class="checkmark"></span>
                                        @else
                                            <input type="radio" id="{{ $item->id }}"
                                                   class="default-address"
                                                   data-id="{{ $item->id }}"
                                                   name="radio">
                                            <span class="checkmark"></span>
                                        @endif
                                    </label>
                                    <div class="iconss d-flex gx-20 manage-icon-ss">
                                        <a href="{{ route('front.dashboard.address.edit', ['id' => $item->id]) }}"
                                           class="edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="19.98"
                                                 height="19.98" viewBox="0 0 19.98 19.98">
                                                <g id="Icon_feather-edit" data-name="Icon feather-edit"
                                                   transform="translate(1 1.175)">
                                                    <path id="Path_48509" data-name="Path 48509"
                                                          d="M10.964,6H4.77A1.77,1.77,0,0,0,3,7.77V20.159a1.77,1.77,0,0,0,1.77,1.77H17.159a1.77,1.77,0,0,0,1.77-1.77V13.964"
                                                          transform="translate(-3 -4.123)" fill="none"
                                                          stroke="#999" stroke-linecap="round"
                                                          stroke-linejoin="round" stroke-width="2"/>
                                                    <path id="Path_48510" data-name="Path 48510"
                                                          d="M21.292,3.368a1.877,1.877,0,0,1,2.655,2.655L15.54,14.429,12,15.314l.885-3.54Z"
                                                          transform="translate(-6.69 -2.818)" fill="none"
                                                          stroke="#999" stroke-linecap="round"
                                                          stroke-linejoin="round" stroke-width="2"/>
                                                </g>
                                            </svg>
                                        </a>
                                        <a href="javascript:void(0)" data-id="{!! $item->id !!}"
                                           class="del delete-address">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16.646"
                                                 height="20.487" viewBox="0 0 16.646 20.487">
                                                <path id="Icon_metro-bin" data-name="Icon metro-bin"
                                                      d="M5.779,8.33v12.8a1.284,1.284,0,0,0,1.28,1.28H18.583a1.284,1.284,0,0,0,1.28-1.28V8.33H5.779ZM9.62,19.854H8.34V10.891H9.62Zm2.561,0H10.9V10.891h1.28Zm2.561,0h-1.28V10.891h1.28Zm2.561,0h-1.28V10.891H17.3ZM20.184,4.489H16.023v-1.6a.963.963,0,0,0-.96-.96H10.581a.963.963,0,0,0-.96.96v1.6H5.459a.963.963,0,0,0-.96.96v1.6H21.144v-1.6A.963.963,0,0,0,20.184,4.489Zm-5.442,0H10.9V3.225h3.841V4.489Z"
                                                      transform="translate(-4.499 -1.928)" fill="#999"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        @empty
                            @include('front.common.alert-empty', ['message' => __('No Address found.')])
                        @endforelse

                    @endif
                </div>
            </div>

        </div>
    </section>
@endsection


@push('scripts')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.default-address', function () {
                let id = $(this).attr('data-id');
                $("#address-form-" + id).submit();
            });
            $('#subscribe-form').validate();
        });

        $(document).ready(function () {
            $('.delete-address').on('click', function () {
                let id = $(this).attr('data-id');
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

                                url: window.Laravel.baseUrl + "dashboard/delete-address/" + id,
                                type: 'get',
                            })
                                .done(function (res) {
                                    toastr.success(
                                        "{{ __('Address has been deleted successfully!') }}");
                                    swal.close()
                                    window.location.href =
                                        "{{ route('front.dashboard.address.index') }}";
                                })
                                .fail(function (res) {
                                    toastr.error("{{ __('Address could not be deleted.') }}");
                                    swal.close()
                                });
                        } else {
                            swal.close();
                        }
                    });
            })
        })
    </script>
@endpush
