@extends('front.layouts.app')
@section('content')
    <section class="four-o-four">
        <div class="content-box">
            <div class="error-img-block">
                <img src="front/assets/img/mask.png" alt="" class="img-fluid">
            </div>
            <div class="four-detail">{{__('404 - page not found')}}</div>
            <div class="page-removed">{{__('The page you are looking for might have been removed')}} <br>
                {{__('had its name changed or is temporarily unavailable.')}}</div>
            <div class="four-btn d-flex justify-content-center">
                <button class="primary-btn">
                    <a href="{{route('front.index')}}" class="primary-btn">{{__('Back to Homepage')}}</a>
                </button>
            </div>
        </div>
    </section>
@endsection
