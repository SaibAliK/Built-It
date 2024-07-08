@extends('admin.layouts.app')

@section('breadcrumb')
    @include('admin.common.breadcrumbs')
@endsection

@push('stylesheet-page-level')
@endpush

@push('script-page-level')
    <script src="{{asset('assets/admin/js/adv_datatables/csrf_token.js')}}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            $('.delete-record-button').on('click', function (e) {
                var url = $(this).data('url');
                swal({
                        title: "Are you sure you want to delete this?",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#1C4670",
                        confirmButtonText: "Delete",
                        cancelButtonText: "No",
                        closeOnConfirm: false,
                        closeOnCancel: false,
                        showLoaderOnConfirm: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                type: 'delete',
                                url: url,
                                dataType: 'json',
                                headers: {
                                    'X-CSRF-TOKEN': window.Laravel.csrfToken
                                }
                            })
                                .done(function (res) {
                                    toastr.success("Sub Category Deleted Successfully!");
                                    location.reload();
                                })
                                .fail(function (res) {
                                    toastr.success("You have deleted inquiry successfully!");
                                });
                        } else {
                            swal.close();
                        }
                    });
            });
        })
    </script>
@endpush

@section('content')
    <div class="m-portlet m-portlet--mobile">

        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m-portlet__head-text">
                        Categories
                        <small>
                            Here You Can Add, Edit Or Delete Category And Sub Category
                        </small>
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <ul class="m-portlet__nav">
                    <li class="m-portlet__nav-item">
                        <div class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push"
                             data-dropdown-toggle="hover" aria-expanded="true">
                            <a href="{!! route('admin.dashboard.categories.edit', 0) !!}"
                               class="btn btn-accent m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill">
                                <span>
                                    <i class="la la-plus"></i>
                                    <span>
                                        Add Category
                                    </span>
                                </span>
                            </a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="manage-category">
            <div class="card-body tree-category-list-mt">
                <ul class="tree ng-tns-c8-2 ng-star-inserted">
                    @forelse($categories as $key => $category)
                        <li class="ng-tns-c8-2 ng-star-inserted"> {!! translate($category->name) !!}
                            <a class="btn btn-primary btn-sm ml-1 ng-tns-c8-2 ng-star-inserted" data-placement="top"
                               data-toggle="tooltip" title="Add Model"
                               href="{!! route('admin.dashboard.categories.sub-categories.edit', [$category->id,0]) !!}"><i
                                        class="fa fa-plus text-white" aria-hidden="true"></i></a><a
                                    class="btn btn-success btn-sm ml-1 square-box" data-placement="top"
                                    data-toggle="tooltip"
                                    title="Update" href="{!! route('admin.dashboard.categories.edit', $category->id) !!}"><i
                                        class="fa fa-pencil text-white" aria-hidden="true"></i></a>
                            <a href="javascript:{}"
                               data-url=" {!! route('admin.dashboard.categories.destroy', $category->id) !!} "
                               class="btn btn-danger btn-sm ml-1 ng-tns-c8-2 ng-star-inserted delete-record-button"
                               data-placement="top" data-toggle="tooltip"
                               style="background-color: #dc3545;border-color: #dc3545;" title="Delete"><i
                                        class="fa fa-trash text-white" aria-hidden="true"></i></a>
                            <ul class="ng-tns-c8-2 ng-star-inserted">
                                @forelse($category->subCategories as $key2 => $subCategory)
                                    <li class="ng-tns-c8-2 ng-star-inserted"> {!! translate( $subCategory->name) !!}
                                        <a class="btn btn-success btn-sm ml-1" data-placement="top"
                                           data-toggle="tooltip"
                                           title="Update"
                                           href="{!! route('admin.dashboard.categories.sub-categories.edit', [$category->id,$subCategory->id]) !!}"><i
                                                    class="fa fa-pencil text-white" aria-hidden="true"></i></a>
                                        <a href="javascript:{}"
                                           data-url=" {!! route('admin.dashboard.categories.destroy', $subCategory->id) !!} "
                                           class="btn btn-danger btn-sm ml-1 ng-tns-c8-2 ng-star-inserted delete-record-button"
                                           data-placement="top" data-toggle="tooltip"
                                           style="background-color: #dc3545;border-color: #dc3545;" title="Delete"><i
                                                    class="fa fa-trash text-white" aria-hidden="true"></i></a>

                                    </li>
                                @empty

                                @endforelse
                            </ul>
                        </li>
                    @empty
                    @endforelse

                </ul>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="manage-pages" id="local_data"></div>
        </div>
        <div class="m-portlet__body">
        </div>
    </div>
@endsection
