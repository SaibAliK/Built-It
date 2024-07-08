@extends('admin.layouts.app')

@section('breadcrumb')
@include('admin.common.breadcrumbs')
@endsection

@push('stylesheet-page-level')
@endpush

@push('script-page-level')
    <script src="{{asset('assets/admin/js/adv_datatables/csrf_token.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/admin/js/adv_datatables/company.js')}}" type="text/javascript"></script>
@endpush

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                     Companies
                        <small>
                            Here You Can Add, Edit or Delete Stores
                        </small>
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" data-dropdown-toggle="hover" aria-expanded="true">
                            <a href="{!! route('admin.dashboard.delivery-companies.edit', 0) !!}" class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                                <span>
                                    <i class="la la-plus"></i>
                                    <span>
                                        Add Delivery Company
                                    </span>
                                </span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <div class="row align-items-center">
                    <div class="col-xl-12 order-2 order-xl-1">
                        <form class="form-group m-form__group row align-items-center" action="" id="manage-companies-search">
                            <div class="col-md-12" style="margin-top: -22px;margin-bottom: 15px;">
                                <h3>Advance Search for Companies</h3>
                            </div>
                            {{--Name--}}

                            <div class="col-md-4 m--margin-bottom-10">
                                <div class="m-form__group m-form__group--inline">
                                    <div class="m-form__label">
                                        <label>
                                        </label>
                                    </div>
                                    <div class="m-form__control">
                                        <input type="text" class="form-control m-bootstrap-select" name="name" placeholder="Name">
                                    </div>
                                </div>
                            </div>
                            {{--Email--}}
                            <div class="col-md-4 m--margin-bottom-10">
                                <div class="m-form__group m-form__group--inline">
                                    <div class="m-form__label">
                                        <label class="m-label m-label--single">
                                        </label>
                                    </div>
                                    <div class="m-form__control">
                                        <input type="text" class="form-control m-bootstrap-select" name="email" placeholder="Email">
                                    </div>
                                </div>
                            </div>
                            {{--Email status--}}
                            <div class="col-md-4">
                                <div class="m-form__group m-form__group--inline">
                                    <div class="m-form__label">
                                        <label class="m-label m-label--single" for="show-user-gender">
                                        </label>
                                    </div>
                                    <div class="m-form__control">
                                        <select class="form-control" id="show-email-status" name="emailStatus">
                                            <option value="">Email Status</option>
                                            <option value="1">Verified</option>
                                            <option value="0">Unverified</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{--Rating--}}
                            <div class="col-md-4">
                                <div class="m-form__group m-form__group--inline">
                                    <div class="m-form__label">
                                        <label class="m-label m-label--single" for="show-user-gender">
                                        </label>
                                    </div>
                                    <div class="m-form__control">
                                        <select class="form-control" id="show-store-rating" name="rating">
                                            <option value="">Select Rating Order</option>
                                            <option value="asc">Ascending</option>
                                            <option value="desc">Descending</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{--Active status--}}
                            <div class="col-md-4">
                                <div class="m-form__group m-form__group--inline">
                                    <div class="m-form__label">
                                        <label class="m-label m-label--single" for="show-user-gender">
                                        </label>
                                    </div>
                                    <div class="m-form__control">
                                        <select class="form-control" id="show-active-status" name="activeStatus">
                                            <option value="">Active Status</option>
                                            <option value="1">Is Active</option>
                                            <option value="0">Not Active</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="user-deleted-at" style="display:none;" class="col-md-4">
                                <div class="m-form__group m-form__group--inline">
                                    <div class="m-form__label">
                                        <label>
                                        </label>
                                    </div>
                                    <div class="m-form__control">
                                        <input type="text"  class="form-control m-bootstrap-select mt-datetime-picker" name="deletedAt" placeholder="deleted At">
                                    </div>
                                </div>
                                <div class="d-md-none m--margin-bottom-10"></div>
                            </div>
                            <div class="col-md-12 text-right mt-3">
                                <button type="submit" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">Submit</button>
                                <button id="page-reset" class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">Reset</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <!--end: Search Form -->
        </div>
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <!--end: Search Form -->
            <!--begin: Datatable -->
        <div class="manage-company" id="manage-company">
            </div>
            <!--end: Datatable -->
        </div>
    </div>
@endsection

