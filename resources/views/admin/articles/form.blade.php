{!! Form::model($article, ['url' => $action, 'method' => 'post', 'role' => 'form', 'class' => 'm-form m-form--fit m-form--label-align-right', 'enctype' => 'multipart/form-data']) !!}
<div class="m-portlet__body">

    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            Title in English
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::text('name->en', old('name->en', $article->name['en']), ['class' => 'form-control', 'placeholder' => 'Title in english', 'required' => 'required','maxlength' => 100]) !!}
        </div>
    </div>

    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            Title in Arabic
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::text('name->ar', old('name->ar', $article->name['ar']), ['class' => 'form-control', 'placeholder' => 'Title in arabic', 'maxlength' => 100,'required' => 'required']) !!}
        </div>
    </div>

    @include('admin.common.upload-image',['name'=>'image','image_name'=> 'image','new_image' => old('image', $article->getOriginal('image')), 'current_image'=>imageUrl(url(empty($article->image)?'assets/front/img/Placeholders/Articles.jpg':$article->image), 555 ,445, 90, 3), 'title'=>'Article image', 'image_size'=>'Recommended size 555 x 445','image_number'=>1])

    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            Author name in English
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::text('author->en', old('author[en]', $article->author['en']), ['class' => 'form-control', 'placeholder' => 'Author name in english', 'required' => 'required','maxlength'=>'32']) !!}
        </div>
    </div>

    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            Author name in Arabic
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::text('author->ar', old('author[ar]', $article->author['ar']), ['class' => 'form-control', 'placeholder' => 'Author name in arabic', 'required' => 'required','maxlength'=>'32']) !!}
        </div>
    </div>


    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            Content in English
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::textarea('content->en', old('content[en]', $article->content['en']), ['class' => 'form-control', 'placeholder' => 'Content in english']) !!}
        </div>
    </div>


    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            Content in Arabic
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::textarea('content->ar', old('content[ar]', $article->content['ar']), ['class' => 'form-control', 'placeholder' => 'Content in arabic']) !!}
        </div>
    </div>

</div>
<div class="m-portlet__foot m-portlet__foot--fit">
    <div class="m-form__actions">
        <div class="row">
            <input type="hidden" value="PUT" name="_method">
            <input type="hidden" name="article_id" value="{!! $articleId !!}">
            <div class="col-4"></div>
            <div class="col-7">
                <button type="submit" class="btn btn-accent m-btn m-btn--air m-btn--custom mx-4">
                    @if($articleId ==0)
                        Add Article
                    @else
                        Update Article
                    @endif
                </button>
                <a href="{!! route('admin.dashboard.articles.index') !!}"
                   class="btn btn-secondary m-btn m-btn--air m-btn--custom">Cancel</a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}

