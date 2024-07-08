@extends('front.layouts.app')
@section('content')
    @include('front.common.breadcrumb')
    {!! translate($page->content) !!}
@endsection


