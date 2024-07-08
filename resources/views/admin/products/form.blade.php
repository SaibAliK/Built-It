{!! Form::model($product, ['url' => $action, 'method' => 'post', 'role' => 'form', 'id' => 'productForm', 'class' => 'm-form m-form--fit m-form--label-align-right', 'enctype' => 'multipart/form-data']) !!}
<div class="m-portlet__body mt-3">
    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('Title') !!}
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::text('title', old('title', $product->translations[$languageId]['title']), ['class' => 'form-control', 'placeholder' => 'Title', 'required' => 'required']) !!}
        </div>
    </div>
    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            <span class="text-danger"></span>

        </label>
        <div class="col-7">
            <button type="button" class="btn btn-accent m-btn m-btn--air m-btn--custom product-upload-image">
                <i class="fa fa-spinner fa-spin" style=" display: none"></i>
                Upload Image
            </button>
            {{-- @php dd($product->image1); @endphp --}}
            <input type="file" id="upload_image_input" class="hide product_upload_image_input" accept="image/*">
            <input class="defaultImage" type="hidden" name="default_image" value="{!! old('default_image') ? 'default_image' : $product->image1 !!}">
            <input class="product-images-array" type="hidden" name="images" value='{!! old('product_image_array') ? 'product_image_array' : json_encode($product->images) !!}'>
            <p class="mx-0 text-danger smaller-font-size mt-2">Recommended size 482 x 526</p>
        </div>
    </div>
    <div class="form-group m-form__group row image-product">


    </div>
    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('Agency') !!}
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {{-- {!! Form::select('category_id', $category, old('category_id',$product->category_id), ['class' => 'form-control category_id', 'id' => 'category_id' ,'required'=>'required']) !!} --}}
            <select name="agency_id" class="form-control agency_id" required>
                <option disabled selected>Select Agency</option>
                @foreach ($agencies as $key => $value)
                    <option value="{{ $key }}" {!! old('agency_id', $product->agency_id) == $key ? 'selected' : '' !!}>{{ $value }}</option>
                @endforeach
            </select>
            <br>
        </div>
    </div>

    <div class="form-group m-form__group row Branches">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('Branch') !!}
            <span class="text-danger"></span>
        </label>
        <div class="col-7">
            <select {{ $productId > 0 ? '' : 'disabled' }} id="branchID" name="branch_id" class="form-control branch">
                <option disabled selected>Select Branch</option>
                @if ($product->branches)
                    @foreach ($product->branches as $key => $value)
                        <option value="{{ $key }}" {{ $key == $product->branch_id ? 'selected' : '' }}>
                            {{ $value }}</option>
                    @endforeach
                @endif
            </select>
            <br>
        </div>
    </div>


    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('Make') !!}
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {{-- {!! Form::select('category_id', $category, old('category_id',$product->category_id), ['class' => 'form-control category_id', 'id' => 'category_id' ,'required'=>'required']) !!} --}}
            <select id="category_id" name="category_id" class="form-control category_id" required>
                <option disabled selected>Select Make</option>
                @foreach ($category as $key => $value)
                    <option value="{{ $key }}" {!! old('category_id', $product->category_id) == $key ? 'selected' : '' !!}>{{ $value }}</option>
                @endforeach
            </select>
            <br>
        </div>
    </div>

    <div class="form-group m-form__group row subcategories">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('Model') !!}
            <span class="text-danger"></span>
        </label>
        <div class="col-7">
            <select {{ $productId > 0 ? '' : 'disabled' }} id="subCategory" name="subCategory"
                class="form-control subCategory">
                <option disabled selected>Select Model</option>
                @if ($product->subCategories)
                    @foreach ($product->subCategories as $key => $value)
                        <option value="{{ $key }}" {{ $key == $product->subCategory_id ? 'selected' : '' }}>
                            {{ $value }}</option>
                    @endforeach
                @endif
            </select>
            <br>
        </div>
    </div>
    <div class="form-group m-form__group row versions">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('Version') !!}
            <span class="text-danger"></span>
        </label>
        <div class="col-7">
            <select {{ $productId > 0 ? '' : 'disabled' }} id="version" name="version" class="form-control version">
                <option disabled selected>Select Version</option>
                @if ($product->versions)
                    @foreach ($product->versions as $key => $value)
                        <option value="{{ $key }}" {{ $key == $product->version_id ? 'selected' : '' }}>
                            {{ $value }}</option>
                    @endforeach
                @endif
            </select>
            <br>
        </div>
    </div>
    <div id="selectAttribute" class="form-group m-form__group row mainAttribute ">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('Attribute') !!}
            <span class="text-danger"></span>
        </label>
        <div class="col-7">
            @if ($productId > 0 && $product->attributes)
                <select {{ $productId > 0 ? '' : 'disabled' }} id="{{ 'attribute' . $languageId }}" multiple="multiple"
                    name="attribute[]" class="form-control attribute">
                    @foreach ($product->attributes as $key => $value)
                        <option value="{{ $value->id }}"
                            {{ in_array($value->id, $product->selectedAttributes) ? 'selected' : '' }}>{{ $value->title }}
                        </option>
                    @endforeach
                </select>
            @else
                <select {{ $productId > 0 ? '' : 'disabled' }} id="{{ 'attribute' . $languageId }}" multiple="multiple"
                    name="attribute[]" class="form-control attribute">
                </select>
                <br>
            @endif
        </div>
    </div>
    <div id="{{ 'subAttribute' . $languageId }}" class=" subAttribute">
        <div class="form-group m-form__group row">
            @if ($productId > 0 && $product->attributes)
                @foreach ($product->attributes as $key => $value)
                    <label for="example-text-input" class="col-3 col-form-label">
                        {{ $value->title }}
                        <span class="text-danger">*</span>
                    </label>
                    <div class="col-7">
                        <select {!! $value->multiple == 1 ? 'multiple' : '' !!} name="{{ 'subAttributes[' . $value->id . '][]' }}"
                            class="form-control">
                            @foreach ($value->subAttributes as $subAttributeid => $subAttribute)
                                <option value="{{ $subAttributeid }}"
                                    {{ in_array($subAttributeid, $product->selectedSubAttributes) ? 'selected' : '' }}>
                                    {{ $subAttribute }}</option>
                            @endforeach

                        </select>
                        <br>
                    </div>
                @endforeach
            @endif
        </div>
    </div>



    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('Price') !!}
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::number('price', old('price', $product->price), ['class' => 'form-control', 'placeholder' => 'price', 'min=0', 'required' => 'required']) !!}
        </div>
    </div>
    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('Discount') !!}
            <span class="text-danger"></span>
        </label>
        <div class="col-7">
            {!! Form::number('discount', old('discount', $product->discount), ['class' => 'form-control', 'placeholder' => 'discount', 'min=0', 'max=100', 'required' => 'required']) !!}
        </div>
    </div>
    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('ExpiryDate') !!}
            <span class="text-danger"></span>
        </label>
        <div class="col-7">
            <input type="text" value="{!! old('expiry_date') ? old('expiry_date') : \Carbon\Carbon::createFromTimestamp($product->expiry_date)->format('m/d/Y') !!}"
                class="form-control m-bootstrap-select event-datetime-picker" name="expiry_date"
                placeholder="expiry date">
        </div>
    </div>
    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('Quantity') !!}
            <span class="text-danger">*</span>
        </label>
        <div class="col-7">
            {!! Form::number('quantity', old('quantity', $product->quantity), ['class' => 'form-control', 'placeholder' => 'quantity', 'min=0', 'max=10000', 'required' => 'required']) !!}
        </div>
    </div>
    {{-- <div class="form-group m-form__group row"> --}}
    {{-- <label for="example-text-input" class="col-3 col-form-label"> --}}
    {{-- {!! __('Create Offer') !!} --}}
    {{-- <span class="text-danger"></span> --}}
    {{-- </label> --}}
    {{-- <div class="col-7 mt-2 pt-1"> --}}
    {{-- <input class="offer-create" type="checkbox" {{$product->offer == 1?'checked':''}}  name="offer" --}}
    {{-- value="1"> --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- <div class="discount-offer" style="display: {{$product->offer == 1?'block':'none'}}"> --}}
    {{-- <div class="form-group m-form__group row"> --}}
    {{-- <label for="example-text-input" class="col-3 col-form-label"> --}}
    {{-- {!! __('Discounted Price') !!} --}}
    {{-- <span class="text-danger">*</span> --}}
    {{-- </label> --}}
    {{-- <div class="col-7"> --}}
    {{-- @if ($product->offer == 1) --}}
    {{-- <input type="number" name="discount" class="form-control" step="0.1" min="0" placeholder="discount" value="{!! old('discount')?old('discount'):$product->discount !!}" > --}}
    {{-- {!! Form::number('discount', old('discount', $product->discount), ['class' => 'form-control','step' => '0.1','min=0', 'placeholder' => 'discount', ]) !!} --}}
    {{-- @else --}}
    {{-- <input type="number" name="discount" class="form-control" step="0.1" min="0" placeholder="discount" value="{!! old('discount')?old('discount'):'' !!}" > --}}

    {{-- {!! Form::number('discount', old('discount'), ['class' => 'form-control','step' => '0.1','min=0', 'placeholder' => 'discount', ]) !!} --}}
    {{-- @endif --}}
    {{-- </div> --}}
    {{-- </div> --}}
    {{-- </div> --}}

    <div class="form-group m-form__group row">
        <label for="example-text-input" class="col-3 col-form-label">
            {!! __('Description') !!}
            <span class="text-danger"></span>
        </label>
        <div class="col-7">
            {!! Form::textarea('description', old('description', $product->translations[$languageId]['description']), ['class' => 'form-control', 'placeholder' => 'Description']) !!}
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
                <button type="submit" class="btn btn-accent m-btn m-btn--air m-btn--custom mx-3">
                    @if ($productId > 0)
                        Update Car
                    @else
                        Add Car
                    @endif
                </button>
                <a href="{!! route('admin.products.index') !!}"
                    class="btn btn-secondary m-btn m-btn--air m-btn--custom">{!! __('Cancel') !!}</a>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
