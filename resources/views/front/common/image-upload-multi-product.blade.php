<div class="upload-sec d-flex align-items-center flex-wrap custom-upload">

    <div class="qust-filed d-flex">
        <!-- image upload -->
        <div class="form-control py-2 d-flex align-items-center justify-content-center mr-2" id="push_loader">
            <div class="" id="previous_html">
                <input multiple type="file" id="choose-file-{{$imageNumber}}" name="product_images" class="input-file d-none">
                <label for="choose-file-{{$imageNumber}}"
                       class="btn-tertiary js-labelFile d-flex align-items-center flex-column">
                    <i class="icon fa fa-plus-circle plus-icon mt-0"></i>
                    <span class="js-fileName heading Poppins-Regular mt-1">{{__('Upload Media')}}</span>
                </label>
            </div>
        </div>
    </div>
    <div id="multiple" class=" row">
        @if(!empty($displayImageSrc))
            @forelse($displayImageSrc as $key => $src)
{{--                {{dd($src->file_type,$src['file_type'])}}--}}
                @if($src['file_type'] == 'video')
                    <div class="more-view-image mt-1 ">
                        <div class="uploads d-flex align-items-center justify-content-center">
                            @if(!empty($src['file_path']))
                                <video width="145" height="115" controls muted>
                                    <source src="{{env('APP_URL') . $src->file_path}}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            @endif
                        </div>
                        @if($src['file_default'] == 1 || $src['file_default'] == '1'|| $src['file_default'] == true)
                            <i class="fas fa-check d-flex align-items-center justify-content-center placeholder-remove-2"></i>
                        @else
                            <a href="javascript:void(0)" class="placeholder-remove d-flex align-items-center justify-content-center delete-image-js"  data-id="{{$src->file_path ?? ''}}">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </a>
                        @endif
                        <input type="hidden" class="user-image selected-image"
                               name="{{$inputName}}[{{$key+15}}][file_path]"
                               value="{{$src->file_path ?? ''}}" required>

                        <input type="hidden" class="user-image selected-image"
                               name="{{$inputName}}[{{$key+15}}][file_type]"
                               value="{{$src->file_type ?? ''}}" required>

                        @if($src['file_default'] == 1 || $src['file_default'] == '1'|| $src['file_default'] == true)
                            <input type="hidden" class="user-image selected-image default-img"
                                   name="{{$inputName}}[{{$key+15}}][file_default]"
                                   value="{{empty(old('file_default')) ? (!empty($src->file_default) ? $src->file_default : old('file_default')) :old('file_default')}}">
                        @else
                            <input type="hidden" class="user-image selected-image default-img"
                                   name="{{$inputName}}[{{$key+15}}][file_default]"
                                   value="0">
                        @endif
                    </div>
                @else
                    <div class="more-view-image mt-1">
                        <div class="uploads d-flex align-items-center justify-content-center">
                            @if(!empty($src->file_path))
                                <img src="{{imageUrl($src->file_path,145, 115, 95, 1)}}" class="img-fluid img"
                                     alt="">
                            @endif
                        </div>
                        @if($src['file_default'] == 1 || $src['file_default'] == '1'|| $src['file_default'] == true)
                            <i class="fas fa-check d-flex align-items-center justify-content-center placeholder-remove-2"></i>
                        @else
                            <a href="javascript:void(0)" class="placeholder-remove d-flex align-items-center justify-content-center delete-image-js"  data-id="{{$src->file_path ?? ''}}">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </a>
                        @endif

                        <input type="hidden" class="user-image selected-image"
                               name="{{$inputName}}[{{$key+15}}][file_path]"
                               value="{{$src->file_path ?? ''}}" required>
                        <input type="hidden" class="user-image selected-image"
                               name="{{$inputName}}[{{$key+15}}][file_type]"
                               value="{{$src->file_type ?? ''}}" required>
                        <input type="hidden" class="user-image selected-image default-img"
                               name="{{$inputName}}[{{$key+15}}][file_default]"
                               value="{{$src->file_default ?? 0 }}" required>
                    </div>
                @endif
            @empty
            @endforelse
        @endif
    </div>

    @if($required)
        <input type="text" name="images" class="" required id="initial_images" style="display: none">
    @endif
{{--    @include('front.common.alert', ['input' => $inputName.'.*'])--}}
</div>


