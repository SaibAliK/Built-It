{!! Form::model($area, ['url' => $action, 'method' => 'post',  'class' => 'm-form m-form--fit m-form--label-align-right', 'enctype' => 'multipart/form-data']) !!}
<div class="m-portlet__body">
    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('Name in English') !!}
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::text('name->en',old('name->en', $area->name['en']), ['class' => 'form-control', 'placeholder' => 'Title', 'required'=>'required']) !!}
        </div>
    </div>
    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('Name in Arabic') !!}
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::text('name->ar',old('name->ar', $area->name['ar']), ['class' => 'form-control', 'placeholder' => 'Title', 'required'=>'required']) !!}
        </div>
    </div>
    <div class="m-form__group row" style="margin-top:40px">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('Select Location') !!}
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            <div class="d-flex align-items-center justify-contnet-between mb-3">
                {!! Form::text('address', old('address'), ['class' => 'form-control', 'placeholder' => 'Search map', 'id'=>'address']) !!}
                <div class="ml-2">
                    <button type="button" onclick="recreateMap()" class="btn btn-accent m-btn m-btn--air m-btn--custom">
                        Reset
                    </button>
                </div>
                @if($area->id > 0)
                    <div class="ml-2">
                        <button type="button" onclick="editMap()" class="btn btn-accent m-btn m-btn--air m-btn--custom">
                            Edit
                        </button>
                    </div>
                @endif
            </div>
            <div id="map" style="height:400px; width:100%; margin-top:5px"></div>
        </div>
    </div>

    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            Selected Polygon lat/long
        </label>
        <div class="col-7">
            <textarea class="form-control" disabled name="vertices" id="vertices" cols="50"
                      rows="10">{{old('polygon',$area->polygon) ?? ''}}</textarea>
        </div>
    </div>

    <input type="hidden" name="parent_id" value="{{$area->parent_id ? $city_id : $parent}}">
    <input type="hidden" name="city_id" value="{{$city_id}}">
    <input type="text" name="latitude" id="latitude" value="{{$area->latitude}}" hidden>
    <input type="text" name="longitude" id="longitude" value="{{$area->longitude}}" hidden>
    <input type="text" name="polygon" id="polygon" value="{{old('polygon',$area->polygon)}}" hidden>
</div>
<div class="m-portlet__foot m-portlet__foot--fit">
    <div class="m-form__actions">
        <div class="row">
            <input type="hidden" value="PUT" name="_method">
            <input type="hidden" name="language_id" value="{!! $languageId !!}">
            <div class="col-4"></div>
            <div class="col-7">
                <button type="submit" class="btn btn-accent m-btn m-btn--air m-btn--custom mx-3">
                    @if($categoryId == 0)
                        Add Delivery Areas
                    @else
                        Update Delivery Areas
                    @endif
                </button>
                <a href="{!! route('admin.dashboard.cities.index') !!}"
                   class="btn btn-secondary m-btn m-btn--air m-btn--custom">{!! __('Cancel') !!}</a>
            </div>
        </div>
    </div>
</div>

{!! Form::close() !!}

<script>
    $(document).ready(function () {
        $('.select_image').hide();
    });
</script>
