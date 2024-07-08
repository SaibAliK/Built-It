@extends('front.layouts.app')
@section('content')
    @include('front.common.breadcrumb')

    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="supplier-listing-page-serch">
                        <div class="custom-input-map-near">
                            <div class="near-input-plus">
                                <div class="input-style">
                                    <div class="loc-icon-input-near">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13.5" height="18"
                                             viewBox="0 0 13.5 18">
                                            <path id="Path_48396" data-name="Path 48396"
                                                  d="M6.047,1.9a.807.807,0,0,0,.7.352.807.807,0,0,0,.7-.352L9.809-1.477q1.758-2.531,2.32-3.41a9.9,9.9,0,0,0,1.09-2.127A6.4,6.4,0,0,0,13.5-9a6.515,6.515,0,0,0-.914-3.375,6.879,6.879,0,0,0-2.461-2.461A6.515,6.515,0,0,0,6.75-15.75a6.515,6.515,0,0,0-3.375.914A6.879,6.879,0,0,0,.914-12.375,6.515,6.515,0,0,0,0-9,6.4,6.4,0,0,0,.281-7.014a9.9,9.9,0,0,0,1.09,2.127q.563.879,2.32,3.41Q5.133.563,6.047,1.9Zm.7-8.086a2.708,2.708,0,0,1-1.986-.826A2.708,2.708,0,0,1,3.938-9a2.708,2.708,0,0,1,.826-1.986,2.708,2.708,0,0,1,1.986-.826,2.708,2.708,0,0,1,1.986.826A2.708,2.708,0,0,1,9.563-9a2.708,2.708,0,0,1-.826,1.986A2.708,2.708,0,0,1,6.75-6.187Z"
                                                  transform="translate(0 15.75)" fill="#45cea2"></path>
                                        </svg>
                                    </div>
                                    <input type="text" class="ctm-input" id="keyword"
                                           value="{{ $request->keyword ?? '' }}"
                                           placeholder="{{ __('Search Supplier') }}">
                                    @if ($request->keyword != '')
                                        <button type="button" class="btn btn-primary" id="clear-search">
                                            <i class="fas fa-times"></i></button>
                                    @else
                                        <button type="button" id="supplier-search" class="btn btn-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                 viewBox="0 0 24 24">
                                                <path id="Path_11" data-name="Path 11"
                                                      d="M49.438,58.778A9.17,9.17,0,0,0,55.4,56.6l7.248,7.248a.85.85,0,0,0,1.2,0,.85.85,0,0,0,0-1.2L56.6,55.4a9.324,9.324,0,1,0-7.165,3.375Zm0-16.98A7.641,7.641,0,1,1,41.8,49.438,7.649,7.649,0,0,1,49.438,41.8Z"
                                                      transform="translate(-40.099 -40.099)" fill="#fff"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- card area start here -->
                @forelse($suppliers as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="supplier-listing-card-page">
                            <div class="card-click-show-main">
                                <div class="inner-wrapper-2nd-card">
                                    <div class="image-block-2nd-card">
                                        <a href="{{ route('front.supplier.detail', $item->id) }}">
                                            <img src="{{ imageUrl($item->image_url, 340, 157, 90, 1) }}"
                                                 class="img-fluid" alt="image">
                                        </a>
                                    </div>
                                    <div class="conetnt-2nd-card">
                                        <h3 class="tittle text-truncate">{{ translate($item->supplier_name) }}</h3>
                                        <div class="marker-tittle-p">
                                            <span class="lock-span-mar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="10.5" height="14" viewBox="0 0 10.5 14">
                              <path id="Path_48396" data-name="Path 48396"
                                    d="M4.7,1.477a.627.627,0,0,0,.547.273A.627.627,0,0,0,5.8,1.477L7.629-1.148Q9-3.117,9.434-3.8a7.7,7.7,0,0,0,.848-1.654A4.977,4.977,0,0,0,10.5-7a5.067,5.067,0,0,0-.711-2.625,5.35,5.35,0,0,0-1.914-1.914A5.067,5.067,0,0,0,5.25-12.25a5.067,5.067,0,0,0-2.625.711A5.35,5.35,0,0,0,.711-9.625,5.067,5.067,0,0,0,0-7,4.977,4.977,0,0,0,.219-5.455,7.7,7.7,0,0,0,1.066-3.8q.438.684,1.8,2.652Q3.992.438,4.7,1.477ZM5.25-4.812a2.106,2.106,0,0,1-1.545-.643A2.106,2.106,0,0,1,3.063-7a2.106,2.106,0,0,1,.643-1.545A2.106,2.106,0,0,1,5.25-9.187a2.106,2.106,0,0,1,1.545.643A2.106,2.106,0,0,1,7.438-7a2.106,2.106,0,0,1-.643,1.545A2.106,2.106,0,0,1,5.25-4.812Z"
                                    transform="translate(0 12.25)" fill="#45cea2"></path>
                            </svg>
                          </span>
                                            <h2 class="address-tittle text-truncate">{{ $item->address }}</h2>
                                        </div>
                                        <div class="star-rating-area contact-us d-flex">
                                            <div class="ratilike ng-binding pl-0">
                                                @if ($item->rating > 0)
                                                    {{ number_format($item->rating, 1) }}
                                                @else
                                                    0
                                                @endif
                                            </div>
                                            <div rel="{{ round(getStarRating($item->rating), 1) }}"
                                                 class="rating-static clearfix">
                                                <label class="full" title="{{ __('Awesome - 5 stars') }}"></label>
                                                <label class="half"
                                                       title="{{ __('Pretty good - 4.5 stars') }}"></label>
                                                <label class="full"
                                                       title="{{ __('Pretty good - 4 stars') }}"></label>
                                                <label class="half" title="{{ __('Good - 3.5 stars') }}"></label>
                                                <label class="full" title="{{ __('Good - 3 stars') }}"></label>
                                                <label class="half" title="{{ __('Average - 2.5 stars') }}"></label>
                                                <label class="full" title="{{ __('Average - 2 stars') }}"></label>
                                                <label class="half"
                                                       title="{{ __('You can do better - 1.5 stars') }}"></label>
                                                <label class="full"
                                                       title="{{ __('You can do better - 1 star') }}"></label>
                                                <label class="half"
                                                       title="{{ __('You can do better - 0.5 stars') }}"></label>
                                            </div>
                                        </div>
                                        <div class="phone-number-parent">
                                            <span class="span-svg">
                              <svg id="Component_5_1" data-name="Component 5 – 1" xmlns="http://www.w3.org/2000/svg"
                                   width="9.999" height="14.524" viewBox="0 0 9.999 14.524">
                                <path id="Path_29" data-name="Path 29"
                                      d="M70.082,10.893a.352.352,0,0,1-.3-.161s0-.007-.007-.011a7.182,7.182,0,0,1-.782-3.459A7.177,7.177,0,0,1,69.774,3.8l.007-.011a.351.351,0,0,1,.3-.161V0c-2.005,0-3.631,3.251-3.631,7.262s1.626,7.262,3.631,7.262Z"
                                      transform="translate(-66.451)" fill="#45cea2"></path>
                                <path id="Path_30" data-name="Path 30"
                                      d="M187.661.436A.436.436,0,0,0,187.225,0h-1.307V3.631h1.307a.436.436,0,0,0,.436-.436Z"
                                      transform="translate(-181.851)" fill="#45cea2"></path>
                                <path id="Path_31" data-name="Path 31"
                                      d="M187.661,320.436a.436.436,0,0,0-.436-.436h-1.307v3.631h1.307a.436.436,0,0,0,.436-.436Z"
                                      transform="translate(-181.851 -309.107)" fill="#45cea2"></path>
                                <path id="Path_32" data-name="Path 32"
                                      d="M237.663,176.049l-.545-.545a1.156,1.156,0,0,0,0-1.634l.545-.545a1.926,1.926,0,0,1,0,2.724Z"
                                      transform="translate(-231.308 -167.425)" fill="#45cea2"></path>
                                <path id="Path_33" data-name="Path 33"
                                      d="M269.637,146.247l-.545-.545a2.692,2.692,0,0,0,0-3.811l.545-.545a3.462,3.462,0,0,1,0,4.9Z"
                                      transform="translate(-262.193 -136.534)" fill="#45cea2"></path>
                                <path id="Path_34" data-name="Path 34"
                                      d="M301.629,116.434l-.545-.545a4.23,4.23,0,0,0,0-5.989l.545-.545a5,5,0,0,1,0,7.079Z"
                                      transform="translate(-293.097 -105.633)" fill="#45cea2"></path>
                              </svg>


                            </span>
                                            <a href="tel:{{ $item->phone }}" class="phone-tittle"
                                               dir="ltr">{{ $item->phone }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <h2>{{ __('No Record Found') }}</h2>
                @endforelse

            </div>
            {{ $suppliers->withQueryString()->links('front.common.pagination', ['paginator' => $suppliers]) }}
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $("#clear-search").on('click', function () {
                window.location.href = "{{ route('front.suppliers') }}";
            });

            $("#supplier-search").on('click', function () {
                let SubName = $('#keyword').val();
                window.location.href = "{{ route('front.suppliers') }}?keyword=" + SubName;
            });
            $("#keyword").keypress(function (event) {
                if (event.which == 13) {
                    let SubName = $('#keyword').val();
                    window.location.href = "{{ route('front.suppliers') }}?keyword=" + SubName;
                }
            });
        });
    </script>
@endPush
