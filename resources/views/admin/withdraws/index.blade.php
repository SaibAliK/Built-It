@extends('admin.layouts.app')

@section('breadcrumb')
    @include('admin.common.breadcrumbs')
@endsection

@push('stylesheet-page-level')
@endpush

@push('script-page-level')
    <script src="{{asset('assets/admin/js/adv_datatables/csrf_token.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/admin/js/adv_datatables/withdraws.js')}}" type="text/javascript"></script>
@endpush

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Manage Payment Release Requests
                        <small>
                            Here you can manage payment release requests
                        </small>
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <!--begin: Search Form -->
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <div class="row align-items-center">
                    <div class="col-xl-12 order-2 order-xl-1">
                        <form class="form-group m-form__group row align-items-center" action=""
                              id="manage-agency-search">
                            <div class="col-md-12" style="margin-top: -22px;margin-bottom: 15px;">
                                <h3>Advance Search for Store Withdraw Request</h3>
                            </div>
                            <div class="col-md-4 m--margin-bottom-10">
                                <div class="m-form__group m-form__group--inline">
                                    <div class="m-form__label">
                                        <label>
                                        </label>
                                    </div>
                                    <div class="m-form__control">
                                        <select class="form-control mt-select2" id="store_name" name="store_name">
                                            <option value="">Select Store</option>
                                            @forelse($stores as $store)
                                                <option value="{{$store->id ?? ''}}">{{$store->supplier_name['en'] ?? 'N/A'}}</option>
                                            @empty
                                            @endforelse

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="m-form__group m-form__group--inline">
                                    <div class="m-form__label">
                                        <label class="m-label m-label--single" for="show-user-gender">
                                        </label>
                                    </div>
                                    <div class="m-form__control">
                                        <select class="form-control" id="show-withdraw-status" name="withdraw_status">
                                            <option value="">Status</option>
                                            <option value="pending">Pending</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit"
                                        class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                                    Submit
                                </button>
                                <button id="page-reset"
                                        class="btn btn-success m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                                    Reset
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <!--end: Search Form -->
        </div>
        <div class="m-portlet__body">
            <!--begin: Datatable -->
            <div class="manage-withdraws" id="withdraws_data"></div>
            <!--end: Datatable -->
        </div>
    </div>

@endsection



