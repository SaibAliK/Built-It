{!! Form::model($selected_admin, ['url' => $action , 'method' => 'POST', 'role' => 'form', 'class' => 'm-form m-form--fit m-form--label-align-right','id'=>'admin_form', 'enctype' => 'multipart/form-data']) !!}

<div class="m-portlet__body">

    <div class="form-group m-form__group row">

        <label for="example-text-input" class="col-3 col-form-label">

            {!! __('Full Name') !!}

            <span class="text-danger">*</span>

        </label>

        <div class="col-7">

            {!! Form::text('name', old('name'), ['class' => 'form-control', 'id' => 'full_name', 'placeholder' => __('Full Name'), 'required' => 'required']) !!}

        </div>

    </div>

    <div class="form-group m-form__group row">

        <label for="example-text-input" class="col-3 col-form-label">

            {!! __('Email') !!}

            <span class="text-danger">*</span>

        </label>

        <div class="col-7">

            {!! Form::email('email', old('email'), ['class' => 'form-control', 'id' => 'user_name', 'placeholder' => __('Username'), 'required' => 'required']) !!}

        </div>

    </div>

    <div class="form-group m-form__group row">

        <label for="example-text-input" class="col-3 col-form-label">

            {!! __('Password') !!}

            <span class="text-danger">*</span>

        </label>

        <div class="col-7">

            @if($selected_admin->id > 0)

                {!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'placeholder' => __('Password')]) !!}

            @else

                {!! Form::password('password', ['class' => 'form-control', 'id' => 'password', 'placeholder' => __('Password'), 'required' => 'required']) !!}

            @endif

        </div>

    </div>


    <div class="form-group m-form__group row">

        <label for="example-text-input" class="col-3 col-form-label">

            {!! __('Confirm Password') !!}

            <span class="text-danger">*</span>

        </label>

        <div class="col-7">

            @if($selected_admin->id > 0)

                {!! Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password_confirmation', 'placeholder' => __('Password Confirmation')]) !!}

            @else

                {!! Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password_confirmation', 'placeholder' => __('Password Confirmation'), 'required' => 'required']) !!}

            @endif

        </div>

    </div>


    @include('admin.common.upload-image',['image_name'=> 'imageUrl','new_image' =>old('imageUrl',$selected_admin->image), 'current_image'=>imageUrl(old('imageUrl',$selected_admin->image_url), 100,100,100,1), 'title'=>'Profile Image', 'image_size'=>'Recommended size 360 x 300','image_number'=>1])


    <div class="form-group m-form__group row">

        <label for="example-text-input" class="col-3 col-form-label">

            {!! __('Active') !!}

            <span class="text-danger">*</span>

        </label>

        <div class="col-7">

            {!! Form::select('is_active', ['0' => 'In-Active', '1' => 'Active'], old('is_active'), ['class' => 'form-control', 'id' => 'is_active', 'required' => 'required']) !!}

        </div>

    </div>

</div>

<div class="m-portlet__foot m-portlet__foot--fit">

    <div class="m-form__actions">

        <div class="row">

            @if ($selected_admin->id > 0)

                <input type="hidden" value="PUT" name="_method">

            @endif

            <div class="col-4"></div>

            <div class="col-7">

                <button type="submit" class="btn btn-accent m-btn m-btn--air m-btn--custom">

                    Save changes

                </button>

                <a href="{!! route('admin.dashboard.administrators.index') !!}"
                   class="btn btn-secondary m-btn m-btn--air m-btn--custom">{!! __('Cancel') !!}</a>
            </div>

        </div>

    </div>

</div>


{!! Form::close() !!}



