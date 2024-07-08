@extends('admin.layouts.app')

@section('breadcrumb')
    @include('admin.common.breadcrumbs')
@endsection

@push('stylesheet-page-level')
@endpush

@push('script-page-level')
    <script>
        $(document).ready(function () {
            $('#add-car-image').on('click', function () {
                $('#car-image-input').click();
            });
        });
        $(document).on('change', '#car-image-input', function () {
            $('#car-images-form').submit();
        });

        $('.delete-record-button').on('click', function (e) {
            var url = $(this).data('url');
            swal({
                    title: "Are you sure to delete this?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#1C4670",
                    confirmButtonText: "Delete",
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    closeOnCancel: false
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
                                toastr.success("You have deleted inquiry successfully!");
                                location.reload();
                            })
                            .fail(function (res) {
                                toastr.success("You have deleted inquiry successfully!");
                                location.reload();
                            });
                    } else {
                        swal.close();
                    }
                });
        });
    </script>
@endpush

@section('content')
    <div class="m-portlet m-portlet--mobile">

        <div class="m-portlet__head">
            <div class="d-flex justify-content-between align-items-start">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Gallery Images
                            <small>
                                Here You Can Add or Delete Gallery Images
                            </small>
                        </h3>
                    </div>
                </div>
                {{--                @if(count($images)<6)--}}
                <div class="m-portlet__head-tools">
                    <ul class="m-portlet__nav">
                        <li class="m-portlet__nav-item">
                            <form action="{!! route('admin.dashboard.galleries.store') !!}" method="POST"
                                  enctype="multipart/form-data" id="car-images-form">
                                {!! csrf_field() !!}
                                <div
                                    class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push"
                                    data-dropdown-toggle="hover" aria-expanded="true">
                                    <label for="car-image-input"
                                           class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill d-flex align-items-center">
                                        <i class="la la-plus mr-1"></i> Add Gallery Images
                                        <input type="file" name="images[]" id="car-image-input" accept="image/*"
                                               class="d-none" multiple>
                                    </label>
                                    <p class="my-0 text-danger smaller-font-size">Recommended size 553 x 553</p>
                                </div>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="container">
                <div class="row mt-4">
                    @forelse($images as $image)
                        <div class="col-sm-4 mb-4">
                            <div class="card">
                                <a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete-record-button"
                                   href="javascript:{};" data-url="{!! route('admin.dashboard.galleries.destroy',$image->id) !!}"><i
                                        class="fa fa-trash"></i></a>
                                <img src="{!! imageUrl(asset($image->image), 292, 200,90,3) !!}" alt="">
                            </div>
                        </div>
                    @empty
                        <div class="col-12 d-flex justify-content-center mt-2 mb-2 text-danger">
                            No Record Found
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

