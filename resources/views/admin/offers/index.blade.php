@extends('admin.layouts.app')

@section('breadcrumb')
    @include('admin.common.breadcrumbs')
@endsection

@push('stylesheet-page-level')
@endpush

@push('script-page-level')
    <script src="{{asset('assets/admin/js/adv_datatables/csrf_token.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/admin/js/adv_datatables/offer.js')}}" type="text/javascript"></script>
@endpush

@section('content')

    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Offer
                        <small>
                            Here You Can Accept, Reject Offers
                        </small>
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30 collapse"
                 id="m_datatable_group_action_form_user">
                <div class="row align-items-center">
                    <div class="col-xl-12">
                        <div class="m-form__group m-form__group--inline">
                            <div class="m-form__label m-form__label-no-wrap">
                                <label class="m--font-bold m--font-danger-">
                                    Selected
                                    <span id="m_datatable_selected_user"></span>
                                    records:
                                </label>
                            </div>
                            <div class="m-form__control">
                                <div class="btn-toolbar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button class="btn btn-sm btn-danger" type="button"
                                            id="m_datatable_check_all_users">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30 collapse"
                 id="m_datatable_group_action_form_user_restore">
                <div class="row align-items-center">
                    <div class="col-xl-12">
                        <div class="m-form__group m-form__group--inline">
                            <div class="m-form__label m-form__label-no-wrap">
                                <label class="m--font-bold m--font-danger-">
                                    Selected
                                    <span id="m_datatable_selected_user_restore"></span>
                                    records:
                                </label>
                            </div>
                            <div class="m-form__control">
                                <div class="btn-toolbar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button class="btn btn-sm btn-success" type="button"
                                            id="m_datatable_check_all_users_restore">
                                        Restore
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="manage-users" id="manage-users"></div>
        </div>
    </div>
@endsection




