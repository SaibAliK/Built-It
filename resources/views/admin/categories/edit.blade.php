@extends('admin.layouts.app')
@section('breadcrumb')
    @include('admin.common.breadcrumbs')
@endsection

@push('stylesheet-page-level')
@endpush

@push('script-page-level')
    <script>
        $('#cate_form').validate({
            ignore: '',
            rules: {
                'name[en]': {
                    required: true,
                    noSpace: true,
                },
                'name[ar]': {
                    required: true,
                    noSpace: true,
                },
                'image': {
                    required: true,
                }
            },
            errorPlacement: function (error, element) {
                console.log(element.attr('name'));
                if (element.attr("name") == "terms_conditions") {
                    error.insertAfter(element.parent().siblings());
                } else {
                    error.insertAfter(element);
                }
            },
        });
    </script>
@endpush



@section('content')
    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div class="m-portlet m-portlet--full-height m-portlet--tabs">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-tools">
                    </div>
                </div>

                <div class="tab-content">

                    <div class="tab-pane active" id="tab_en">
                        @include('admin.categories.form', ['languageId' => $locales[0]])
                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection

