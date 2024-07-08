{!! Form::model($category, ['url' => $action, 'method' => 'post', 'id' => 'sub_cate_form',  'class' => 'm-form m-form--fit m-form--label-align-right', 'enctype' => 'multipart/form-data']) !!}
<div class="m-portlet__body">
    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            Name in English
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::text('name[en]',old('name[en]', $category->name['en']), ['class' => 'form-control', 'placeholder' => 'Title', 'required'=>'required']) !!}
        </div>
    </div>
    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            Name in Arabic
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::text('name[ar]',old('name[ar]', $category->name['ar']), ['class' => 'form-control', 'placeholder' => 'Title', 'required'=>'required']) !!}
        </div>
    </div>
    <input type="hidden" name="parent_id" value="{{$category->parent_id ? $category->parent_id : $parent}}">

    @include('admin.common.upload-image',['name'=>'image','image_name'=> 'image','new_image' => old('image', $category->getOriginal('image')), 'current_image'=>imageUrl(url(empty($category->image)?'assets/front/img/Placeholders/category.jpg':$category->image), 165 ,165, 90, 3), 'title'=>'Sub Category Image', 'image_size'=>'Recommended size 555 x 400','image_number'=>1])

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
                        Add Sub Category
                    @else
                        Update Sub Category
                    @endif
                </button>
                <a href="{!! route('admin.dashboard.categories.index') !!}"
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
