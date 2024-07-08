{!! Form::model($page, ['url' => $action, 'method' => 'post', 'role' => 'form', 'class' => 'm-form m-form--fit m-form--label-align-right', 'enctype' => 'multipart/form-data']) !!}
<div class="m-portlet__body">
    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            Title English
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::text('name[en]', old('name[en]', $page->name['en']), ['class' => 'form-control', 'placeholder' => 'Title', 'required' => 'required']) !!}
        </div>
    </div>

    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            Title Arabic
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::text('name[ar]', old('name[ar]', $page->name['ar']), ['class' => 'form-control', 'placeholder' => 'Title', 'required' => 'required']) !!}
        </div>
    </div>


    @if($page->slug == config('settings.about_us') || $page->slug == config('settings.mission_and_vision'))
        @include('admin.common.upload-image',['image_name'=> 'image','new_image' => old('image', $page->image), 'current_image'=>imageUrl(old('image',$page->image_url), 100,100,90,1), 'title'=>'File', 'image_size'=>'Recommended size 1140 x 400','image_number'=>1])
    @endif

    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            Content English
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::textarea('content[en]', old('content[en]',$page->content['en']), ['class' => 'form-control', 'placeholder' => 'Content']) !!}
        </div>
    </div>

    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            Content Arabic
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::textarea('content[ar]', old('content[ar]', $page->content['ar']), ['class' => 'form-control', 'placeholder' => 'Content']) !!}
        </div>
    </div>

</div>
<div class="m-portlet__foot m-portlet__foot--fit">
    <div class="m-form__actions">
        <div class="row">
            <input type="hidden" value="PUT" name="_method">
{{--            <input type="hidden" name="language_id" value="{!! $languageId !!}">--}}
            <input type="hidden" name="page_id" value="{!! $pageId !!}">
            <div class="col-4"></div>
            <div class="col-7">
                <button type="submit" class="btn btn-accent m-btn m-btn--air m-btn--custom mx-4">
                    @if($pageId ==0)
                        Add Info Page
                        @else
                        Update Info Page
                        @endif
                </button>
                <a href="{!! route('admin.dashboard.pages.index') !!}" class="btn btn-secondary m-btn m-btn--air m-btn--custom">{!! __('Cancel') !!}</a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}

