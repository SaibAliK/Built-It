@extends('front.layouts.app')
@push('stylesheet-page-level')
    <style>
        .active {
            background-color: black !important;
            color: white !important;
        }

        .active-content {
            display: block !important;
        }
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
                            <div class="button-and-select-manage">
                                @if($userData->isSupplier() && auth()->user()->isCardImageVerified())
                                    @if($userData->isSupplier() && !auth()->user()->isSubscribed())
                                        <div class="add-new-product-manage">
                                            <a href="" data-toggle="modal" data-target="#exampleModalCenter"
                                               class="btn btn-primary">{{__('Add New Product')}}</a>
                                        </div>
                                    @else
                                        <div class="add-new-product-manage">
                                            <a href="{{route('front.dashboard.product.create',0)}}"
                                               class="btn btn-primary">{{__('Add New Product')}}</a>
                                        </div>
                                    @endif
                                @else
                                    <div class="add-new-product-manage">
                                           <span class="trade-linc-tittle-managee">
                                                 
                                                      {{__('Your Trade Licence is not verified by the admin. You can not add any Product until your ID is verified by the admin.')}}
                                              </span>
                                    </div>
                                @endif

{{--                                @if(count($products) > 0)--}}
                                    <div class="filter-order-page">
                                        <div class="input-style phone-dropdown custom-drop-contact">
                                            <div class="custom-selct-icons-arow position-relative">
                                                <img alt="" src="{{asset('assets/front/img/arrow-down-2.png')}}"
                                                     class="img-fluid arrow-abs">
                                                <select class="js-example-basic-single" id="product_status">

                                                    <option disabled selected>
                                                        @if(request()->has('all_for_store'))
                                                            {{__('All Product')}}
                                                        @elseif(request()->has('featured'))
                                                            {{__('Featured')}}
                                                        @elseif(request()->has('offerStatus=approved'))
                                                            {{__('Approved(offer)')}}
                                                        @elseif(request()->has('offerStatus=pending'))
                                                            {{__('Pending(offer)')}}
                                                        @elseif(request()->has('offerStatus=cancelled'))
                                                            {{__('Cancelled(offer)')}}
                                                        @elseif(request()->has('offerStatus=expired'))
                                                            {{__('Expired(offer)')}}
                                                        @else
                                                            {{__('Select Status')}}
                                                        @endif
                                                    </option>

                                                    <option value="all" id="all"
                                                            data-status="pending">{{__('All Product')}}</option>
                                                    <option value="featured" id="featured"
                                                            data-status="pending">{{__('Featured')}}</option>
                                                    <option value="pending" class="offerStatus"
                                                            data-status="pending">{{__('Pending(offer)')}}</option>
                                                    <option value="approved" class="offerStatus"
                                                            data-status="approved">{{__('Approved(offer)')}}</option>
                                                    <option value="cancelled" class="offerStatus"
                                                            data-status="cancelled">{{__('Cancelled(offer)')}}</option>
                                                    <option value="expired" class="offerStatus"
                                                            data-status="expired">{{__('Expired(offer)')}}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
{{--                                @endif--}}
                            </div>
                        </div>
                        @forelse($products as $product)
                            <div class="col-md-6 col-lg-4">
                                <div class="manage-product-dashbd-card">
                                    <div class="feature-product-card-main">
                                        <div class="inner-wrapper-feat">
                                            <div class="image-block-fet">
                                                {{--     href="{{ route('front.product.detail', $product->id) }}"--}}
                                                <a>
                                                    @if( str_contains($product->imageType(), 'video'))
                                                        <video width="230" height="178" controls>
                                                            <source src="{{$product->default_image}}"
                                                                    type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    @else
                                                        <img
                                                            src="{!! imageUrl($product->default_image,230,178,100,1) !!}"
                                                            class="img-fluid" alt="">
                                                    @endif
                                                </a>
                                                <div class="cart-icon-start-parent">
                                                    <div class="labe-starts">
                                                        <h3 class="tittle">
                                                            @if($product->average_rating > 0)
                                                                {{ number_format($product->average_rating,1) }}
                                                            @else
                                                                0
                                                            @endif
                                                            <span class="star-icon">
                                                                  <svg xmlns="http://www.w3.org/2000/svg" width="14.541"
                                                                       height="14"
                                                                       viewBox="0 0 12.541 12">
                                        <path id="Path_48396" data-name="Path 48396"
                                              d="M8.531-10.078a.713.713,0,0,1,.41-.375.8.8,0,0,1,.539,0,.713.713,0,0,1,.41.375l1.523,3.094,3.422.492a.711.711,0,0,1,.48.281.783.783,0,0,1,.164.516.719.719,0,0,1-.223.492L12.773-2.789,13.359.633a.74.74,0,0,1-.105.527.688.688,0,0,1-.434.316.723.723,0,0,1-.539-.07L9.211-.187,6.141,1.406a.723.723,0,0,1-.539.07.688.688,0,0,1-.434-.316A.74.74,0,0,1,5.063.633l.586-3.422L3.164-5.2A.719.719,0,0,1,2.941-5.7a.783.783,0,0,1,.164-.516.711.711,0,0,1,.48-.281l3.422-.492Z"
                                              transform="translate(-2.941 10.5)" fill="#ff6a00"></path>
                                      </svg>
                                                             </span>
                                                        </h3>
                                                    </div>

                                                    <div class="cart-icon-img">
                                                        <a href="{{route('front.dashboard.product.edit',$product->id)}}">
                                                            <svg id="Component_5_1" data-name="Component 5 – 1"
                                                                 xmlns="http://www.w3.org/2000/svg" width="25"
                                                                 height="25"
                                                                 viewBox="0 0 25 25">
                                                                <rect id="Rectangle_50" data-name="Rectangle 50"
                                                                      width="25"
                                                                      height="25" rx="5" fill="#45cea2"/>
                                                                <path id="Path_48396" data-name="Path 48396"
                                                                      d="M3.145-9.98a.262.262,0,0,0-.191-.082.262.262,0,0,0-.191.082L-3.227-3.992-3.5-1.477a.531.531,0,0,0,.15.451.531.531,0,0,0,.451.15l2.516-.273L5.605-7.137a.262.262,0,0,0,.082-.191.262.262,0,0,0-.082-.191Zm4.43-.629a1.1,1.1,0,0,1,.3.766,1,1,0,0,1-.3.738l-.984.984a.262.262,0,0,1-.191.082.262.262,0,0,1-.191-.082L3.746-10.582a.262.262,0,0,1-.082-.191.262.262,0,0,1,.082-.191l.984-.984a1,1,0,0,1,.738-.3,1.1,1.1,0,0,1,.766.3ZM2.625-2.789a.26.26,0,0,1,.109-.219L3.828-4.1a.274.274,0,0,1,.342-.082.3.3,0,0,1,.205.3V.438a1.266,1.266,0,0,1-.383.93,1.266,1.266,0,0,1-.93.383H-6.562a1.266,1.266,0,0,1-.93-.383,1.266,1.266,0,0,1-.383-.93V-9.187a1.266,1.266,0,0,1,.383-.93,1.266,1.266,0,0,1,.93-.383h7.82a.3.3,0,0,1,.3.205.274.274,0,0,1-.082.342L.383-8.832a.3.3,0,0,1-.219.082H-6.125V0h8.75Z"
                                                                      transform="translate(12.5 18)" fill="#fff"/>
                                                            </svg>
                                                        </a>

                                                        <a href="javascript:void(0)"
                                                           data-id="{!! $product->id !!}" class="delete-product">
                                                            <svg id="Component_6_1" data-name="Component 6 – 1"
                                                                 xmlns="http://www.w3.org/2000/svg" width="25"
                                                                 height="25"
                                                                 viewBox="0 0 25 25">
                                                                <rect id="Rectangle_50" data-name="Rectangle 50"
                                                                      width="25"
                                                                      height="25" rx="5" fill="#45cea2"/>
                                                                <path id="Path_48397" data-name="Path 48397"
                                                                      d="M-6.125-9.953a.316.316,0,0,0,.1.232.316.316,0,0,0,.232.1H5.8a.316.316,0,0,0,.232-.1.316.316,0,0,0,.1-.232v-.766a.633.633,0,0,0-.191-.465.633.633,0,0,0-.465-.191H2.406l-.246-.52a.614.614,0,0,0-.246-.26.693.693,0,0,0-.355-.1H-1.559a.693.693,0,0,0-.355.1.614.614,0,0,0-.246.26l-.246.52H-5.469a.633.633,0,0,0-.465.191.633.633,0,0,0-.191.465ZM5.25-8.422a.316.316,0,0,0-.1-.232.316.316,0,0,0-.232-.1H-4.922a.316.316,0,0,0-.232.1.316.316,0,0,0-.1.232V.438a1.266,1.266,0,0,0,.383.93,1.266,1.266,0,0,0,.93.383H3.938a1.266,1.266,0,0,0,.93-.383A1.266,1.266,0,0,0,5.25.438ZM-2.187-6.562V-.437a.426.426,0,0,1-.123.314A.426.426,0,0,1-2.625,0a.426.426,0,0,1-.314-.123.426.426,0,0,1-.123-.314V-6.562a.426.426,0,0,1,.123-.314A.426.426,0,0,1-2.625-7a.426.426,0,0,1,.314.123A.426.426,0,0,1-2.187-6.562Zm2.625,0V-.437a.426.426,0,0,1-.123.314A.426.426,0,0,1,0,0,.426.426,0,0,1-.314-.123.426.426,0,0,1-.437-.437V-6.562a.426.426,0,0,1,.123-.314A.426.426,0,0,1,0-7a.426.426,0,0,1,.314.123A.426.426,0,0,1,.438-6.562Zm2.625,0V-.437a.426.426,0,0,1-.123.314A.426.426,0,0,1,2.625,0a.426.426,0,0,1-.314-.123.426.426,0,0,1-.123-.314V-6.562a.426.426,0,0,1,.123-.314A.426.426,0,0,1,2.625-7a.426.426,0,0,1,.314.123A.426.426,0,0,1,3.063-6.562Z"
                                                                      transform="translate(12.5 18)" fill="#fff"/>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </div>

                                                @if($product->is_featured)
                                                    <div class="green-label-badge">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="27"
                                                             viewBox="0 0 15 27">
                                                            <path id="Path_48351" data-name="Path 48351"
                                                                  d="M0,0H15V27L7.313,21.393,0,27Z" fill="#45cea2"/>
                                                        </svg>
                                                    </div>
                                                @endif

                                            </div>

                                            <div class="content-crad-feat">
                                                <h3 class="tittle-card-nameee">{!! translate($product->name) !!}</h3>
                                                <h2 class="price-tittle">
                                                    @if($product->offer_percentage > 0)
                                                        <span
                                                            class="cut-price-title">{{getPrice($product->price,$currency)}}
                                                        </span>
                                                    @endif
                                                    <span class="grenn-tittle-p">{{getPrice($product->discounted_price,$currency)}} / </span>{{__('Meter')}}
                                                </h2>
                                                {{--                                                <h2>--}}
                                                {{--                                                    @if($product->offer_percentage > 0)--}}
                                                {{--                                                        <div class="feature-box d-block">--}}
                                                {{--                                                            {{$product->offer_percentage}}{{__('%')}} {{__('Off')}}--}}
                                                {{--                                                        </div>--}}
                                                {{--                                                    @endif--}}
                                                {{--                                                </h2>--}}
                                                {{--                                                <h3 class="tittle-supplier-name">--}}
                                                {{--                                                    {{__('Approval Status:')}} <span--}}
                                                {{--                                                        class="name-sub-sup"> {{$product->status}} </span>--}}
                                                {{--                                                </h3>--}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            @include('front.common.alert-empty', ['message' => __('No Product found.')])
                        @endforelse
                    </div>
                    {{ $products->withQueryString()->links('front.common.pagination', ['paginator' => $products]) }}
                </div>
            </div>

        </div>
    </section>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            var url_string = window.location.href;
            var url = new URL(url_string);

            var paramValue = url.searchParams.get("page");
            if (paramValue) {
                var pag = paramValue;
            } else {
                var pag = 1;
            }


            $(document).on('change', "#product_status", function () {
                var sel_val = $('#product_status :selected').val();
                if (sel_val == 'all') {
                    window.location.href = '?all_for_store=' + true + '&page=' + pag;
                } else if (sel_val == 'featured') {
                    window.location.href = '?featured=' + true + '&page=' + pag;
                } else {
                    window.location.href = '?offer=' + true + '&offerStatus=' + sel_val + '&page=' + pag;
                }
            });

            $('.delete-product').on('click', function () {
                let id = $(this).attr('data-id');
                swal({
                        title: "{{__('Are you sure you want to delete this?')}}",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#1C4670",
                        confirmButtonText: "{{__('Delete')}}",
                        cancelButtonText: "{{__('No')}}",
                        closeOnConfirm: false,
                        closeOnCancel: false,
                        showLoaderOnConfirm: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "{{route('api.auth.product.delete')}}",
                                type: 'post',
                                data: {id: id},
                                headers: {
                                    'Authorization': '{!! $userData['token'] !!}'
                                }
                            }).done(function (res) {
                                toastr.success("{{__('Product has been deleted successfully!')}}");
                                swal.close()
                                window.location.href = "{{route('front.dashboard.product.index')}}";
                            }).fail(function (res) {
                                toastr.error("{{__('Product could not be deleted.')}}");
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
