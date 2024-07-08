<div class="uploder-ss-m">
    <div class="input-style">
        <label class="d-block input-label">{{ __($imageTitle) }} @if($isRequired)<span class="text-danger">*</span>@endif</label>
    </div>
    <div class="upload-sec d-flex align-items-center flex-wrap">
        <!-- image upload -->
        <div class="qust-filed mr-2">
            <div class="form-control py-2 d-flex align-items-center justify-content-center">
                <input type="file" id="choose-file-{{$imageNumber}}" class="input-file d-none">
                <label for="choose-file-{{$imageNumber}}" class="btn-tertiary js-labelFile d-flex align-items-center flex-column">
                    <i class="icon fa fa-plus-circle plus-icon"></i>
                    <span class="js-fileName heading Poppins-Regular">{{__('Display Image')}}</span>
                </label>
            </div>
        </div>
        <div class="more-view-image @if(empty($displayImageSrc)) d-none @endif" id="selected-div-{{$imageNumber}}">
            <div class="uploads d-flex align-items-center justify-content-center">
                @if(!file_exists($displayImageSrc))
                    <img src="{{$displayImageSrc}}" class="img-fluid img" alt="" id="selected-image-{{$imageNumber}}">
                @endif
            </div>
            @if($allowDelete)
            <a href="javascript:void(0)" class="placeholder-remove d-flex align-items-center justify-content-center" id="delete-icon-{{$imageNumber}}">
                <svg xmlns="http://www.w3.org/2000/svg" width="11.001" height="11.003" viewBox="0 0 11.001 11.003">
                    <path id="Forma_1" data-name="Forma 1" d="M1028.345,545.163l-3.333,3.335,3.333,3.334a1.27,1.27,0,1,1-1.795,1.8l-3.334-3.335-3.334,3.335a1.27,1.27,0,0,1-1.795-1.8l3.333-3.334-3.333-3.335a1.269,1.269,0,0,1,1.795-1.8l3.334,3.335,3.334-3.335a1.269,1.269,0,0,1,1.8,1.8Z" transform="translate(-1017.715 -542.996)" fill="#fff"></path>
                </svg>
            </a>
            @endif
        </div>

        <input type="text" class="user-image selected-image d-none" name="{{ $inputName }}" id="selected-image-url-{{$imageNumber}}" value="{{ $value }}" @if($isRequired) required @endif>
        <!-- image upload -->

    </div>
    @include('front.common.alert', ['input' => $inputName])

</div>



@push('scripts')
    <script>
        $(document).on('click', '#selected-div-' + '{{ $imageNumber }}', function () {
            var imagePath = $('#selected-image-url-' + '{{ $imageNumber }}').val();
            if (imagePath.length > 0) {
                $('#selected-image-url-' + '{{ $imageNumber }}').val(null);
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
                        $('#selected-div-'+ '{{$imageNumber}}').addClass('d-none');
                    }
                })
                    .fail(function (res) {
                        toastr.error("{{ __('Something went wrong, please refresh.') }}");
                    });

            }


        });

        $(document).on('change', '#choose-file-' + '{{ $imageNumber }}', function () {
            let url = window.Laravel.apiUrl + 'upload-image';
            let fileData = $(this).prop("files")[0];
            if (fileData !== undefined) {
                let formData = new FormData();
                if (!{{$allowVideo}}){
                    if (fileData.type.includes('video')){
                        toastr.error('{{ __('Error') }}','{{__('You can only upload images')}}');
                        return false;
                    }
                }
                formData.append("image", fileData);
                if (url.length > 0) {
                    jQuery.ajax({
                        url        : url,
                        type       : 'post',
                        dataType   : 'json',
                        cache      : false,
                        contentType: false,
                        processData: false,
                        data       : formData,
                        headers    : {
                            'X-CSRF-TOKEN': window.Laravel.csrfToken
                        },
                    }).done(function (res) {
                        if (res.success == false) {
                            toastr.error(res.message);
                        } else {
                            toastr.success(res.message);
                            {{--$("#selected-image-url-" + '{{ $imageNumber }}').hide();--}}
                            $(this).val("");
                            $('#choose-file-' + '{{ $imageNumber }}').val(null);
                            $('#selected-image-' + '{{ $imageNumber }}').attr('src', imageUrl(res.data.collection.file_name, 145, 115, 95, 1));
                            $('#selected-image-url-' + '{{ $imageNumber }}').val(res.data.collection.file_name);
                            $('#selected-div-' + '{{ $imageNumber }}').removeClass('d-none');
                            $('#delete-icon-' + '{{ $imageNumber }}').removeClass('d-none');
                            $('.single-img-upload').removeClass('d-none');
                        }
                    }).fail(function (res) {
                        alert('{{ __('Something went wrong, please try later.') }}');
                    });
                }
            }

        });
    </script>
@endpush
