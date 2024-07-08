@extends('admin.layouts.app')
@section('breadcrumb')
    @include('admin.common.breadcrumbs')
@endsection

@push('stylesheet-page-level')
@endpush

@push('script-page-level')


@endpush

@section('content')
    <div class="container" id="kt_docs_content_container">
        <!--begin::Card-->
        <div class="card card-docs mb-2">
            <!--begin::Card Body-->
            <div class="card-body fs-6 py-15 px-10 py-lg-15 px-lg-15 text-gray-700">
                <div class="px-md-10 pt-md-10 pb-md-5">
                    <div class="text-center mb-20">
                        <h1 class="fs-2tx fw-bolder mb-8">Welcome To The Admin Panel</h1>
                        <div class="fw-bold fs-2 text-gray-500 mb-10">On the left side you will see the modules
                            Categories, Attributes And Orders Modules are complete. Product Module is pending.
                    </div>
                </div>
            </div>
        </div>
        <!--end::Card-->
    </div>

@endsection

@section('custom_js')

@endsection
