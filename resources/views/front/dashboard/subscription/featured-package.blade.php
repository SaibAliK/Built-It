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
                        <div class="col-lg-12 mx-auto">
                            <ul class="nav nav-pills mb-26 register-tabs" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link all_pkg {{ request()->has('purchase_pkg') ? '' : 'active' }}">{{__('All Packages')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link purchase_pkg {{ request()->has('purchase_pkg') ? 'active' : '' }}">{{__('Purchased Packages')}}</a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show {{ request()->has('purchase_pkg') ? '' : 'active' }}">
                                    <div class="row">
                                        @forelse($packages as $package)
                                            <div class="col-md-6 col-lg-4">
                                                <form method="post"
                                                      action="{{ route('front.dashboard.featured.subscription.payment') }}">
                                                    @csrf
                                                    <input type="hidden" value="{{ $package->getOriginal('price') }}"
                                                           name="package_price">
                                                    <input type="hidden" value="{{ $package->duration }}"
                                                           name="duration">
                                                    <input type="hidden" value="{{ $package->name['en'] }}"
                                                           name="package_name">
                                                    <input type="hidden" value="{{ $package->id }}" name="package_id">
                                                    <input type="hidden"
                                                           value="{{ old($package->subscription_type, 'featured') }}"
                                                           name="package_type">
                                                    <div class="feature-card-dashboard-main">
                                                        <h2 class="basic-tittle text-truncate">{{ translate($package->name) }}</h2>
                                                        <h3 class="price-tittle text-truncate">{{ getPrice($package->price, $currency) }}</h3>
                                                        <h4 class="tittle-decp-h4">{!! __(strip_tags(translate($package->description))) !!}
                                                            {{ $package->duration }}  {{ __($package->duration_type) }}</h4>
                                                        <div class="feature-button-page">
                                                            @if (isset($subscriptionId))
                                                                @if ($package->id == $subscriptionId)

                                                                @else
                                                                    <button type="submit"
                                                                            class="btn btn-black w-100 @if ($package->id == $subscriptionId) d-none @endif">
                                                                        {{ __('Buy Now') }}
                                                                    </button>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        @empty
                                            <div class="col-sm-12 cols alert alert-danger ml-1" role="alert">
                                                {{ __('No record found') }}
                                            </div>
                                        @endforelse
                                    </div>
                                    {{ $packages->withQueryString()->links('front.common.pagination', ['paginator' => $packages]) }}
                                </div>

                                <div
                                    class="tab-pane fade show {{ request()->has('purchase_pkg') ? 'active' : '' }}">
                                    <div class="row">
                                        @forelse($buyPackages as $package)
                                            <div class="col-md-6 col-lg-4">
                                                <form method="post"
                                                      action="{{ route('front.dashboard.featured.subscription.payment') }}">
                                                    @csrf
                                                    <input type="hidden" value="{{ $package->aed_price }}"
                                                           name="package_price">
                                                    <input type="hidden" value="{{ $package->package['duration'] }}"
                                                           name="duration">
                                                    <input type="hidden"
                                                           value="{{ translate($package->package['name']) }}}"
                                                           name="package_name">
                                                    <input type="hidden" value="{{ $package->package['id'] }}"
                                                           name="package_id">
                                                    <input type="hidden"
                                                           value="{{ old($package->package['subscription_type'], 'featured') }}"
                                                           name="package_type">

                                                    <div class="feature-card-dashboard-main">
                                                        <h2 class="basic-tittle text-truncate"> {{ translate($package->package['name']) }}</h2>
                                                        <h3 class="price-tittle text-truncate">{{ getPrice($package->package['price'], $currency) }}</h3>
                                                        <h4 class="tittle-decp-h4">{!! __(strip_tags(translate($package->package['description']))) !!}
                                                            {{ $package->package['duration'] }}
                                                            {{ __($package->package['duration_type']) }}</h4>
                                                        <h5 class="count-tittle-here text-truncate">{{__('Purchased Count:')}}
                                                            <span
                                                                class="numbers"> {{ $package->purchase_count }}</span>
                                                        </h5>
{{--                                                        <div class="feature-button-page">--}}
{{--                                                            <a href="#" class="btn btn-black w-100">Buy Now</a>--}}

{{--                                                        </div>--}}

                                                    </div>
                                                </form>
                                            </div>
                                        @empty
                                            <div class="col-sm-12 cols alert alert-danger ml-1" role="alert">
                                                {{ __('No record found') }}
                                            </div>
                                        @endforelse
                                    </div>
                                    {{ $buyPackages->withQueryString()->links('front.common.pagination', ['paginator' => $buyPackages]) }}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection

@push('scripts')
    <script>
        $('#exampleModalCenter').on('show.bs.modal', function (event) {
            console.log("this is the invoker element =>", event.relatedTarget);
            console.log("this is the package id =>", $(event.relatedTarget).data('package_id'));
            $('#model-package-id').val($(event.relatedTarget).data('package_id'));
        });

        $(document).on('click', ".all_pkg", function () {
            window.location.href = '?type=featured&all_pkg=' + true;
        });

        $(document).on('click', ".purchase_pkg", function () {
            window.location.href = '?type=featured&purchase_pkg=' + true;
        });

        function openPage(pageName, elmnt, color, color2) {
            // Hide all elements with class="tabcontent" by default */
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Remove the background color of all tablinks/buttons
            tablinks = document.getElementsByClassName("tablink");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].style.backgroundColor = "";
                tablinks[i].style.color = "";
            }

            // Show the specific tab content
            document.getElementById(pageName).style.display = "block";

            $(elmnt).css({
                'background-color': color,
                'color': color2
            });
        }
    </script>
@endpush
