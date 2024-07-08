@extends('front.layouts.app')

@section('content')
    @include('front.common.breadcrumb')

    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                @include('front.dashboard.common.left-sidebar')
                <div class="col-lg-8 col-md-8">
                    <div class="review-total d-flex align-items-center justify-content-between mb-3">
                        <div class="title">{{__('Ratings')}} <span
                                class="total-reviewss">({{$reviews->total()}} {{__('Reviews')}})</span></div>
                        <div class="review-cot d-flex align-items-center">
                            <div class="star-rating-area review-page-mt d-flex align-items-center">
                                <div class="ratilike ng-binding mt-0 ml-1">
                                    @if(auth()->user()->rating > 0)
                                        {{ number_format(auth()->user()->rating,1) }}
                                    @else
                                        0
                                    @endif
                                </div>
                                <div class="rating-static clearfix"
                                     rel="{{round(getStarRating(auth()->user()->rating),1)}}">
                                    <label class="full" title="Awesome - 5 stars" style="color: #ccc;"></label>
                                    <label class="half" title="{{__('Pretty good - 4.5 stars')}}"
                                           style="color: #ccc;"></label>
                                    <label class="full" title="{{__('Pretty good - 4 stars')}}"></label>
                                    <label class="half" title="{{__('Meh - 3.5 stars')}}"></label>
                                    <label class="full" title="{{__('Meh - 3 stars')}}"></label>
                                    <label class="half" title="{{__('Kinda bad - 2.5 stars')}}"></label>
                                    <label class="full" title="{{__('Kinda bad - 2 stars')}}"></label>
                                    <label class="half" title="{{__('Meh - 1.5 stars')}}"></label>
                                    <label class="full" title="{{__('You can do better - 1 star')}}"></label>
                                    <label class="half" title="{{__('You can do better - 0.5 stars')}}"></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            @forelse($reviews as $key => $item)
                                <div class="review-list-box rating-page-cls d-flex ">
                                    <div class="review-img">
                                        <img src="{!! imageUrl(url($item->user->image),50,50,100,1) !!}"
                                             class="img-fluid" alt="">
                                    </div>
                                    <div class="review-detail-box">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="tittle-left-starts-icons d-flex align-items-center">
                                                <div class="title">{{$item->user->user_name ?? ''}}</div>
                                                <div class="starts-tittle-icon">
                                                    <span class="tittle">
                                                         @if($item->rating > 0)
                                                            {{ number_format($item->rating,1) }}
                                                        @else
                                                            0
                                                        @endif
                                                    </span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14.631" height="14"
                                                         viewBox="0 0 14.631 14">
                                                        <path id="Path_48396" data-name="Path 48396"
                                                              d="M9.953-11.758a.832.832,0,0,1,.479-.437.931.931,0,0,1,.629,0,.832.832,0,0,1,.479.438l1.777,3.609,3.992.574a.83.83,0,0,1,.561.328.913.913,0,0,1,.191.6.839.839,0,0,1-.26.574L14.9-3.254,15.586.738a.864.864,0,0,1-.123.615.8.8,0,0,1-.506.369.843.843,0,0,1-.629-.082L10.746-.219,7.164,1.641a.843.843,0,0,1-.629.082.8.8,0,0,1-.506-.369A.864.864,0,0,1,5.906.738L6.59-3.254,3.691-6.07a.839.839,0,0,1-.26-.574.913.913,0,0,1,.191-.6.83.83,0,0,1,.561-.328l3.992-.574Z"
                                                              transform="translate(-3.431 12.25)" fill="#ff6a00"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div
                                                class="reviw-time">{{Carbon\Carbon::parse($item->updated_at)->diffForHumans()}}</div>
                                        </div>

                                        <div class="des clearfix">
                                            {!!nl2br($item->review) !!}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-sm-12">
                                    <div class="review">
                                        <div class="alert alert-danger"
                                             role="alert">
                                            {{__('No Reviews')}}
                                        </div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                        {{ $reviews->withQueryString()->links('front.common.pagination', ['paginator' => $reviews]) }}
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>

    </script>
@endpush