<script>
    $(document).ready(function() {

        if ("{!! $productId == 0 !!}") {
            $('.subcategories').hide();
            $('.versions').hide();
            $('.mainAttribute').hide();
            $('.Branches').hide();
        } else {
            if ("{!! empty($product->subCategories) !!}") {
                $('.subcategories').hide();
                $('.versions').hide();
            }
            if ("{!! empty($product->attributes) !!}") {
                $('.mainAttribute').hide();
            }
        }

        $('#book{{ $languageId }}').change(function() {
            if (this.checked) {
                $("#is_book{{ $languageId }}").css("display", "block");
                $("#isbn{{ $languageId }}").css("display", "block");
            } else
                $("#is_book{{ $languageId }}").css("display", "none");
            $("#isbn{{ $languageId }}").css("display", "none");
        });
        $('.category_id').change(function() {
            let id = this.value;
            subCategories(id);
        });
        $('.subCategory').change(function() {
            let id = this.value;
            versions(id);
        });
        $('.agency_id').change(function() {
            let id = this.value;
            branches(id);
        });

        let requestTimer;
        let xhr;
        $('#attribute{{ $languageId }}').change(function($event) {
            $('#subAttribute{{ $languageId }}').empty();
            var select1 = document.getElementById("attribute{{ $languageId }}");
            var selected1 = [];
            for (var i = 0; i < select1.length; i++) {
                if (select1.options[i].selected) selected1.push(select1.options[i].value);
            }
            if (requestTimer) window.clearTimeout(
            requestTimer); //see if there is a timeout that is active, if there is remove it.
            if (xhr) xhr.abort(); //kill active Ajax request
            requestTimer = setTimeout(function() {
                subAttributes(selected1)
            }, 1000);
        });

        function subAttributes(array) {
            if (array.length > 0 || array.length != undefined) {
                xhr = $.ajax({
                    type: 'POST',
                    url: window.Laravel.apiUrl + "sub-attributes",
                    data: {
                        id: array
                    },
                    success: function(response) {
                        $.each(response.attributes, function(key, value) {
                            let multiple = '';
                            if (value['multiple'] == 1) {
                                multiple = 'multiple'
                            }
                            $div = $(
                                "<div id='subAttributes' class='form-group m-form__group row subAttributes'></div>"
                                )
                            var label = $("<label>").text(value['translation']['name'])
                                .attr("for", "example-text-input").attr("class",
                                    "col-3 col-form-label");
                            $span = $("  <span class='text-danger'>*</span>")
                            $(label).append($span);
                            $($div).append(label);
                            $div2 = $("<div class='col-7'></div>");
                            var select = $(
                                    "<select style='width: 100%' id='subattributes' class='form-control' " +
                                    multiple + "></select>").attr("data-id", "attributes")
                                .attr("name", `subAttributes[` + value['id'] + '][]');
                            $.each(value['subAttributes'], function(key1, value1) {
                                select.append($("<option></option>").attr("value",
                                        value1['translation']['attribute_id'])
                                    .text(value1['translation']['name']));
                            });
                            $($div2).append(select);
                            $($div).append($div2);
                            $(".subAttribute").append($div);
                        });
                        $('.subAttribute').removeAttr('disabled');
                    },
                    error: function() {
                        $(".subAttribute").empty();
                        $('.subAttribute').prop('disabled', true);
                    }
                })
            } else {
                $(".subAttribute").empty();
                $('.subAttribute').prop('disabled', true);
            }
        }

        function subCategories(id) {
            if (id > 0) {
                $.ajax({
                    url: window.Laravel.apiUrl + "sub-categories/" + id,
                    success: function(response) {
                        $(".subCategory").empty();
                        $(".attribute").empty();
                        $("#subAttribute{{ $languageId }}").empty();
                        if (response.subCategories != '') {
                            $(".subCategory").append(
                                '<option value="" selected>select Model</option>');
                            $.each(response.subCategories, function(key, value) {
                                $(".subCategory").append('<option value="' + key + '">' +
                                    value + '</option>');
                            });
                            $('.subCategory').removeAttr('disabled');
                            $('.subcategories').show();
                        } else {
                            $('.subcategories').hide();
                        }
                        if (response.attribute != '') {
                            $.each(response.attribute, function(key, value) {
                                $(".attribute").append('<option value="' + value['id'] +
                                    '">' + value['title'] + '</option>');
                            });
                            $('.attribute').removeAttr('disabled');
                            $('.mainAttribute').show();

                        } else {
                            $('.mainAttribute').hide();
                        }


                    },
                    error: function() {
                        $(".subCategory").empty();
                        $('.subCategory').prop('disabled', true);
                    }
                })
            } else {
                $(".subCategory").empty();
                $('.subCategory').prop('disabled', true);

            }
        }

        function branches(id) {
            if (id > 0) {
                $.ajax({
                    url: window.Laravel.apiUrl + "agency-branches?agency_id=" + id,
                    type: "post",
                    success: function(response) {
                        console.log(response.data.collection.length);
                        $(".branch").empty();
                        if (response.data.collection.length > 0) {
                            $(".branch").append('<option value="" selected>select Branch</option>');
                            $.each(response.data.collection, function(key, value) {
                                $(".branch").append('<option value="' + value['id'] + '">' +
                                    value['translation']['name'] + '</option>');
                            });
                            $('.branch').removeAttr('disabled');
                            $('.Branches').show();
                        } else {
                            $('.Branches').hide();
                        }

                    },
                    error: function() {
                        $(".version").empty();
                        $('.version').prop('disabled', true);
                    }
                })
            } else {
                $(".subCategory").empty();
                $('.subCategory').prop('disabled', true);

            }
        }

        function versions(id) {
            if (id > 0) {
                $.ajax({
                    url: window.Laravel.apiUrl + "sub-categories/" + id,
                    success: function(response) {
                        $(".version").empty();
                        if (response.subCategories != '') {
                            $(".version").append(
                                '<option value="" selected>select sub version</option>');
                            $.each(response.subCategories, function(key, value) {
                                $(".version").append('<option value="' + key + '">' +
                                    value + '</option>');
                            });
                            $('.version').removeAttr('disabled');
                            $('.versions').show();
                        } else {
                            $('.versions').hide();
                        }

                    },
                    error: function() {
                        $(".version").empty();
                        $('.version').prop('disabled', true);
                    }
                })
            } else {
                $(".subCategory").empty();
                $('.subCategory').prop('disabled', true);

            }
        }


    });
