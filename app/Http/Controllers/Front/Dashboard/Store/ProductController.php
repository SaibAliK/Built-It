<?php

namespace App\Http\Controllers\Front\Dashboard\Store;

//use App\Events\CartChangeNotifications;
use App\Actions\AddProduct;
use App\Http\Controllers\Controller;
use App\Http\Dtos\AddProductDto;
use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\UserFeaturedSubscriptionRepository;
use App\Http\Repositories\UserRepository;
use Exception;

//use App\Http\Repositories\CartRepository;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected UserRepository $userRepository;
    protected CategoryRepository $categoryRepository;
    protected ProductRepository $productRepository;
//    protected CartRepository $cartRepository;
    public AddProduct $addProduct;
    protected UserFeaturedSubscriptionRepository $userFeaturedSubscriptionRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->productRepository = new ProductRepository();
//        $this->cartRepository = new CartRepository();
        $this->addProduct = new AddProduct();
        $this->userFeaturedSubscriptionRepository = new UserFeaturedSubscriptionRepository();
        $this->breadcrumbs[route('front.dashboard.index')] = ['title' => __('Home')];
    }

    public function all(Request $request)
    {
        try {
            $storeId = $this->productRepository->getUser()->id;

            $request->merge([
                'store_id_store' => $storeId,
                'categoriesWhereHas' => true,
                'subCategoryWhereHas' => true,
                'is_id_card_verified' => true,
            ]);

            if (!isset($request->all_for_store) && !isset($request->featured) && !isset($request->offer)) {
                $request->merge([
//                    'all_for_store' => true,
                    'orderByFeatured' => true
                ]);
            }

            if (isset($request->featured)) {
                $request->merge([
                    'featured' => true
                ]);
            }

            if (isset($request->offer) || isset($request->offerStatus)) {
                $request->merge([
                    'product_type' => ['offer'],
                    'status' => $request->offerStatus
                ]);
            }

            $this->breadcrumbTitle = __('Manage Products');
            $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => __('Manage Products')];
            $this->productRepository->setPaginate(6);

            $this->productRepository->setRelations([
                'category:id,parent_id,name'
            ]);

            $products = $this->productRepository->all($request->all(), 'search', true);
            return view('front.dashboard.product.manage_product', ['products' => $products]);
        } catch (\Exception $e) {
            return redirect()->back()->with('err', $e->getMessage())->withInput();
        }
    }

    public function create(Request $request, $id)
    {
        $user = auth()->user();
        $this->breadcrumbs[route('front.dashboard.product.index')] = ['title' => __('Manage Products')];

        $this->categoryRepository->setRelations(['subCategories']);
        $categories = $this->categoryRepository->all(true, true);

        $subscriptions = $this->userFeaturedSubscriptionRepository->all();
        $subscriptions = $subscriptions->map(function ($item) {
            return $item->getFormattedModel();
        });

        $product = $this->productRepository->getModel();

        if ($id == 0) {
            $productId = 0;
            $this->breadcrumbTitle = __('Add Product');
            $this->breadcrumbs['javascript:{};'] = ['title' => __('Add Product')];
        } else {
            $productId = $id;
            $this->breadcrumbTitle = __('Edit Product');
            $this->breadcrumbs['javascript:{};'] = ['title' => __('Edit Product')];
        }

        return view('front.dashboard.product.edit-product',
            [
                'productId' => $productId,
                'categories' => $categories,
                'product' => $product,
                'subscriptions' => $subscriptions
            ]
        );
    }

    function save(ProductRequest $request, $id)
    {
        try {

            $user = auth()->user();
            if ($user->isSupplier()) {
                if (!$user->isCardImageVerified()) {
                    if ($id > 0) {
                        throw new Exception(__('Your trade license is not verified. You can not update any product until it is verified by admin.'));
                    } else {
                        throw new Exception(__('Your trade license is not verified. You can not add any products until it is verified by admin.'));
                    }
                }
            }

            $addProductDto = AddProductDto::fromRequest($request);
            // call Add Product Action
            $product = $this->addProduct->handle($addProductDto, $id);
            //  $product = $this->productRepository->save($addProductDto, $id);

            if ($request->id != null) {
                session()->flash('status', __('Product updated successful.'));
            } else {
                session()->flash('status', __('Product created successful.'));
            }
            return redirect(route('front.dashboard.product.index'));

        } catch (\Exception $e) {
            return redirect()->back()->with('err', $e->getMessage())->withInput();
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $user = auth()->user();
            if ($user->isSupplier()) {
                if (!$user->isCardImageVerified()) {
                    return redirect(route('front.dashboard.product.index'))->with('err', __('Your ID Card is not verified by the admin. You can not edit any Product until your ID is verified by the admin.'));
                }
            }

            $this->breadcrumbTitle = __('Edit Product');
            $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => __('Edit Product')];
            $categories = $this->categoryRepository->all(true, true);
            $subscriptions = $this->userFeaturedSubscriptionRepository->all();
            $subscriptions = $subscriptions->map(function ($item) {
                return $item->getFormattedModel();
            });

            $this->productRepository->setRelations([
                'category:id,name',
                'images:id,file_path,file_default,file_type,product_id',
            ]);
            $product = $this->productRepository->get($id);

            if ($product->user_id != $user->id) {
                return redirect(route('front.dashboard.product.index'))->with('err', __('You are not allowed to edit this product'));
            }
            return view('front.dashboard.product.edit-product', get_defined_vars());
        } catch (\Exception $e) {
            return redirect()->back()->with('err', $e->getMessage())->withInput();
        }
    }

}
