@extends('front.layouts.app')
@section('content')
    @include('front.common.breadcrumb')

    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                @forelse($images as $key => $image)
                    <div class="col-md-6 col-lg-4">
                        <div class="iamge-gallery-card-page">
                            <div class="inner-wrapper-gallery">
                                <h3 class="tittle-numberss">{{$key}}</h3>
                                <div class="iamge-block-gallery">
                                    <a href="{!! url($image->image)!!}" data-lightbox="models"
                                       data-title="Caption1" class="">
                                        <img src="{{imageUrl($image->image,360,221,95,1)}}" class="img-fluid"
                                             alt="image">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    @include('front.common.alert-empty',['message'=>__('No Image found.')])
                @endforelse
            </div>

            {{ $images->withQueryString()->links('front.common.pagination', ['paginator' => $images]) }}
        </div>
    </section>

@endsection

@push('scripts')

@endpush
