<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\City;
use App\Jobs\SendMail;
use App\Models\Review;
use App\Models\Comment;
use App\Models\Category;
use App\Http\Dtos\AdminDto;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Dtos\SendEmailDto;
use App\Http\Libraries\Uploader;
use App\Http\Requests\AdminRequest;
use App\Http\Requests\ImageRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Http\Repositories\FaqRepository;
use App\Http\Repositories\CityRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Repositories\AdminRepository;
use App\Http\Repositories\CouponRepository;
use App\Http\Repositories\SliderRepository;
use App\Http\Repositories\ArticleRepository;
use App\Http\Repositories\GalleryRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\InfoPagesRepository;
use App\Http\Repositories\AdvertisementRepository;

class IndexController extends Controller
{
    protected CategoryRepository $categoryRepository;
    protected UserRepository $userRepository;
    protected GalleryRepository $galleryRepository;
    protected FaqRepository $faqRepository;
    protected InfoPagesRepository $pagesRepository;
//    protected CouponRepository $couponRepository;
    protected ArticleRepository $articleRepository;
    protected ProductRepository $productRepository;
    protected CityRepository $cityRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->articleRepository = new ArticleRepository();
        $this->productRepository = new ProductRepository();
        $this->galleryRepository = new GalleryRepository();
        $this->pagesRepository = new InfoPagesRepository();
//        $this->couponRepository = new CouponRepository();
        $this->faqRepository = new FaqRepository();
        $this->cityRepository = new CityRepository();
    }

    public function uploadImage(ImageRequest $request)
    {
        try {
            $path = 'front/media';
            $input = 'image';
            if ($request->filled('path')) {
                $path = $request->path;
            }
            if ($request->filled('input')) {
                $input = $request->input;
            }
            if ($request->hasFile($input)) {
                $image = uploadImage($request->file($input), $path, $input);
            } else {
                throw new Exception(__('Something went wrong'));
            }
            return responseBuilder()->success(__('Image Uploaded'), $image);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }
    
    function city($id, $store_id)
    {
        $areas= $this->cityRepository->getAreas($id,$store_id);
        $html='';
        $html .='<option value="">Choose the City Area</option>';
        if(count($areas)>0)
        {
            foreach($areas as $area)
            {
                $html .='<option value='.$area->id.'>'. translate($area->name) .'</option>';
            }
        }
        return $html;
    }

    public function removeImage(Request $request)
    {
        deleteImage($request->image);
        return responseBuilder()->success(__('Image Delete Successfully'));
    }

//    public function areasForPopup(Request $request)
//    {
//        $areas = $this->cityRepository->areasForPopup($request->id);
//        return responseBuilder()->success('Areas', $areas);
//    }

    public function areas(Request $request)
    {
        $areas = $this->cityRepository->areas($request->id);
        return responseBuilder()->success('Areas', $areas);
    }

    public function deliveryCompanies()
    {
        try {
            $this->userRepository->setPaginate(12);
            $suppliers = $this->userRepository->companyFilter();
            foreach ($suppliers as $item) {
                $item->getFormattedModel();
            }
            return responseBuilder()->success(__('Companese'), $suppliers);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function multiImageUpload(ImageRequest $request)
    {
        try {
            $imageArray = [];
            $path = 'front/media';
            if ($request->has('path')) {
                $path = $request->path;
            }
            if ($request->hasFile('images')) {
                $data = $request->allFiles();
                foreach ($data['images'] as $key => $img) {
                    $image = uploadImage($img, $path);
                    $imageArray[$key] = $image;
                }
            }
            return responseBuilder()->success(__('Images Uploaded'), $imageArray);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function suppliers(Request $request)
    {
        try {

            $ss_data = $request->get('user_data');

            if (!empty($ss_data)) {
                if (!array_key_exists('city_id', $ss_data) || !array_key_exists('area_id', $ss_data) || !array_key_exists('address', $ss_data) || !array_key_exists('latitude', $ss_data) || !array_key_exists('longitude', $ss_data)) {
                    return responseBuilder()->error(__('The data provided is invalid.'));
                }

                $city = City::where('id', $ss_data['area_id'])->where('parent_id', $ss_data['city_id'])->first();
                $polygon_lat = [];
                $polygon_lng = [];
                $count_polygon = count($city->polygon);
                foreach ($city->polygon as $item) {
                    array_push($polygon_lat, $item['lat']);
                    array_push($polygon_lng, $item['lng']);
                }

                if (!Check_is_in_polygon($count_polygon - 1, $polygon_lng, $polygon_lat, $ss_data['longitude'], $ss_data['latitude'])) {
                    return responseBuilder()->error(__('Address not found'));
                }
            }


            $this->userRepository->setPaginate(12);
            $suppliers = $this->userRepository->storeFilter(null, $request);
            foreach ($suppliers as $item) {
                $item->getFormattedModel();
            }
            return responseBuilder()->success(__('Suppliers'), $suppliers);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function suppliersDetail(Request $request, $id)
    {
        try {

            if (auth()->check() && auth()->user()->isUser()) {
//                $userId = auth()->id();
//                $this->userRepository->setRelations(['orderDetails' => function ($q) use ($userId) {
//                    $q->with('orderItems.product')->whereHas('orders', function ($q) use ($userId) {
//                        $q->where('user_id', '=', $userId)->where('status', '=', 'completed')->orderBy('created_at', 'desc');
//                    });
//                    $q->doesnthave('review');
//                }]);
            }


            if (!isset($request->products) && !isset($request->reviews)) {
                $request->merge(['products' => true]);
            }


            $supplier = $this->userRepository->get($id)->getFormattedModel();
            $reviews = Review::where('store_id', $id)->where('product_id', NUll)->with('user:id,user_name')->orderBy('id', 'desc')->get();

            $supplier['can_review_store'] = false;
            if (Auth()->user() != NULL && Auth()->user()->isUser()) {
//                $user = Auth()->user();
//                $orderdetail_count = OrderDetail::where(['user_id' => $user->id, 'store_id' => $id])->count();
//                $review_count = Review::where(['user_id' => $user->id, 'store_id' => $id])->count();
//                if ($orderdetail_count > $review_count) {
//                    $supplier->can_review_store = true;
//                }
            }


            $store_id = $supplier->id;
            $request->merge([
                'categoriesWhereHas' => true,
                'subCategoryWhereHas' => true,
                'is_id_card_verified' => true,
                'store_id' => $store_id,
            ]);
            $this->productRepository->setPaginate(8);
            $products = $this->productRepository->all($request->all());
            foreach ($products as $item) {
                $item->getFormattedModel();
            }

            return responseBuilder()->success(__('Suppliers Detail'), ['suppliers' => $supplier, 'products' => $products, 'reviews' => $reviews]);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function offers(Request $request)
    {
        try {
            $request->merge([
                'categoriesWhereHas' => true,
                'subCategoryWhereHas' => true,
                'is_id_card_verified' => true,
                'product_type' => ['offer'],
                'status' => 'approved'
            ]);

            $this->productRepository->setRelations([
                'category:id,parent_id,name',
                'store:id, user_type, supplier_name, is_id_card_verified',
                'images:id,product_id,file_path,file_default,file_type'
            ]);
            $this->productRepository->setPaginate(8);
            $offers = $this->productRepository->all($request->all(), 'search', true);

            foreach ($offers as $item) {
                $item->getFormattedModel();
            }

            return responseBuilder()->success(__('Offers Product'), ['suppliers' => $offers]);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }


    public function couponValidate(CouponRequest $request)
    {
        try {

            $data = $this->couponRepository->addUserCoupon($request->get('code'));
            if ($data == "expired") {
                return responseBuilder()->error(__('Coupon is Expired.'));
            }
            if ($data == "finished") {
                return responseBuilder()->error(__('Coupon is Expired Or Finished.'));
            }
            return responseBuilder()->success(__('Coupon is Successfully Added.'));
        } catch (\Exception $e) {
            //            return responseBuilder()->error($e->getMessage());
            return responseBuilder()->error('Something Went Wrong.');
        }
    }


    public function couponList($coupon)
    {
        try {

            $coupons = $this->couponRepository->getQuery()->where('coupon_code', $coupon)->where('status', 'active')->first();
            //throw exception if $coupons is null
            throw_if(!$coupons, new exception('Coupon Not Found'));

            return responseBuilder()->success(__('Coupon Listing'), $coupons->toArray());
        } catch (\Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function products(Request $request)
    {
        try {
            $request->merge([
                'categoriesWhereHas' => true,
                'subCategoryWhereHas' => true,
                'is_id_card_verified' => true,
            ]);

            if (!isset($request->all) && !isset($request->featured) && !isset($request->offer)) {
                $request->merge([
                    'all' => true,
                ]);
            }

            if (isset($request->offer)) {
                $request->merge([
                    'product_type' => ['offer'],
                    'status' => 'approved'
                ]);
            }

            $this->productRepository->setRelations([
                'category:id,parent_id,name',
                'store:id, user_type, supplier_name, is_id_card_verified',
                'images:id,product_id,file_path,file_default,file_type'
            ]);

            $this->productRepository->setPaginate(8);
            $products = $this->productRepository->all($request->all(), 'search', true);
            foreach ($products as $item) {
                $item->getFormattedModel();
            }

            return responseBuilder()->success(__('All Products'), $products);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function productDetail(Request $request, $id)
    {
        try {
            $userId = 0;
            if (auth()->check() && auth()->user()->isUser()) {
                $userId = auth()->user()->id;
            }

            if (\Auth::check()) {
//                $userId = auth()->user()->id;
//
//                $this->productRepository->setRelations([
//                    'store:id,user_type,supplier_name,city_id,is_id_card_verified,address,latitude,longitude,image,rating',
//                    'images:id,file_path,file_default,file_type,product_id',
//                    'category:id,name',
//                    'subCategory:id,name',
//                    'orderDetailItems' => function ($q) use ($userId) {
//                        $q->whereHas('orderDetail', function ($q) use ($userId) {
//                            $q->where('status', 'completed')->whereHas('order', function ($q) use ($userId) {
//                                $q->where('user_id', $userId);
//                            });
//                        });
//                        $q->doesnthave('review');
//                    }
//                ]);
            } else {
                $this->productRepository->setRelations(
                    [
                        'store:id,user_type,supplier_name,city_id,is_id_card_verified,address,latitude,longitude,image,rating',
                        'images:id,file_path,file_default,file_type,product_id',
                        'category:id,name',
                        'subCategory:id,name'
                    ]
                );
            }


            $product = $this->productRepository->get($id)->getFormattedModel();
            if ($product) {
                $store = $product->store;
                if ($store->isSupplier()) {
                    if (!$store->isCardImageVerified()) {
                        // return redirect(route('front.404'))->with('err', __('Product is no longer available'));
                    }
                }
            } else {
                // return redirect(route('front.404'));
            }
            $product['can_review_product'] = false;
            if (Auth()->user() != NULL && Auth()->user()->isUser()) {
//                $user = Auth()->user();
//                $orderdetail_count = OrderDetail::where(['user_id' => $user->id])->whereHas('orderItems', function ($q) use ($product) {
//                    $q->where(['product_id' => $product->id]);
//                })->count();
//                $review_count = Review::where(['user_id' => $user->id, 'product_id' => $id])->count();
//                if ($orderdetail_count > $review_count) {
//                    $product->can_review_product = true;
//                }
            }

            $reviews = Review::where('store_id', $product->user_id)->where('product_id', $product->id)->where('order_detail_id', NUll)->orderBy('id', 'desc')->with('user:id,user_name,email,phone,image,user_type,rating')->get();

            return responseBuilder()->success(__('Product Detail'), ['products' => $product, 'reviews' => $reviews]);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function articles()
    {
        try {
            $this->articleRepository->setPaginate(6);
            $articles = $this->articleRepository->all();
            return responseBuilder()->success(__('Articles'), $articles);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function articleDetail($slug)
    {
        $article = $this->articleRepository->get($slug);
        return responseBuilder()->success(__('Articles'), ['articles' => $article]);
    }

    public function gallery()
    {
        try {
            $this->galleryRepository->setPaginate(8);
            $images = $this->galleryRepository->all_for_front();
            return responseBuilder()->success(__('Gallery'), $images);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function faqs()
    {
        try {
            $this->faqRepository->setPaginate(0);
            $faqs = $this->faqRepository->all();
            return responseBuilder()->success(__('Faq'), $faqs);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function sliders()
    {
        try {
            $sliders = $this->sliderRepository->all();
            return responseBuilder()->success(__('sliders'), $sliders);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function ads()
    {
        try {
            $ads = $this->advertisementRepository->all_for_front();
            foreach ($ads as $item) {
                $item->getFormattedModel();
            }
            return responseBuilder()->success(__('Ads'), $ads);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function most_selling_product()
    {
        try {
            $this->productRepository->setPaginate(6);
            $params = [
                'categoriesWhereHas' => true,
                'subCategoryWhereHas' => true,
                'is_id_card_verified' => true,
                'most_selling' => true,
            ];
            $most_selling_product = $this->productRepository->all($params, 'search', true);
            return responseBuilder()->success(__('Most Selling Product'), $most_selling_product);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function pages($slug)
    {
        try {
            $pageData = $this->pagesRepository->getslug($slug);
            return responseBuilder()->success(__('Pages'), ['Info Pages' => $pageData]);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function SaveCommentForArticle(Request $request)
    {
        try {

            if (auth()->user()->isSupplier()) {
                throw new Exception(__('Supplier is not allow to comment'));
            }

            $request->validate([
                'article_id' => 'required',
                'comment' => 'required',
            ]);
            $comment = Comment::create([
                'article_id' => $request->article_id,
                'comment' => $request->comment,
                'user_id' => auth()->user()->id
            ]);
            return responseBuilder()->success(__('Successfully Created'));
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }


    public function setUserSettings()
    {
        $userRepository = new UserRepository();
        $settings = $userRepository->setUserSettings();
        return responseBuilder()->success(__("Settings updated successfully."), ['settings' => $settings]);
    }

    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'email' => 'required|email',
            'message_text' => 'required',
        ]);

        try {
            $data = collect($request->all());

            $data['receiver_name'] = $request->name;
            $data['receiver_email'] = $request->email;
            $data['subject'] = $request->subject;
            $data['message'] = $request->message_text;
            $data['message_text'] = $request->message_text;
            $data['view'] = 'emails.user.contact_us';
            $data['sender_name'] = $request->name;
            $data['sender_email'] = $request->email;
            $data['data'] = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message_text' => $request->message_text,
            ];


            $sendEmailDto = SendEmailDto::fromCollection($data);
            SendMail::dispatch($sendEmailDto);

            return responseBuilder()->success(__('Contact Email Sent Successfully'));
        } catch (\Exception $e) {
            return responseBuilder()->success($e->getMessage());
        }
    }
}
