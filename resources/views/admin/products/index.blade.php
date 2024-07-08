@extends('admin.layouts.app')

@section('breadcrumb')
    @include('admin.common.breadcrumbs')
@endsection

@push('stylesheet-page-level')
@endpush

@push('script-page-level')
    <script src="{{asset('assets/admin/js/adv_datatables/csrf_token.js')}}" type="text/javascript"></script>
    <script src="{{asset('assets/admin/js/adv_datatables/products.js')}}" type="text/javascript"></script>
@endpush

@section('content')
    <div class="m-portlet m-portlet--mobile">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Product
                        <small>
                            Here You Can Delete Product
                        </small>
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                <div class="row align-items-center">
                    <div class="col-xl-12 order-2 order-xl-1">
                        <form class="form-group m-form__group row align-items-center" action="" id="product-list">
                            <div class="col-md-12" style="margin-top: -22px;margin-bottom: 15px;">
                                <h3>Advance Search for Products</h3>
                            </div>

                            <div class="col-md-4 m--margin-bottom-10">
                                <div class="m-form__group m-form__group--inline">
                                    <div class="m-form__label">
                                        <label>
                                        </label>
                                    </div>
                                    <div class="m-form__control">
                                        <input type="text" class="form-control m-bootstrap-select" name="title"
                                               placeholder="Title">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 m--margin-bottom-10">
                                <div class="m-form__group m-form__group--inline">
                                    <div class="m-form__label">
                                        <label>
                                        </label>
                                    </div>
                                    <div class="m-form__control">
                                        <select class="form-control mt-select2" id="category_id" name="category_id">
                                            <option value="">Select Category</option>
                                            @forelse($categories as $category)
                                                <option
                                                    value="{{$category->id}}">{{translate($category->name)}}</option>
                                            @empty
                                            @endforelse

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3 text-right">
                                <button type="submit"
                                        class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                                    Submit
                                </button>
                                <button id="page-reset"
                                        class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                                    Reset
                                </button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
            <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30 collapse"
                 id="m_datatable_group_action_form_page">
                <div class="row align-items-center">
                    <div class="col-xl-12">
                        <div class="m-form__group m-form__group--inline">
                            <div class="m-form__label m-form__label-no-wrap">
                                <label class="m--font-bold m--font-danger-">
                                    Selected
                                    <span id="m_datatable_selected_page"></span>
                                    records:
                                </label>
                            </div>
                            <div class="m-form__control">
                                <div class="btn-toolbar">
                                    &nbsp;&nbsp;&nbsp;
                                    <button class="btn btn-sm btn-danger" type="button"
                                            id="m_datatable_check_all_pages">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="manage-pages" id="local_data"></div>
        </div>
        <div class="m-portlet__body">
            <div class="list" id="list"></div>
        </div>
    </div>

@endsection


