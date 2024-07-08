<div class="form-group m-form__group row">
    <label for="example-text-input" class="col-3 col-form-label">
        {{$title}}
        <span class="text-danger">*</span>
    </label>
    <div class="col-7">
        <div class="tab-pane fade show  home{!! $image_number !!}" id="home{!! $image_number !!}" role="tabpanel"
             aria-labelledby="home-tab">
            <div class="container-h">
                <button type="button" id="upload-image-{{$image_number}}"
                        class="btn btn-accent m-btn m-btn--air m-btn--custom upload-image upload-trade-image-h">
                    Upload {{$title}}
                </button>
                <input multiple type="file" id="upload_image_input_{{$image_number}}" class="hide upload_image_input" accept="image/*">
            </div>
        </div>

        @if($image_size != '' )
        <p class=" mt-3 text-danger smaller-font-size">{{$image_size}}</p>
        @endif
    </div>
</div>

<div class="form-group m-form__group row" id="uploaded-images-{{$image_number}}">
    <label for="example-text-input" class="col-3 col-form-label">
        Current {{$title}}
    </label>
{{--    <div id="uploaded-images-{{$image_number}}">--}}
        @if(!is_null($current_images))
            @forelse($current_images as $image)
                <div class="col-3 display-selected-file-{{$image_number}}" style="padding-top: 140px">
                    @if (pathinfo($image, PATHINFO_EXTENSION) == 'mp4')
                        <video width="320" height="240" controls muted> <source src="{{ imageUrl($image, 100,100,100,1) }}" type="video/mp4"> Your browser does not support the video tag. </video>
                        <input type="hidden" name="{{$image_name}}[]" class="public_select_image" value="{{$image}}">
                    @else
                        <img style="width:120px;height: 120px; " src="{{ imageUrl($image, 100,100,100,1) }}" class="selected-image img-fluid">
                        <input type="hidden" name="{{$image_name}}[]" class="public_select_image" value="{{$image}}">
                    @endif
                </div>
            @empty
            @endforelse
        @endif
{{--    </div>--}}


</div>


@push('script-page-level')

    <script>
        $('#upload-image-{{$image_number}}').on('click', function () {
            $(this).next().click();
        });

        $('#upload_image_input_{{$image_number}}').on('change', function () {
            // console.log("On change function is cladded");
            var fileData = $(this).prop("files");

            if ({{$number_of_images}} > 0){
                let totalCount = $('.display-selected-file-{{$image_number}}').length + fileData.length;
                if (totalCount > {{$number_of_images}}){
                    toastr.error('{{ __('Error') }}','{{__('You can only upload')}}'+' '+'{{$number_of_images}}'+' '+'{{__('images')}}');
                    return false;
                }
            }
            if (fileData[0] !== undefined) {
                var formData = new FormData();
                Object.values(fileData).map((value, index)=>{
                    formData.append("images[]", fileData[index]);
                });
                var url = window.Laravel.baseUrl + 'upload-multi-image';
                if (url.length > 0) {
                    $.ajax({
                        url: url,
                        type: 'post',
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': window.Laravel.csrfToken
                        }
                    })
                        .done(function (res) {
                            let images = res.data.collection;
                            console.log("these are the images", images);
                            images.map((image)=>{
                                let html
                                console.log('is it a video',image.file_name.includes('.mp4'));
                                if (image.file_name.includes('.mp4')){
                                    html = `
                                      <div class="col-3 display-selected-file-{{$image_number}}" style="padding-top: 140px">
                                    <video width="320" height="240" controls muted> <source src="`+ window.Laravel.base + image.file_name +`" type="video/mp4"> Your browser does not support the video tag. </video>
                                    <input type="hidden" name="{{$image_name}}[]" class="public_select_image" value="`+ image.file_name +`">
                                    </div>
                                    `
                                }else{
                                    html = `
                                             <div class="col-3 display-selected-file-{{$image_number}}" style="padding-top: 140px">
                                            <img style="width:120px;height: 120px; " src="`+ imageUrl(image.file_name, 120,120,100,1) +`" class="selected-image img-fluid">
                                            <input type="hidden" name="{{$image_name}}[]" class="public_select_image" value="`+ image.file_name +`">
                                            </div>
                                    `;
                                }
                                $("#uploaded-images-{{$image_number}}").append(html);
                            });
                            toastr.success(res.message);
                            $('#upload_image_input_{{$image_number}}').val('');

                        })
                        .fail(function (res) {
                            alert('Something went wrong, please try later.');
                            $('#upload_image_input_{{$image_number}}').val('');
                        });
                }
            }
        });

        function checkURL(url) {
            return(url.match(/\.(jpeg|jpg|gif|png)$/) != null);
        }
    </script>

@endpush
