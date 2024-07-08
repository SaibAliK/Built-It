@extends('front.layouts.app')
@section('content')
    @include('front.common.breadcrumb')

    <section class="login-seca-all-page">
        <div class="container">
            <div class="row">
                @forelse($categories as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="categories-page-main mb-3" id="showSubCategories">
                            <div class="card-cloth-ban-main">
                                <div class="inner-wrapper-card-coloth">
                                    <div class="image-block-card-c">
                                        <img src="{{imageUrl($item->image,135,149,90,1)}}" class="img-fluid"
                                             alt="image">
                                    </div>
                                    <input class="hiddenCategoryId" type="hidden" value="{{$item->id}}">
                                    <input class="hiddenCategoryTitle" type="hidden" value="{{translate($item->name)}}">
                                    <input class="hiddenCategoryimage" type="hidden"
                                           value="{{imageUrl($item->image,555,169,100,1)}}">
                                    <div class="right-content-of-card">
                                        <h3 class="tittle-name text-truncate">{{translate($item->name)}}</h3>
                                        <a href="#" type="button" class="detail-more-anchr" data-toggle="modal"
                                           data-target="#exampleModalCenter-1">{{__('Browse detail')}}
                                            <svg xmlns="http://www.w3.org/2000/svg" width="10.5" height="10.219"
                                                 viewBox="0 0 10.5 10.219">
                                                <path id="Path_48395" data-name="Path 48395"
                                                      d="M4.477-8.93a.476.476,0,0,0-.176.4.586.586,0,0,0,.176.4l2.813,2.7H.563a.542.542,0,0,0-.4.164.542.542,0,0,0-.164.4v.75a.542.542,0,0,0,.164.4.542.542,0,0,0,.4.164H7.289L4.477-.867a.586.586,0,0,0-.176.4.476.476,0,0,0,.176.4l.516.516a.566.566,0,0,0,.8,0L10.336-4.1a.542.542,0,0,0,.164-.4.542.542,0,0,0-.164-.4L5.789-9.445a.542.542,0,0,0-.4-.164.542.542,0,0,0-.4.164Z"
                                                      transform="translate(0 9.609)" fill="#666"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    @include('front.common.alert-empty', ['message' => __('No Category found.')])
                @endforelse
            </div>
            {{ $categories->withQueryString()->links('front.common.pagination', ['paginator' => $categories]) }}
        </div>
    </section>


    <div class="modal fade custom-modal-pa-all categories-modal-page" id="exampleModalCenter-1" tabindex="-1"
         role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="modal-body px-0">
                    <div class="categories-modal-1" id="">
                        <div class="top-image-cat-block">
                            <img src="{{asset('assets/front/img/modal-cat-img.jpg')}}" id="pushCateImage"
                                 class="img-fluid" alt="iamge">
                        </div>
                        <h3 class="plain-fabric-titlee" id="pushCateTitle">Plain Fabrics</h3>
                        <ul class="lising-of-sub-cate-modal" id="push_ajax_code">
                            <li class="itmes-list">
                                <a href="#">Lawn Fabric</a>
                                <svg xmlns="http://www.w3.org/2000/svg" width="10.781" height="11.138"
                                     viewBox="0 0 10.781 11.138">
                                    <path id="Path_48396" data-name="Path 48396"
                                          d="M-4.9-6.685a.353.353,0,0,1,.112.257.353.353,0,0,1-.112.257l-5.2,5.2a.353.353,0,0,1-.257.112.353.353,0,0,1-.257-.112l-.558-.558a.353.353,0,0,1-.112-.257.353.353,0,0,1,.112-.257l4.386-4.386-4.386-4.386a.353.353,0,0,1-.112-.257.353.353,0,0,1,.112-.257l.558-.558A.353.353,0,0,1-10.357-12a.353.353,0,0,1,.257.112Zm4.286,0A.353.353,0,0,1-.5-6.429a.353.353,0,0,1-.112.257l-5.2,5.2a.353.353,0,0,1-.257.112.353.353,0,0,1-.257-.112l-.558-.558A.353.353,0,0,1-7-1.786a.353.353,0,0,1,.112-.257L-2.5-6.429l-4.386-4.386A.353.353,0,0,1-7-11.071a.353.353,0,0,1,.112-.257l.558-.558A.353.353,0,0,1-6.071-12a.353.353,0,0,1,.257.112Z"
                                          transform="translate(11.283 11.998)" fill="#45cea2"/>
                                </svg>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            function jsTranslate(data) {
                if (locale == 'ar' && data['ar'] !== undefined || data['ar'] == '') {
                    return data['en']
                }
                return data[locale];
            }

            $(document).on("click", '.cate_click', function (e) {
                e.preventDefault();
                let id = $(this).data('id');
                let parent_id = $(this).data('parent');
                let urlds = "{{ route('front.products') }}" + "?category_id=" + parent_id + "&subcategory_id=" +id;
                window.location.href = urlds;
            });

            $(document).on("click", "#showSubCategories", function (e) {
                e.preventDefault();
                let categories = [];
                let html = '';
                var category_id = $(this).find('.hiddenCategoryId').val();
                var category_image = $(this).find('.hiddenCategoryimage').val();
                var category_title = $(this).find('.hiddenCategoryTitle').val();
                $('#push_ajax_code').empty();
                $("#pushCateImage").attr('src', category_image);

                $("#pushCateTitle").html(category_title);
                $.ajax({
                    type: "GET",
                    url: "{{route('api.categories.sub-categories')}}/" + category_id,
                    dataType: 'json',
                    success: function (response) {
                        for (var i = 1; i < response.data.collection.length; i++) {
                            categories.push(response.data.collection[i]);
                        }
                        categories.map((cate, index) => {
                            html += `<li class="itmes-list cate_click" data-id="${cate.id}" data-parent="${cate.parent_id}">
                                <a  data-id="${cate.id}" data-parent="${cate.parent_id}">${jsTranslate(cate.name)}</a>
                                <svg xmlns="http://www.w3.org/2000/svg" width="10.781" height="11.138"
                                     viewBox="0 0 10.781 11.138">
                                    <path id="Path_48396" data-name="Path 48396"
                                          d="M-4.9-6.685a.353.353,0,0,1,.112.257.353.353,0,0,1-.112.257l-5.2,5.2a.353.353,0,0,1-.257.112.353.353,0,0,1-.257-.112l-.558-.558a.353.353,0,0,1-.112-.257.353.353,0,0,1,.112-.257l4.386-4.386-4.386-4.386a.353.353,0,0,1-.112-.257.353.353,0,0,1,.112-.257l.558-.558A.353.353,0,0,1-10.357-12a.353.353,0,0,1,.257.112Zm4.286,0A.353.353,0,0,1-.5-6.429a.353.353,0,0,1-.112.257l-5.2,5.2a.353.353,0,0,1-.257.112.353.353,0,0,1-.257-.112l-.558-.558A.353.353,0,0,1-7-1.786a.353.353,0,0,1,.112-.257L-2.5-6.429l-4.386-4.386A.353.353,0,0,1-7-11.071a.353.353,0,0,1,.112-.257l.558-.558A.353.353,0,0,1-6.071-12a.353.353,0,0,1,.257.112Z"
                                          transform="translate(11.283 11.998)" fill="#45cea2"/>
                                </svg>
                            </li>`;
                        });
                        $("#push_ajax_code").append(html);
                    }
                });
            });
        });
    </script>
@endpush
