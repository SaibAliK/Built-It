{!! Form::model($package, ['url' => $action, 'method' => 'post', 'role' => 'form', 'class' => 'm-form m-form--fit m-form--label-align-right','id'=>'subscription_form', 'enctype' => 'multipart/form-data']) !!}
<div class="m-portlet__body">
    <div class="form-group m-form__group row">
        <div class="col-lg-3 col-md-3">
            <label for="example-text-input" class="col-form-label">
                Title English
                <span class="text-danger">*</span>
            </label>
        </div>
        <div class="col-lg-7 col-md-7">
            {!! Form::text('name[en]', old('name[en]', $package->name['en']), ['class' => 'form-control', 'placeholder' => 'Title', 'required' => 'required']) !!}
        </div>
    </div>

    <div class="form-group m-form__group row">
        <div class="col-lg-3 col-md-3">
            <label for="example-text-input" class="col-form-label">
                Title Arabic
                <span class="text-danger">*</span>
            </label>
        </div>
        <div class="col-lg-7 col-md-7">
            {!! Form::text('name[ar]', old('name[ar]', $package->name['ar']), ['class' => 'form-control', 'placeholder' => 'Title', 'required' => 'required']) !!}
        </div>
    </div>

    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-lg-3 col-md-3">
            Is free
            <span class="text-danger">*</span>
        </label>
        <div class="col-9 d-flex">
            <label class="check text-capitalize black gothic-normel option-g mr-3 mb-0 p-c d-flex align-items-center">
                <input type="radio" {!! (old('is_free',$package->is_free) == '1' ) ? 'checked' : '' !!} name="is_free"
                       required
                       value="1">
                <span class="log checkmark ml-1"></span>Yes
            </label>

            <label class="check mr-3 text-capitalize black gothic-normel option-g mb-0 p-c d-flex align-items-center">
                <input type="radio" {!! (old('is_free',$package->is_free) == '0' ) ? 'checked' : '' !!} name="is_free"
                       required
                       @if($id =="0") checked @endif  value="0">
                <span class="log checkmark ml-1"></span>No
            </label>

        </div>
    </div>

    <div class="form-group m-form__group row" id="Price_input">
        <div class="col-lg-3 col-md-3">
            <label for="example-text-input" class="col-form-label">
                Price
                <span class="text-danger">*</span>
            </label>
        </div>
        <div class="col-lg-7 col-md-7">
            <input type="number" name="price" class="form-control" value="{{old('price',$package->price)}}"
                   id="price_field" placeholder="240" min="1" max="999999">
            {{--            {!! Form::number('price', old('price',$package->price), ['class' => 'form-control','type'=>'number', 'placeholder' => '240', 'max'=>999999, 'min'=>'1']) !!}--}}
        </div>
    </div>

    <div class="form-group m-form__group row">
        <div class="col-lg-3 col-md-3">
            <label for="example-text-input" class="col-form-label">
                {!! __('Duration') !!}
                <span class="text-danger">*</span>
            </label>
        </div>
        <div class="col-lg-7 col-md-7">
            <div class="subscripton-number mb-2">
                @if(old('duration_type', $package->duration_type) == 'years' )
                    {!! Form::number('duration', old('duration',$package->duration), ['class' => 'form-control duration_number','type'=>'number', 'id'=>'duration_number','max'=>9,'placeholder' => 'Duration', 'required' => 'required', 'min'=>'1']) !!}
                @else
                    {!! Form::number('duration', old('duration',$package->duration), ['class' => 'form-control duration_number','type'=>'number', 'id'=>'duration_number','max'=>99,'placeholder' => 'Duration', 'required' => 'required', 'min'=>'1']) !!}
                @endif
            </div>
            <div class="subscripton-checkbox">
                <input type="radio" class="duration_day" id="duration_day"
                       {{old('duration_type',$package->duration_type) == 'days'? 'checked':''}} name="duration_type"
                       value="days"> {!! __('Days') !!}&nbsp;&nbsp;&nbsp;
                <input type="radio" class="duration_month" id="duration_month"
                       {{old('duration_type',$package->duration_type) == 'months'? 'checked':''}}  name="duration_type"
                       value="months"> {!! __('Months') !!}&nbsp;&nbsp;&nbsp;
                <input type="radio" class="duration_year" id="duration_year"
                       {{old('duration_type',$package->duration_type) == 'years'? 'checked':''}} name="duration_type"
                       value="years"> {!! __('Years') !!}
            </div>
            <!-- </div> -->
        </div>
    </div>


    <input type="hidden" name="subscription_type" value="featured">

    <div class="form-group m-form__group row">
        <div class="col-lg-3 col-md-3">
            <label for="example-text-input" class="col-form-label">
                Description English
                <span class="text-danger">*</span>
            </label>
        </div>
        <div class="col-lg-7 col-md-7">
            {!! Form::textarea('description[en]', old('description[en]', $package->description['en']), ['class' => 'form-control', 'placeholder' => 'Description']) !!}
        </div>
    </div>

    <div class="form-group m-form__group row">
        <div class="col-lg-3 col-md-3">
            <label for="example-text-input" class="col-form-label">
                Description Arabic
                <span class="text-danger">*</span>
            </label>
        </div>
        <div class="col-lg-7 col-md-7">
            {!! Form::textarea('description[ar]', old('description[ar]', $package->description['ar']), ['class' => 'form-control', 'placeholder' => 'Description']) !!}
        </div>
    </div>


</div>
<div class="m-portlet__foot m-portlet__foot--fit">
    <div class="m-form__actions">
        <div class="row">
            <input type="hidden" value="PUT" name="_method">
            <input type="hidden" name="package_id" value="{!! $packageId !!}">
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-accent m-btn m-btn--air m-btn--custom mx-2">
                    @if($packageId ==0)
                        Add Package
                    @else
                        Update Package
                    @endif
                </button>
                <a href="{!! route('admin.dashboard.subscriptions.index') !!}"
                   class="btn btn-secondary m-btn m-btn--air m-btn--custom mx-2">Cancel</a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
