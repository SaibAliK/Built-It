<?php

namespace App\Http\Controllers\Api\Store;

//use App\Http\Repositories\CartRepository;
use App\Actions\AddProduct;
use App\Http\Dtos\AddProductDto;
use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Requests\Image;
use App\Http\Requests\ProductRequest;
use App\Http\Repositories\UserFeaturedSubscriptionRepository;
//use App\Models\Advertisement;
use App\Models\Product;
use Exception;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected ProductRepository $productRepository;
    protected UserFeaturedSubscriptionRepository $userFeaturedSubscriptionRepository;
    protected UserRepository $userRepository;
    protected AddProduct $addProduct;
    protected CategoryRepository $categoryRepository;
//    protected CartRepository $cartRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository;
        $this->userRepository = new UserRepository();
        $this->categoryRepository = new CategoryRepository();
        $this->addProduct = new AddProduct();
//        $this->cartRepository = new CartRepository();
        $this->userFeaturedSubscriptionRepository = new UserFeaturedSubscriptionRepository();
    }

    public function all(Request $request)
    {
        try {

            $storeId = $this->productRepository->getUser()->id;
            $request->merge([
                'store_id' => $storeId,
                'categoriesWhereHas' => true,
                'subCategoryWhereHas' => true,
                'is_id_card_verified' => true,
            ]);

            if (!isset($request->all) && !isset($request->featured) && !isset($request->offer)) {
                $request->merge([
                    'all_for_store' => true,
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

            $this->productRepository->setRelations([
                'category:id,parent_id,name'
            ]);

            $products = $this->productRepository->all($request->all(), 'search', true);

            return responseBuilder()->success(__('Store products.'), $products);

        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());

        }
    }

    public function categories(Request $request)
    {
        try {
            $categories = $this->categoryRepository->all(true);
            return responseBuilder()->success(__('Categories'), $categories);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function listOfPurchasePackage(Request $request)
    {
        try {
            $subscriptions = $this->userFeaturedSubscriptionRepository->all();
            return responseBuilder()->success(__('Featured Subscriptions Package'), $subscriptions);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            $user = auth()->user();
            if ($user->isSupplier()) {
                if (!$user->isCardImageVerified()) {
                    return responseBuilder()->error(__('Your ID Card is not verified by the admin. You can not edit any Product until your ID is verified by the admin.'), $user);
                }
            }

            $this->productRepository->setRelations([
                'store:id,user_type,supplier_name,city_id,is_id_card_verified,address,latitude,longitude,image,rating',
                'category:id,name',
                'subCategory:id,name',
                'userFeaturedSubscriptions:id,user_id,product_id,package,is_expired,payment_status,first_name,last_name,aed_price,currency',
                'images:id,file_path,file_default,file_type,product_id',
            ]);
            $product = $this->productRepository->get($id);
            return responseBuilder()->success(__('Product details.'), $product->toArray());
        } catch (\Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function save(ProductRequest $request, $id)
    {
        try {
            $addProductDto = AddProductDto::fromRequest($request);
            $product = $this->addProduct->handle($addProductDto, $id);

            if ($request->id != null) {
                return responseBuilder()->success(__('Product updated successful.'));
            } else {
                return responseBuilder()->success(__('Product created successful.'));
            }

            // TODO::will Do when Add to cart module start
            // // if ($id > 0) {
            // $this->cartRepository->delete(null, null, $id);
            //  }
            // return responseBuilder()->success(__('Product saved.', ['Stadium' => $product]));

        } catch (\Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function get($id)
    {
        try {
            $relations = ['images', 'areas', 'categories', 'userFeaturedSubscriptions'];
            $this->productRepository->setRelations($relations);
            $product = $this->productRepository->get($id, null, null, null, null);
            if (!$product) {
                return responseBuilder()->error(__('Product is no longer available'));
            }
            $product->expiry_date = convertDateFormat('Y-m-d', $product->expiry_date);
            $product->product_expiry_date = convertDateFormat('Y-m-d', $product->product_expiry_date);

            if ($product->categories->isNotEmpty()) {
                foreach ($product->categories as $category) {
                    if ($category->parent_id == 0) {
                        $product->category = $category;
                    } else {
                        $product->subcategory = $category;
                    }
                }
            } else {
                $product->category = [];
                $product->subcategory = [];
            }

            if (count($product->categories) < 0) {
                return responseBuilder()->error(__('Product category has been deleted. Upload new information'), $product->toArray());
            } else {
                return responseBuilder()->success(__('Product details.'), $product->toArray());
            }
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }

    }

    public function delete(Request $request)
    {
        try {
            $this->productRepository->delete($request->id);
            return responseBuilder()->success(__('Product delete successfully.'));

        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }

    }

    public function uploadImage(Image $request)
    {
        return $this->productRepository->uploadImage($request);
    }

}
