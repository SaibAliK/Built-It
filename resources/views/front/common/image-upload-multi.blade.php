<div class="uploder-ss-m">
    <div class="input-style1">
        <label class="d-block login-labels">
            @if($type == 'supplier')
                {{__('Trade License')}}
            @else
                {{__('Driving License')}}
            @endif
            @if($isRequired)<span
                class="text-danger">*</span>@endif</label>
    </div>
    <div class="upload-sec d-flex align-items-center flex-wrap">
        <!-- image upload -->
        <div class="qust-filed mr-2">
            <div class="form-control py-2 d-flex align-items-center justify-content-center">
                <input multiple type="file" id="choose-file-{{$imageNumber}}" class="input-file d-none">
                <label for="choose-file-{{$imageNumber}}"
                       class="btn-tertiary js-labelFile d-flex align-items-center flex-column">
                    <i class="icon fa fa-plus-circle plus-icon"></i>
                    @if($type == 'supplier')
                        <span class="js-fileName heading Poppins-Regular">{{__('Trade License')}}</span>
                    @else
                        <span class="js-fileName heading Poppins-Regular">{{__('Driving License')}}</span>
                    @endif
                </label>
            </div>
        </div>

        <div id="multiple" class="uploads d-flex align-items-center justify-content-center">
            @if(is_array($displayImageSrc) && count($displayImageSrc) > 0)
                @forelse($displayImageSrc as $key => $src)
                    @if(file_exists($src))
                        <div class="more-view-image">
                            <div class="uploads d-flex align-items-center justify-content-center">
                                <img src="{{imageUrl($src,145, 115, 95, 1)}}" class="img-fluid img" alt="">
                            </div>
                            <a href="javascript:void(0)"
                               class="placeholder-remove d-flex align-items-center justify-content-center delete-image-js"
                               data-id=`+ image.file_name +`>
                                <svg xmlns="http://www.w3.org/2000/svg" width="11.001" height="11.003"
                                     viewBox="0 0 11.001 11.003">
                                    <path id="Forma_1" data-name="Forma 1"
                                          d="M1028.345,545.163l-3.333,3.335,3.333,3.334a1.27,1.27,0,1,1-1.795,1.8l-3.334-3.335-3.334,3.335a1.27,1.27,0,0,1-1.795-1.8l3.333-3.334-3.333-3.335a1.269,1.269,0,0,1,1.795-1.8l3.334,3.335,3.334-3.335a1.269,1.269,0,0,1,1.8,1.8Z"
                                          transform="translate(-1017.715 -542.996)" fill="#fff"></path>
                                </svg>
                            </a>
                            <input type="hidden" class="user-image selected-image" name="{{$inputName}}[]"
                                   value="{{$src}}" required>
                        </div>
                    @endif
                @empty

                @endforelse
            @else
                <input type="text" hidden class="user-image selected-image" name="{{$inputName}}"
                       @if($isRequired) required @endif>
            @endif
        </div>

        <!-- image upload -->
    </div>
    {{--    @include('front.common.alert', ['input' => $inputName.'.*'])--}}
</div>



@push('scripts')
    <script>
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

                Object.values(fileData).map((value, index) => {
                    formData.append("images[]", fileData[index]);
                });
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
                            let images = res.data.collection;
                            // if ($('.delete-image-js').length == 0){
                            //     $("#multiple").empty();
                            // }
                            images.map((image) => {
                                let html = `    <div class="more-view-image">
            <div class="uploads d-flex align-items-center justify-content-center">

                               <img src="` + imageUrl(image.file_name, 145, 115, 95, 1) + `" class="img-fluid img" alt="">

                                </div>
                                <a href="javascript:void(0)" class="placeholder-remove d-flex align-items-center justify-content-center delete-image-js" data-id=` + image.file_name + `>
                <svg xmlns="http://www.w3.org/2000/svg" width="11.001" height="11.003" viewBox="0 0 11.001 11.003">
                    <path id="Forma_1" data-name="Forma 1" d="M1028.345,545.163l-3.333,3.335,3.333,3.334a1.27,1.27,0,1,1-1.795,1.8l-3.334-3.335-3.334,3.335a1.27,1.27,0,0,1-1.795-1.8l3.333-3.334-3.333-3.335a1.269,1.269,0,0,1,1.795-1.8l3.334,3.335,3.334-3.335a1.269,1.269,0,0,1,1.8,1.8Z" transform="translate(-1017.715 -542.996)" fill="#fff"></path>
                </svg>

            </a>
            <input type="hidden" class="user-image selected-image" name="{{$inputName}}[]" value="` + image.file_name + `" required>
        </div>`;

                                $("#multiple").append(html);

                            });
                            toastr.success(res.message);

                        }
                    }).fail(function (res) {
                        alert('{{ __('Something went wrong, please try later.') }}');
                    });
                }
            }

        });
        $(document).on('click', '.delete-image-js', function () {
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
                    }
                }).fail(function (res) {
                    toastr.error("{{ __('Something went wrong, please refresh.') }}");
                });
                @else
                parentDive.remove();
                @endif
            }
        });
    </script>
@endpush
