@extends('admin.layouts.app')

@section('breadcrumb')
    @include('admin.common.breadcrumbs')
@endsection

@push('stylesheet-page-level')
@endpush

@push('script-page-level')

    <script>
        $('#admin_form').validate({
            ignore: '',
            rules: {
                'name': {
                    required: true,
                    noSpace: true,
                },
                email: {
                    required: true,
                    email: true,
                },
            },
            errorPlacement: function (error, element) {
                console.log(element.attr('name'));
                if (element.attr("name") == "abc") {
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

            <div class="m-portlet m-portlet--full-height m-portlet--tabs  ">

                <div class="m-portlet__head">

                    <div class="m-portlet__head-tools">

                        <ul class="nav nav-tabs m-tabs m-tabs-line   m-tabs-line--left m-tabs-line--primary"
                            role="tablist">

                            <li class="nav-item m-tabs__item">

                                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_user_profile_tab_1"
                                   role="tab">

                                    <i class="flaticon-share m--hide"></i>

                                    Edit Administrator

                                </a>

                            </li>

                        </ul>

                    </div>

                </div>

                <div class="tab-content">

                    <div class="tab-pane active" id="m_user_profile_tab_1">

                        @include('admin.administrators.form', ['admin', $admin, 'action' => $action])

                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection



