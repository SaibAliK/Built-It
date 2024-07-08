@extends('front.layouts.app')
@section('content')
    @include('front.common.breadcrumb')

    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                @include('front.dashboard.common.left-sidebar')
                <div class="col-lg-8 col-md-8">
                    <div class="row">
                        @forelse($packages as $package)
                            <div class="col-md-6 col-lg-4">
                                <div class="dash-board-sub-pack-main">
                                    <div class="subcription-card-main-p ">
                                        <div class="black-box-color"></div>
                                        <div class="inner-wrapper-card-1">

                                            <div class="back-ground-grenn-box">
                                                <h3 class="tittle-pack-nameee text-truncat"> {{translate($package->name)}}</h3>
                                            </div>
                                            <div class="content-area-package">
                                                <h2 class="price-tittle-pac text-truncate">{{$package->isFree() ? 0: getPrice($package->price, $currency) }}</h2>
                                                <h3 class="month-tittle text-truncate">{{__('Per')}} {{$package->duration ." ".$package->duration_type}}</h3>
                                                <ul class="ul-listing-of-pack">
                                                    {!! translate($package->description) !!}
                                                </ul>
                                                <div class="sub-button-main-block">
                                                    @if($package->id == $subscriptionId)
                                                        <button type="button"
                                                                data-package_id="{{$package->id}}"
                                                                class="btn btn-primary w-100">
                                                            {{__('Purchased')}}
                                                        </button>
                                                    @else
                                                        <button type="button"
                                                                data-package_id="{{$package->id}}"
                                                                class="btn btn-primary w-100 exampleModalCenter">
                                                            {{__('Continue')}}
                                                        </button>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            @include('front.common.alert-empty', ['message'=>__('No record found')])
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="d-none">
            <form method="post" id="submit_form" action="{{route('front.dashboard.subscription.payment')}}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" value="{{$package->id}}" id="model-package-id" name="package_id">
                    <label class="contain">{{__('Paypal')}}
                        <input type="radio" checked="checked" name="payment_method" value="paypal">
                        <span class="checkmark"></span>
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary d-none" data-dismiss="modal">{{__('Close')}}
                    </button>
                    <button type="submit" class="btn primary-btn">{{__('Pay Now')}}</button>
                </div>
            </form>
        </div>
        <!--End-->
    </section>


@endsection

@push('scripts')
    <script>
        $('.exampleModalCenter').on('click', function () {
            $('#model-package-id').val($(this).data("package_id"));
            $('#submit_form').submit();
        });
    </script>
@endpush
