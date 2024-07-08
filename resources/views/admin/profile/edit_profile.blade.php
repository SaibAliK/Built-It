@extends('admin.layouts.app')
@section('breadcrumb')
    @include('admin.common.breadcrumbs')
@endsection

@push('stylesheet-page-level')
@endpush

@section('content')

    <div class="row">
        <div class="col-lg-2"></div>
        <div class="col-lg-8">
            <div class="m-portlet m-portlet--full-height m-portlet--tabs">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-tabs m-tabs m-tabs-line   m-tabs-line--left m-tabs-line--primary"
                            role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#m_user_profile_tab_1"
                                   role="tab">
                                    <i class="flaticon-share m--hide"></i>
                                    Edit Profile
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="tab-content">
                    <div class="tab-pane active" id="m_user_profile_tab_1">
                        {!! Form::model($admin, ['route' => 'admin.dashboard.update-profile', 'method' => 'post', 'role' => 'form', 'class' => 'm-form m-form--fit m-form--label-align-right', 'enctype' => 'multipart/form-data']) !!}
                        <div class="m-portlet__body">
                            <div class="form-group m-form__group row">
                                <label for="example-text-input" class="col-3 col-form-label">
                                    {!! __('Full Name') !!}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-7">
                                    {!! Form::text('name', old('name',$admin['name']),['class' => 'form-control', 'id' => 'full_name', 'placeholder' => __('Full Name'), 'required' => 'required']) !!}
                                </div>
                            </div>

                            <div class="form-group m-form__group row">
                                <label for="example-text-input" class="col-3 col-form-label">
                                    {!! __('Email') !!}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-7">
                                    {!! Form::text('email', old('email', $admin['email']), ['class' => 'form-control', 'id' => 'email', 'placeholder' => __('Email'), 'required' => 'required', 'readonly'=>'readonly']) !!}
                                </div>
                            </div>

                            <div class="form-group m-form__group row">
                                <label for="example-text-input" class="col-3 col-form-label">
                                    {!! __('Password') !!}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-7">
                                    {!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'placeholder' => __('Password')]) !!}
                                </div>
                            </div>

                            <div class="form-group m-form__group row">
                                <label for="example-text-input" class="col-3 col-form-label">
                                    {!! __('Confirm Password') !!}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-7">
                                    {!! Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password_confirmation', 'placeholder' => __('Password Confirmation')]) !!}
                                </div>
                            </div>
                            <div class="form-group m-form__group row" style="display: none">
                                <label for="example-text-input" class="col-3 col-form-label">
                                    {!! __('Change Position') !!}
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="col-7">
                                    <div id="map{!! $languageId !!}"
                                         style="height:500px; width:auto;margin-top: 48px"></div>
                                </div>
                            </div>
                            @include('admin.common.upload-image',['image_name'=> 'imageUrl','new_image' =>'', 'current_image'=>url($admin['image']), 'title'=>'Profile Image', 'image_size'=>'Recommended size 360 x 300','image_number'=>1])
                        </div>


                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions">
                                <div class="row">
                                    <input type="hidden" value="PUT" name="_method">
                                    <input type="hidden" value="{{$admin['id']}}" name="id">
                                    <div class="col-4"></div>
                                    <div class="col-7">
                                        <button type="submit" class="btn btn-accent m-btn m-btn--air m-btn--custom">
                                            Save changes
                                        </button>
                                        &nbsp;&nbsp;
                                    </div>
                                </div>
                            </div>
                        </div>


                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
