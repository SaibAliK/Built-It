<?php

namespace App\Http\Controllers\Front;

use App\Http\Dtos\AdminDto;
use App\Http\Libraries\Uploader;
use App\Http\Repositories\AdminRepository;
use App\Http\Repositories\ArticleRepository;
use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\FaqRepository;
use App\Http\Repositories\GalleryRepository;
use App\Http\Repositories\InfoPagesRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\SubscriptionRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\AdminRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageRequest;
use App\Models\Category;
use App\Models\Review;
use App\Traits\EMails;
use Exception;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    use EMails;

    public InfoPagesRepository $pagesRepository;
    public CategoryRepository $categoriesRepository;
    public ArticleRepository $articleRepository;
    public FaqRepository $faqRepository;
    public GalleryRepository $galleryRepository;
    public CategoryRepository $categoryRepository;
    public UserRepository $userRepository;
    public ProductRepository $productRepository;
    public SubscriptionRepository $subscriptionRepository;

    public function __construct(InfoPagesRepository $pagesRepository)
    {
        parent::__construct();
        $this->pagesRepository = $pagesRepository;
        $this->articleRepository = new ArticleRepository();
        $this->faqRepository = new FaqRepository();
        $this->galleryRepository = new GalleryRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->categoriesRepository = new CategoryRepository();
        $this->userRepository = new UserRepository();
        $this->productRepository = new ProductRepository();
        $this->subscriptionRepository = new SubscriptionRepository();
    }

    public function index(Request $request)
    {
        $ss_data = session()->get('area_id');
        $request->merge(['area_id' => $ss_data]);

        $request->merge([
            'categoriesWhereHas' => true,
            'subCategoryWhereHas' => true,
            'is_id_card_verified' => true,
            'featured' => true
        ]);

        $this->productRepository->setRelations([
            'store:id, user_type, supplier_name, is_id_card_verified',
            'images'
        ]);

        $this->productRepository->setPaginate(9);

        $products = $this->productRepository->all($request->all(), 'search', true);
        foreach ($products as $item) {
            $item->getFormattedModel();
        }


        $categories = $this->categoryRepository->all(true);
        $this->userRepository->setPaginate(6);
        $stores = $this->userRepository->storeFilter(null, $request);
        foreach ($stores as $item) {
            $item->getFormattedModel();
        }
        $locations = [];
        foreach ($stores as $supplier) {
            $location = [
                $supplier->supplier_name,
                $supplier->latitude,
                $supplier->longitude,
            ];
            array_push($locations, $location);
        }

        if (count($locations) < 1) {
            $locations[0][0]['en'] = "";
            $locations[0][0]['ar'] = "";
            $locations[0][1] = config('settings.latitude');
            $locations[0][2] = config('settings.longitude');
        }


        return view('front.home.index', [
            'categories' => $categories,
            'stores' => $stores,
            'locations' => $locations,
            'products' => $products,
        ]);
    }

    public function error404()
    {
        return view('front.home.404');
    }

    public function emailSubscribe(Request $request)
    {

        $this->validate(request(), [
            'email_for_subscribe' => 'required'
        ]);
        $this->subscriptionRepository->save($request, $id = 0);
        return redirect()->back()->with('status', __('Email Subscription Successfully'));
    }

    public function contactEmail(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required',
                'subject' => 'required',
                'email' => 'required|email',
                'message_text' => 'required',
            ]);

            $data = $request->all();
            $data['receiver_email'] = config('settings.email');
            $data['receiver_name'] = config('settings.company_name');
            $data['sender_name'] = config('settings.company_name');
            $data['sender_email'] = $data['email'];

            $email = $this->sendMail($data, 'emails.user.contact_us', strtoupper($data['subject']), $data['receiver_email'], $data['sender_email']);

            if ($email !== 'Success') {
                return redirect()->back()->with('err', __('The Email You Have Entered Is Spam Email'));
            } else {
                return redirect()->back()->with('status', __('Contact Email Sent Successfully'));
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('err', $e->getMessage())->withInput();
        }
    }

    public function SaveUserData2(Request $request)
    {

        if ($request->id) {

            session()->put('area_id', $request->id);
        } else {
            session()->put('area_id', null);
        }

        return true;
    }

    public function products(Request $request)
    {
        try {
            $ss_data = session()->get('area_id');
            $request->merge(['area_id' => $ss_data]);
            $this->breadcrumbs[url(route('front.index'))] = ['title' => __('home')];
            $this->breadcrumbTitle = __('All Products');
            $this->breadcrumbs['javascript: {};'] = ['title' => __('All Products')];
            $categories = $this->categoryRepository->all(true,true);

            if (isset($request->category_id)) {
                $sub_categories = Category::where('parent_id', $request->category_id)->get();
            }

            $suppliers = $this->userRepository->storeFilter(null, null);
            foreach ($suppliers as $item) {
                $item->getFormattedModel();
            }


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
                'images'
            ]);

            $this->productRepository->setPaginate(9);
            $products = $this->productRepository->all($request->all(), 'search', true);
            foreach ($products as $item) {
                $item->getFormattedModel();
            }

            return view('front.home.products', get_defined_vars());
        } catch (Exception $e) {
            return redirect(route('front.404'))->with('err', $e->getMessage());
        }
    }

    public function suppliers(Request $request)
    {
        try {
            $ss_data = session()->get('area_id');
            $request->merge(['area_id' => $ss_data]);
            $this->breadcrumbs[url(route('front.index'))] = ['title' => __('home')];
            $this->breadcrumbTitle = __('Suppliers');
            $this->breadcrumbs['javascript: {};'] = ['title' => __('Suppliers')];
            $this->userRepository->setPaginate(12);
            $suppliers = $this->userRepository->storeFilter(null, $request);
            foreach ($suppliers as $item) {
                $item->getFormattedModel();
            }

            return view('front.home.suppliers', get_defined_vars());
        } catch (Exception $e) {
            return redirect(route('front.404'))->with('err', $e->getMessage());
        }
    }

    public function suppliersDetail(Request $request, $id)
    {
        try {

            if (\Auth::check()) {
                $userId = $this->user->id;
                $this->userRepository->setRelations(['orderDetails' => function ($q) use ($userId) {
                    $q->with('orderItems.product')->whereHas('orders', function ($q) use ($userId) {
                        $q->where('user_id', '=', $userId)->where('status', '=', 'completed')->orderBy('created_at', 'desc');
                    });
                    $q->doesnthave('review');
                }]);
            }


            if (!isset($request->products) && !isset($request->reviews)) {
                $request->merge(['products' => true]);
            }
            $userId = 0;
            if (auth()->check() && auth()->user()->isUser()) {
                $userId = auth()->id();
            }

            $supplier = $this->userRepository->get($id)->getFormattedModel();

            $store_id = $supplier->id;
            $request->merge([
                'categoriesWhereHas' => true,
                'subCategoryWhereHas' => true,
                'is_id_card_verified' => true,
                'store_id' => $store_id,
                'all' => true
            ]);
            $this->productRepository->setPaginate(8);
            $products = $this->productRepository->all($request->all());
            foreach ($products as $item) {
                $item->getFormattedModel();
            }

            $reviews = Review::where('store_id', $id)->where('product_id', NUll)->orderBy('id', 'desc')->get();


            $this->breadcrumbs[url(route('front.index'))] = ['title' => __('home')];
            $this->breadcrumbTitle = translate($supplier->supplier_name);
            $this->breadcrumbs['javascript: {};'] = ['title' => __('Supplier Products')];

            return view('front.home.supplier_detail', get_defined_vars());
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect(route('front.404'))->with('err', $e->getMessage());
        }
    }

    public function productDetail(Request $request, $id)
    {
        try {
            $userId = 0;
            if (auth()->check() && auth()->user()->isUser()) {
                $userId = auth()->id();
            }
            if (\Auth::check()) {
                $userId = $this->user->id;
                $this->productRepository->setRelations(['category', 'subCategory', 'images', 'store', 'orderDetailItems' => function ($q) use ($userId) {
                    $q->whereHas('orderDetail', function ($q) use ($userId) {
                        $q->where('status', 'completed')->whereHas('order', function ($q) use ($userId) {
                            $q->where('user_id', $userId);
                        });
                    });
                    $q->doesnthave('review');
                }]);
            } else {
                $this->productRepository->setRelations([
                        'store:id,user_type,supplier_name,city_id,is_id_card_verified,address,latitude,longitude,image,rating',
                        'images:id,file_path,file_default,file_type,product_id',
                        'category:id,name',
                        'subCategory:id,name'
                    ]);
            }
            $product = $this->productRepository->get($id)->getFormattedModel();

            if ($product) {
                $store = $product->store;
                if ($store->isSupplier()) {
                    if (!$store->isCardImageVerified()) {
                        return redirect(route('front.404'))->with('err', __('Product is no longer available'));
                    }
                }
            } else {
                return redirect(route('front.404'));
            }

            $colors = [
                'heading_color' => $product->store->color->heading_color ?? '#020202',
                'text_color' => $product->store->color->text_color ?? '#000',
                'icons_color' => $product->store->color->icons_color ?? '#fefdfd',
                'background_color' => $product->store->color->background_color ?? '#ffffff',
            ];


            $this->breadcrumbs[url(route('front.index'))] = ['title' => __('home')];
            $this->breadcrumbTitle = translate($product->name);
            $this->breadcrumbs['javascript: {};'] = ['title' => __('Product Detail')];


            return view('front.home.product_detail', get_defined_vars());
        } catch (Exception $e) {
            return redirect(route('front.404'))->with('err', $e->getMessage());
        }
    }

    public function putSession(Request $request)
    {
        session()->put('latitude', $request->latitude);
        session()->put('longitude', $request->longitude);
        $test = session()->get('latitude');
        return $test;
    }

    public function supplierMapSearch(Request $request)
    {
        $this->userRepository->setPaginate(6);
        $stores = $this->userRepository->storeFilter(null, $request);

        $locations = [];
        foreach ($stores as $supplier) {
            $location = [
                $supplier->supplier_name,
                $supplier->latitude,
                $supplier->longitude,
            ];
            array_push($locations, $location);
        }
        return json_encode(['stores' => $stores, 'locations' => $locations]);
    }

    public function categories()
    {
        try {
            $this->breadcrumbs[url(route('front.index'))] = ['title' => __('home')];
            $this->breadcrumbTitle = __('Categories');
            $this->breadcrumbs['javascript: {};'] = ['title' => __('Categories')];
            $this->categoryRepository->setPaginate(6);
            $categories = $this->categoryRepository->all(true,false);
            return view('front.home.categories', ['categories' => $categories]);
        } catch (Exception $e) {
            return redirect(route('front.404'))->with('err', $e->getMessage());
        }
    }

    public function subcategory($id)
    {
        $this->categoriesRepository->setSelect([
            'id',
            'name',
        ]);
        $categories = $this->categoriesRepository->getSubCategories($id);
        return responseBuilder()->success('', $categories);
    }

    public function articles()
    {
        $this->breadcrumbs[url(route('front.index'))] = ['title' => __('home')];
        $this->breadcrumbTitle = __('Articles');
        $this->breadcrumbs['javascript: {};'] = ['title' => __('Articles')];
        $this->articleRepository->setPaginate(6);
        $articles = $this->articleRepository->all();
        return view('front.home.article', ['articles' => $articles]);
    }

    public function faqs()
    {
        $this->breadcrumbs[url(route('front.index'))] = ['title' => __('home')];
        $this->breadcrumbTitle = __("FAQ's");
        $this->breadcrumbs['javascript: {};'] = ['title' => __("FAQ's")];
        $this->faqRepository->setPaginate(6);
        $faqs = $this->faqRepository->all();
        return view('front.home.faqs', get_defined_vars());
    }

    public function gallery()
    {
        $this->breadcrumbs[url(route('front.index'))] = ['title' => __('home')];
        $this->breadcrumbTitle = __('Images Gallery');
        $this->breadcrumbs['javascript: {};'] = ['title' => __('Images Gallery')];
        $this->galleryRepository->setPaginate(10);
        $images = $this->galleryRepository->all_for_front();
        return view('front.home.gallery', ['images' => $images]);
    }

    public function articleDetail($slug)
    {
        $article = $this->articleRepository->get($slug);
        if (!is_null($article)) {
            $article_name = translate($article->name);
            $this->breadcrumbTitle = __($article_name);
            $this->breadcrumbs[] = ['url' => route('front.index'), 'title' => __('Home')];
            $this->breadcrumbs[] = ['url' => route('front.articles'), 'title' => __('Articles')];
            $this->breadcrumbs['javascript: {};'] = ['title' => __('Article Detail')];
            return view('front.home.article-detail', compact('article'));
        }
        return redirect(route('front.404'))->with('err', __('No record found'));
    }

    public function pages($slug)
    {
        try {
            if ($slug == "contact-us") {
                $this->breadcrumbs[url(route('front.index'))] = ['title' => __('home')];
                $this->breadcrumbTitle = __('Contact Us');
                $this->breadcrumbs['javascript: {};'] = ['title' => __('Contact Us')];
                return view('front.home.contact');
            }


            $pageData = $this->pagesRepository->getslug($slug);
            $this->breadcrumbs[url(route('front.index'))] = ['title' => __('home')];
            $this->breadcrumbTitle = __(translate($pageData->name));
            $this->breadcrumbs['javascript: {};'] = ['title' => translate($pageData->name)];

            if ($slug == config('settings.terms_and_conditions') || $slug == config('settings.privacy_policy') || $slug == config('settings.user_data_delete')) {
                return view('front.home.pages', ['page' => $pageData]);
            }


            if ($slug == config('settings.about_us')) {
                $this->breadcrumbTitle = __('About Us');
                $this->breadcrumbs['javascript: {};'] = ['title' => __('About Us')];
                $page = $this->pagesRepository->getslug(config('settings.about_us'));
                $mission_vision = $this->pagesRepository->getslug(config('settings.mission_and_vision'));
                if (!empty($page) && !empty($mission_vision)) {
                    return view('front.home.about-us', get_defined_vars());
                } else {
                    return redirect(route('front.404'))->with('err', __('No record found'));
                }
            }

            return view('front.home.pages', ['page' => $pageData]);
        } catch (Exception $e) {
            return redirect(route('front.404'))->with('err', $e->getMessage());
        }
    }

    public function change_currency($currency)
    {
        session()->put('currency', $currency);
        return redirect()->back();
    }

}