</script>
@push('script-page-level')
    <script>
        let images = JSON.parse($(".product-images-array").val());
        let defaultImage = $(".defaultImage").val();

        function makeDefaut(image) {
            defaultImage = image;
            $(".defaultImage").val(defaultImage);
            apeendImaged();
        }

        function deleteImage(image) {
            $.ajax({
                url: window.Laravel.baseUrl + 'delete-image',
                type: "post",
                data: {
                    'path': image
                },
                headers: {
                    'X-CSRF-TOKEN': window.Laravel.csrfToken
                }
            });
            let index = images.indexOf(image);
            images.splice(index, 1);
            apeendImaged();
        }

        function changeType(image) {
            let str = image.split(",");
            for (let img of images) {
                if (img[0] == str[0]) {
                    if (img[1] == 'interior') {
                        img.splice(1, 1, 'exterior');
                    } else {
                        img.splice(1, 1, 'interior');
                    }
                }
            }
            apeendImaged();
        }


        function apeendImaged() {
            $(".image-product").empty();
            let imageDiv = ``;
            for (let image of images) {
                let imagePath = imageUrl(window.Laravel.base + image[0], 120, 120, 100, 1);
                if (image[0] != defaultImage) {
                    // imageDiv += `<div class='d-flex justify-cotent-center align-items-center'><img id='image' name='image' style='width:120px;height: 120px;' src='${imagePath}'></div> <a onclick='makeDefaut("${image}")'>default</a> <a onclick='deleteImage("${image}")'>delete</a>`
                    imageDiv += `<div class="col-3 border box-maker">
                            <div class="height-width-set">
                              <div class="button-groups d-flex justify-content-around">
                                <button class="float-left right " onclick='makeDefaut("${image[0]}")'><i class="fa fa-check"></i></button>
                                <button class="float-right left" onclick='deleteImage("${image[0]}")'><i class="fa fa-times"></i></button>
                              </div>
                              <img src="${imagePath}" class="img-fluid set-max-height-image">
                            </div>
                            <button class="water-maker" onclick='changeType("${image}")'><i class="fa fa-check"></i>` +
                        image[1] + `</button>
                        </div>`

                } else {
                    //

                    // imageDiv += `<div class='d-flex justify-cotent-center align-items-center'><img id='image' name='image' style='width:120px;height: 120px;' src='${imagePath}'></div> <a >defaulted</a>`
                    imageDiv += `<div class="col-3 border box-maker">
                            <div class="height-width-set">
                              <div class="button-groups d-flex justify-content-around">
                                <button class="float-left right icon-active"><i class="fa fa-check"></i></button>
                              </div>
                              <img src="${imagePath}" class="img-fluid set-max-height-image">
                            </div>
                            <button class="water-maker" onclick='changeType("${image}")'><i class="fa fa-check"></i>` +
                        image[1] + `</button>
                        </div>`

                }
            }
            console.log(imageDiv);
            $('.image-product').append(imageDiv);
            // $('#image-product-1').append(imageDiv);
            $(".product-images-array").val(JSON.stringify(images));
        }
        $(document).ready(function() {
            var date = new Date();
            if ($('.event-datetime-picker').length > 0) {
                $('.event-datetime-picker').datepicker({
                    format: "m/d/yyyy",
                    startDate: date
                });
            }
            if (images.length > 0) {
                console.log(images.length)
                setTimeout(() => {
                    apeendImaged();
                }, 1000)
            }
            $('.product-upload-image').on('click', function() {
                $(this).next().click();
            });
            $('.product_upload_image_input').on('change', function() {
                $(".fa-spinner").show();
                $(".product-upload-image").attr('disabled', 'disabled');
                var fileData = $(this).prop("files")[0];
                var formData = new FormData();
                formData.append("image", fileData);
                var url = window.Laravel.baseUrl + 'upload-image';
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
                        .done(function(res) {
                            // $('.public_select_image').val(res.data);
                            // let img  = imageUrl(window.Laravel.base+res.data, 120, 120, 100, 1);

                            images.push([res.data, 'interior']);
                            apeendImaged();
                            $(".fa-spinner").hide();
                            $('.product-upload-image').removeAttr('disabled');
                            toastr.success(res.message, 'Success');
                        })
                        .fail(function(res) {
                            alert('Something went wrong, please try later.');
                        });
                }
            });
            $('.select_image').hide();
        })
    </script>
@endpush
