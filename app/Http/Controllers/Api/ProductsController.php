<?php

namespace App\Http\Controllers\Api;

use App\Http\Repositories\ProductRepository;

//use App\Http\Requests\CheckOutRequest;
//use App\Http\Requests\GymSubscriptionRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ProductsController extends Controller
{


    protected ProductRepository $productRepository;

    public function __construct()
    {
        $this->middleware('jwt.verify')->only('getStoreProducts', 'setFavourite', 'setUnFavourite', 'detail', 'favouriteProducts', 'SearchProduct', 'gymPackageSubscription', 'getDetail');
        $this->productRepository = new ProductRepository;
    }

    public function SearchProduct(Request $request)
    {
        if ($request->product_type) {
            $request->merge(['product_type' => [$request->product_type]]);

            if ($request->product_type == 'offer') {
//                $request->merge(['attributeWhereHas' => true]);
            }
        }

        if ($request->category || $request->products_offers) {
//            $request->merge(['attriCheck' => true]);
        }
//
        $request->merge([
            'categoriesWhereHas' => 1,
            'user_location_data' => $request->get('user_location_data'),
            'orderByFeatured' => true,
        ]);

        $this->productRepository->setPaginate(10);
        $this->productRepository->setRelations(['category']);


        $products = $this->productRepository->all($request->all(), isset($request->called_from) ? $calledFrom = 'dashboard' : 'search', true);

        foreach ($products as $product) {
            unset($product->branch_ids);
        }
        if ($products) {
            return responseBuilder()->success(__(' Products'), $products);
        }
        return responseBuilder()->error(__('SomeThing Went Wrong'));
    }

    /*public function getProductsByLocation(Request $request){
        $lat = $request->get('latitude', 0);
        $long = $request->get('longitude', 0);
        if ($request->get('latitude') && $request->get('longitude')){
            $query = Product::query();
            $select = ['name','price','latitude','meal_id','longitude','id','slug','user_id','image',\DB::raw('concat("' . url('/') . '/",image)as image')];
            $selectStore = function ($query){
                $query->select('id','type','chef_name');
            };
            $distance = config("settings.nearby" , 20); //km
            $haversine  = '( 6367 * acos( cos( radians('.$lat.') )* cos( radians( latitude ) ) *cos( radians( longitude ) - radians('.$long.') ) + sin( radians('.$lat.') )* sin( radians( latitude ) ) ) )';
            $products = $query->select($select)->selectRaw(" {$haversine} AS distance")->whereRaw("{$haversine} < ?", [$distance])->whereHas('store')->with(['store'=>$selectStore])->orderBy('distance', 'ASC')->limit(8)->get();
        }else{
            $products = Product::with('store')->latest()->limit(8)->get();
        }

        foreach($products as $key => $product){
            $product->price = json_decode(json_encode(getPriceObject($product->price)),true);
        }
        if ($products){
            return responseBuilder()->success(__(' Products'),$products);
        }
        return  responseBuilder()->error(__('SomeThing Went Wrong'));
    }*/

    public function detail(Request $request, $slug)
    {

        $relations = ['attributes', 'store', 'images', 'categories', 'children.store'];
        $this->productRepository->setRelations($relations);
        $product = $this->productRepository->get(null, null, $slug, null, $request);
        $productReview = paginate($product->reviews, $request->url());
        $product->formatted_expiry_date = convertDateFormat('d-M-Y', $product->expiry_date);
        $product->formatted_product_expiry_date = false;
        if (!is_null($product->product_expiry_date)) {
            $product->formatted_product_expiry_date = convertDateFormat('d-M-Y', $product->product_expiry_date);
        }

        $branch_ids = [];
        if ($product->branch_ids != '') {
            $product->branch_ids = explode(',', $product->branch_ids);
        } else {
            $product->branch_ids = $branch_ids;
        }

        if ($product) {
            return responseBuilder()->success('Product Details', $product->toArray(), 'reviews', $productReview);
        }
        return responseBuilder()->error('something went wrong');
    }

    public function setFavourite(ProductRequest $request)
    {
        try {
            $this->favoriteProductRepository->save($request);
            return responseBuilder()->success(__('Favourite list updated.'));

        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());

        }
    }

    public function favouriteProducts()
    {
        try {
            $this->productRepository->setPaginate(6);
            $products = $this->productRepository->all(['favorite' => true], 'favorite');
            foreach ($products as $product) {
                $branch_ids = [];
                if ($product->branch_ids != '') {
                    $product->branch_ids = explode(',', $product->branch_ids);
                } else {
                    $product->branch_ids = $branch_ids;
                }
            }
            return responseBuilder()->success(__('Favourite products.'), $products);
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());

        }
    }

    public function getDetail(Request $request, $slug)
    {
        $relations = ['attributes', 'store', 'images', 'categories' => function ($q) {
            $q->where('parent_id', 0);
        }, 'children.store', 'reviews.user'];
        $this->productRepository->setRelations($relations);
        $product = $this->productRepository->get(null, null, $slug, null, $request);
        if ($product) {
            $store = $product->store;
            $relatedProducts = [];
            if (isset($product->categories)) {
                $categories = $product->categories->pluck('id');
                $ss_data = $request->get('user_location_data');
                $relatedProducts = Product::where(['user_id' => $store->id])->where('id', '!=', $product->id)->whereHas('categories', function ($query) use ($categories) {
                    $query->whereIn('categories.id', $categories);
                })->whereHas('areas', function ($q) use ($ss_data) {
                    $q->where('cities.id', $ss_data['area_id'])->where('cities.parent_id', $ss_data['city_id']);
                })->withCount('deletedAttributes as attributes_count')->having('attributes_count', 0)->with('store')->get();
//                $relatedProducts = $this->productRepository->all(['category' => $product->categories->first()->id]);
            }
        }
        $productReview = paginate($product->reviews, $request->url());
        unset($product->reviews);
        $product->formatted_expiry_date = convertDateFormat('d-M-Y', $product->expiry_date);
        $product->formatted_production_date = convertDateFormat('d-M-Y', $product->production_date);
        $branch_ids = [];

        if (!empty($product->branch_ids)) {
            $product->branch_ids = explode(',', $product->branch_ids);
        } else {
            $product->branch_ids = $branch_ids;
        }
        if ($product) {
            return responseBuilder()->success('Product Details', ['product' => $product->toArray(), 'reviews' => $productReview, 'relatedProducts' => $relatedProducts]);
        }
        return responseBuilder()->error('something went wrong');
    }


    public function gymPackageSubscription(GymSubscriptionRequest $request)
    {
        $response = app('App\Http\Repositories\CheckOutRepository')->gymPackageSubscription($request);
        if (!empty($response)) {
            return responseBuilder()->success('Gym Subscribed', ['data' => $response]);
        }
    }


}
