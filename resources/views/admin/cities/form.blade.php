{!! Form::model($city, ['url' => $action, 'method' => 'post',  'class' => 'm-form m-form--fit m-form--label-align-right', 'enctype' => 'multipart/form-data']) !!}

<div class="m-portlet__body">
    <div class="form-group m-form__group row pb-3">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('Name in English') !!}
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::text('name->en',old('name->en',$city->name['en']), ['class' => 'form-control', 'placeholder' => 'Title', 'required'=>'required']) !!}
        </div>
    </div>
    <input type="hidden" name="parent_id" value="0">

    <div class="form-group m-form__group row pb-3">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('Name in Arabic') !!}
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::text('name->ar',old('name->ar', $city->name['ar']), ['class' => 'form-control', 'placeholder' => 'Title', 'required'=>'required']) !!}
        </div>
    </div>

</div>
<div class="m-portlet__foot m-portlet__foot--fit">
    <div class="m-form__actions">
        <div class="row">

            <input type="hidden" value="PUT" name="_method">
            <input type="hidden" name="language_id" value="{!! $languageId !!}">
            <div class="col-4"></div>
            <div class="col-7">
                @if($cityId == 0)
                    <button type="submit" class="btn btn-accent m-btn m-btn--air m-btn--custom mx-3">
                        Add City
                    </button>
                @else
                    <button type="submit" class="btn btn-accent m-btn m-btn--air m-btn--custom mx-3">
                        Update City
                    </button>
                @endif
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

    $(document).ready(function () {
        $(document).on('change', '.btn-file :file', function () {
            var input = $(this),
                label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [label]);
        });

        $('.btn-file :file').on('fileselect', function (event, label) {

            var input = $(this).parents('.input-group').find(':text'),
                log = label;

            if (input.length) {
                input.val(log);
            } else {
                if (log) alert(log);
            }

        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#img-upload').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imgInp").change(function () {
            readURL(this);
        });
    });
</script>