@push('scripts')
    <script>
        var disSrc = <?php echo json_encode($displayImageSrc); ?>;
        $("#initial_images").val(disSrc);
        let images = [];
        let indexCount = 0;
        $(document).on('change', '#choose-file-' + '{{ $imageNumber }}', function () {
            if ({{$numberOfImages}} > 0) {
                let totalCount = $('.delete-image-js').length + $(this).prop("files").length;
                if (totalCount > {{$numberOfImages}}) {
                    toastr.error('{{ __('Error') }}', '{{__('You can only upload')}}' + ' ' + '{{$numberOfImages}}' + ' ' + '{{__('images')}}');
                    return false;
                }
            }
            let url = window.Laravel.apiUrl + 'upload-multi-image';
            let fileData = $(this).prop("files");
            if (fileData[0] !== undefined) {
                let formData = new FormData();
                if (!{{$allowVideo}}) {
                    let filteredFilesData = Object.values(fileData).filter((value) => {
                        if (value.type.includes('video')) {
                            return true;
                        }
                        return false;
                    });
                    if (filteredFilesData.length > 0) {
                        toastr.error('{{ __('Error') }}', '{{__('You can only upload images')}}');
                        return false;
                    }
                }
                let html_loader = `<div class="form-control py-2 d-flex align-items-center justify-content-center" id="loader_div"><div class="gif-loader-imageee"><img src="{{asset('/assets/front/img/ajax-loader.gif')}}" alt="image-loader"></div></div>`;
                $("#loader_div").remove();
                $("#previous_html").addClass('preview_html_remove');
                $("#push_loader").append(html_loader);

                Object.values(fileData).map((value, index) => {
                    formData.append("images[]", fileData[index]);
                });

                $(".add_btn").prop('disabled', true);

                if (url.length > 0) {
                    jQuery.ajax({
                        url: url,
                        type: 'post',
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': window.Laravel.csrfToken
                        },
                    }).done(function (res) {
                        if (res.success == false) {
                            toastr.error(res.message);
                        } else {
                            $("#loader_div").addClass('loader_html_remove');
                            $("#previous_html").removeClass('preview_html_remove');

                            if (disSrc.length != 0) {
                                // images = [];
                                images.push(...res.data.collection);
                            } else {
                                images.push(...res.data.collection);
                            }

                            let html = '';

                            images.map((image, index) => {
                                if (index >= indexCount) {
                                    if (image.file_name.includes('.mp4')) {
                                        html += `<div class="more-view-image mt-1">
            <div class="uploads d-flex align-items-center justify-content-center">
                                 <video width="320" height="240" controls muted> <source src="` + window.Laravel.base + image.file_name + `" type="video/mp4"> Your browser does not support the video tag. </video>
                                </div>
                                <a href="javascript:void(0)" class="placeholder-remove d-flex align-items-center justify-content-center delete-image-js" data-id=` + image.file_name + `>
               <i class="fa fa-times" aria-hidden="true"></i>
            </a>
            <input type="hidden" class="user-image selected-image" name="{{$inputName}}[` + index + `][file_path]" value="` + image.file_name + `" required>
            <input type="hidden" class="user-image selected-image" name="{{$inputName}}[` + index + `][file_type]" value="` + image.type + `">
            <input type="hidden" class="user-image selected-image default-img" name="{{$inputName}}[` + index + `][file_default]" value="0">
        </div>`;
                                    } else {
                                        html += `<div class="more-view-image mt-1">
            <div class="uploads d-flex align-items-center justify-content-center">
                               <img src="` + imageUrl(image.file_name, 145, 115, 95, 1) + `" class="img-fluid img" alt="">
                                </div>
                                <a href="javascript:void(0)" class="placeholder-remove d-flex align-items-center justify-content-center delete-image-js" data-id=` + image.file_name + `>
                             <i class="fa fa-times" aria-hidden="true"></i>
            </a>
            <input type="hidden" class="user-image selected-image" name="{{$inputName}}[` + index + `][file_path]" value="` + image.file_name + `" required>
            <input type="hidden" class="user-image selected-image" name="{{$inputName}}[` + index + `][file_type]" value="` + image.type + `">
            <input type="hidden" class="user-image selected-image default-img" name="{{$inputName}}[` + index + `][file_default]" value="0">
        </div>`;
                                    }
                                    indexCount++;
                                }
                            });
                            $("#multiple").append(html);

                            $("#initial_images").val(html);
                            $(".add_btn").removeAttr("disabled");
                            //remove uploaded images from img
                            $('#choose-file-' + '{{ $imageNumber }}').val(null);

                            toastr.success(res.message);
                            let image_count = $('.more-view-image').length;

                            let image_def_boolean = false;
                            $('.default-img').each(function (index, item) {
                                if (item.value == 1 || item.value == '1' || item.value == true) {
                                    image_def_boolean = true;
                                }
                            });

                            if (image_count >= 1 && !image_def_boolean) {
                                let selected_el = $("[name='product_images[0][file_default]']");
                                selected_el.val(1);
                                selected_el.closest('.more-view-image').find('.uploads').click();
                            }

                        }
                    }).fail(function (res) {
                        alert('{{ __('Something went wrong, please try later.') }}');
                    });
                }
            }

        });

        $(document).on('click', '.delete-image-js', function () {
            if (disSrc.length !== 0) {
                images = [];
            }
            let imagePath = $(this).data("id");
            let parentDive = $(this).parent();
            if (imagePath.length > 0) {
                @if($allowDeleteApi)
                let url = window.Laravel.apiUrl + 'remove-image';
                $.ajax({
                    url: url,
                    type: 'post',
                    data: 'image= ' + imagePath,
                    headers: {
                        'X-CSRF-TOKEN': window.Laravel.csrfToken
                    },
                }).done(function (res) {
                    if (res.success == false) {
                        toastr.error(res.message);
                    } else {
                        toastr.success(res.message);
                        parentDive.remove();
                        if (images.length > 0) {
                            images.forEach(function (item, arrayItem) {
                                if (item.file_name === imagePath) {
                                    images.splice(arrayItem, 1);
                                }
                            });
                        }
                    }
                }).fail(function (res) {
                    toastr.error("{{ __('Something went wrong, please refresh.') }}");
                });
                @else
                parentDive.remove();
                @endif
            }
        });

        $(document).on('click', '.uploads', function () {
            // make all default values to zero
            $('.default-img').val(0);
            let newDefaultImgInput = $(this).closest('.more-view-image').find('.default-img');
            newDefaultImgInput.val(1);

            // remove and add border-default class
            let htm = '<i class="fas fa-check d-flex align-items-center justify-content-center placeholder-remove-2"></i>';
            $(".placeholder-remove-2").remove();
            $(this).find('img').parent().after(htm);

            // remove and add delete icon
            $('.delete-image-js').addClass('placeholder-remove');
            $('.placeholder-remove').removeClass('remove_cross_icon');
            $(this).closest('.more-view-image').find('.placeholder-remove').addClass('remove_cross_icon');
        });

    </script>
@endpush
