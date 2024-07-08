@extends('front.layouts.app')
@section('content')
    @include('front.common.breadcrumb')
    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                @forelse($articles as $key => $article)
                    <div class="col-md-6 col-lg-4">
                        <a href="{{ route('front.article.detail', $article->slug) }}">
                            <div class="aritcal-page-card-main">
                                <div class="top-date-and-text">
                                    <h3 class="date-tittle-green">{{ \Carbon\Carbon::parse($article->created_at)->format('M Y') }}</h3>
                                    <h4 class="dec-tittle-card"> {!! translate($article->name) !!}</h4>
                                </div>
                                <div class="arical-image-card-block">
                                    <img src="{!! imageUrl(url($article->image_url), 320, 140, 90) !!}"
                                         class="img-fluid" alt="image">
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    @include('front.common.alert-empty', ['message' => __('No Article found.')])
                @endforelse
            </div>
            {{ $articles->withQueryString()->links('front.common.pagination', ['paginator' => $articles]) }}

        </div>
    </section>


@endsection

@push('scripts')
@endpush
