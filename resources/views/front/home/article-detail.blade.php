@extends('front.layouts.app')
@section('content')
    @include('front.common.breadcrumb')
    <section class="mb-70-mt-100">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="artical-detail-page-parent">
                        <div class="image-block-artical-dt">
                            <img src="{!! imageUrl(url($article->image),655,544,90) !!}" class="img-fluid" alt="image">
                        </div>
                        <div class="right-side-descp-dt-art">
                            <div class="top-date-and-text">
                                <h3 class="date-tittle-green">{{ \Carbon\Carbon::parse($article->created_at)->format('M Y') }}</h3>
                                <h4 class="dec-tittle-card">{!! translate($article->name) !!}</h4>
                            </div>
                            {!! translate($article->content)!!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')

@endpush
