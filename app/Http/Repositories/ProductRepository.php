<?php


namespace App\Http\Repositories;
//use App\Events\CartChangeNotifications;
use App\Actions\AddProduct;
use App\Http\Libraries\DataTable;
use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Jobs\ProductDiscountExpiry;
use App\Models\UserFeaturedSubscription;
use Exception;
use App\Jobs\ExpireOffer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProductRepository extends Repository
{
    protected UserFeaturedSubscriptionRepository $userFeaturedSubscriptionRepository;
    protected CategoryRepository $categoryRepository;
    protected ProductImageRepository $imageRepository;
    public AddProduct $addProduct;

    public function __construct()
    {
        $this->setModel(new Product());
        $this->addProduct = new AddProduct();
        $this->categoryRepository = new CategoryRepository();
        $this->userFeaturedSubscriptionRepository = new UserFeaturedSubscriptionRepository();
        $this->imageRepository = new ProductImageRepository();
    }

    public function all($params, $calledFrom = "search", $isPaginateSet = null)
    {
        $query = $this->getModel()->query();
        $query = $query->where('deleted_at', null);
        $relations = $this->getRelations();

        $selectStore = function ($query) {
            $query->select(['id', 'user_type', 'supplier_name', 'is_id_card_verified']);
        };

        if (isset($params['store_type'])) {
            $storeType = $params['store_type'];
            $selectStore = function ($query) use ($storeType) {
                $query->select(['id', 'type', 'supplier_name', 'is_id_card_verified']);
            };
            $query->whereHas('store', function ($q) use ($storeType) {
                $q->where('user_type', $storeType)->whereHas('storeSubscription', function ($q) {
                    $q->where('is_expired', 0);
                });
            });
        }


        if (isset($params['area_id']) && $params['area_id'] !== '' && $params['area_id'] != null) {
            $ss_data = $params['area_id'];
            $query->whereHas('store', function ($q) use ($ss_data) {
                $q->whereHas('coveredAreas', function ($q) use ($ss_data) {
                    $q->where('area_id',  $ss_data);
                });
            });
        }

        if (isset($params['is_id_card_verified'])) {
            $trade_license = $params['is_id_card_verified'];
            $query->whereHas('store', function ($q) use ($trade_license) {
                $q->where('is_id_card_verified', 1)->where('is_active', 1)->where('is_verified', 1)->whereHas('storeSubscription', function ($q) {
                    $q->where('is_expired', 0);
                });
            });
        }

//        $relations = ['reviews'];
        $relations['store'] = $selectStore;

        $this->setRelations($relations);

        if (isset($params['keyword']) && $params['keyword'] != '') {
            $query->where('name', 'like', '%' . $params['keyword'] . '%');
        }


        if (isset($params['store_id'])) {
            $query->where('user_id', $params['store_id']);
        }

        if (isset($params['store_id_store'])) {
            $query->where('user_id', $params['store_id_store']);
        }

        if (isset($params['category_id']) && $params['category_id'] != '0') {
            $query->where('category_id', $params['category_id']);
        }

        if (isset($params['subcategory_id']) && $params['subcategory_id'] != '0') {
            $query->where('subcategory_id', $params['subcategory_id']);
        }

        if (isset($params['rating'])) {
            $query->where('average_rating', $params['rating']);
        }

        if (isset($params['price'])) {
            $query->where('discounted_price', '=', $params['price']);
        }

        if (isset($params['rate_high_to_low'])) {
            $query->where('average_rating', 'DESC');
        }

        if (isset($params['rate_low_to_high'])) {
            $query->where('average_rating', 'ASC');
        }


        if (isset($params['a_to_z'])) {
            $query->orderBy('slug', 'ASC');
        }

        if (isset($params['most_selling'])) {
            $query->where(function ($q) {
                $q->where('status', 'approved')
                    ->orWhere('status', 'null');
            })->orderBy('average_rating', 'DESC');
        }

        if (isset($params['latest'])) {
            $query->where(function ($q) {
                $q->where('status', 'approved')
                    ->orWhere('status', 'null');
            })->orderBy('created_at', 'DESC');
        }

        if (isset($params['z_to_a'])) {
            $query->orderBy('slug', 'DESC');
        }

        if (isset($params['high_to_low'])) {
            $query->orderBy('discounted_price', 'DESC');
        }

        if (isset($params['low_to_high'])) {
            $query->orderBy('discounted_price', 'ASC');
        }

        $checkCategories = function ($query) use ($params) {
            $query->where('category_id', $params['category']);
        };

        if (isset($params['category']) && $params['category'] !== '') {
            $query->whereHas('categories', $checkCategories);
        }

        if (isset($params['subcategory']) && $params['subcategory'] !== '') {
            $categoriesArray = [$params['category'], $params['subcategory']];
            $query->whereHas('categories', function ($q) use ($categoriesArray) {
                $q->whereIn('categories.id', $categoriesArray);
            }, '=', count($categoriesArray));
        }

        if (isset($params['all'])) {
            $query->where(function ($q) {
                $q->where('status', 'approved')
                    ->orWhere('status', 'null');
            });
        }

        if (isset($params['all_for_store'])) {
            $query->where('deleted_at', null);
        }

        if (isset($params['categoriesWhereHas']) && $params['categoriesWhereHas'] !== '') {
            $query->whereHas('category');
        }

        if (isset($params['subCategoryWhereHas']) && $params['subCategoryWhereHas'] !== '') {
            $query->whereHas('subCategory');
        }

        if (isset($params['product_type'])) {
            $query->whereIn('product_type', $params['product_type']);
        }

        if (isset($params['product_type']) && isset($params['status'])) {
            $query->where('status', $params['status']);
        }


        if (isset($params['latitude']) && $params['latitude'] > 0 && isset($params['longitude']) && $params['longitude'] > 0) {
            $distance = config("settings.nearby_radius", 20); //km
            $haversine = '( 6367 * acos( cos( radians(' . $params['latitude'] . ') )* cos( radians( latitude ) ) *cos( radians( longitude ) - radians(' . $params['longitude'] . ') ) + sin( radians(' . $params['latitude'] . ') )* sin( radians( latitude ) ) ) )';
            $stores = User::selectRaw("id,{$haversine} AS distance")->whereRaw("{$haversine} < ?", [$distance])->whereHas('products')->orderBy('distance', 'ASC')->pluck('id');
            $query->whereIn('user_id', $stores);
        }

        if (isset($params['favorite'])) {
            $query->whereHas('favorites', function ($q) use ($params) {
                $q->where('user_id', $params['user_id']);
            });
        }

        if (isset($params['featured'])) {
            $query->whereHas('userFeaturedSubscriptions')->where('is_featured', 1);
        }

        if (isset($params['products_offers']) && isset($params['store_id'])) {
            $query->where(function ($q) {
                $q->where('approval_status', 'approved')
                    ->orWhere('approval_status', null);
            })->where('deleted_at', null);
            $user_id = $params['store_id'];
            $categories_ids = Category::where('parent_id', 0)->whereHas('products', function ($q) use ($user_id) {
                $q->where('user_id', $user_id)->where('product_type', 'add-on');
            })->pluck('id');

            $query->whereHas('categories', function ($q) use ($categories_ids) {
                $q->whereIn('categories.id', $categories_ids);
            });
        }

        if (isset($params['orderByFeatured'])) {
            $query->select(["*", \DB::raw("(SELECT COUNT(*) FROM user_featured_subscription WHERE products.id = user_featured_subscription.product_id AND user_featured_subscription.is_expired = 0  ) as is_featured1")]);
            $query->orderByDesc('is_featured1');
        }


        if ($isPaginateSet) {
            $products = $query->with($this->getRelations())->latest()->paginate($this->getPaginate());
        } else {
            $products = $query->with($this->getRelations())->latest()->get();
        }

        return $products;
    }

    public function uploadImage($request)
    {
        return uploadImage($request, 'products');
    }

    //Not Use, Move to Laravel Action
    public function save($params, $id)
    {
        DB::beginTransaction();
        try {
            $user = $this->getUser();
            if ($user->isSupplier()) {
                if (!$user->isCardImageVerified()) {
                    if ($id > 0) {
                        throw new Exception(__('Your trade license is not verified. You can not update any product until it is verified by admin.'));
                    } else {
                        throw new Exception(__('Your trade license is not verified. You can not add any products until it is verified by admin.'));
                    }
                }
            }

            $product = $this->addProduct->save($params, $id);

            if ($product->offer_percentage > 0) {
                $days = Carbon::now()->diffInDays(unixTODateformate($product->offer_expiry_date));
                ProductDiscountExpiry::dispatch($product->id)->delay(now()->addDays($days)->endOfDay());
            }

            if ($product->product_type == 'offer') {
                if ($product->offer_expiry_date && $product->offer_expiry_date != 0) {
                    $days = Carbon::now()->diffInDays(unixTODateformate($product->offer_expiry_date));
                    ExpireOffer::dispatch($product->id)->delay(now()->addDays($days)->endOfDay());
                }
            }

//            $cartRepository = new CartRepository();
//            $carts = $cartRepository->getModel()->where('product_id', $product->id)->get();
//            $cartUserId = [];
//
//            foreach ($carts as $cart) {
//                array_push($cartUserId, $cart->user_id);
//                $cart->delete();
//            }
//            foreach ($cartUserId as $id) {
//                sendNotification([
//                    'sender_id' => \Auth::check() ? auth()->id() : 1,
//                    'receiver_id' => $id,
//                    'extras->product_slug' => $product->slug,
//                    'title->en' => 'Product has been removed from your cart.',
//                    'title->ar' => __('Product has been removed from your cart.'),
//                    'description->en' => 'Product has been removed from your cart.',
//                    'description->ar' => __('Product has been removed from your cart.'),
//                    'action' => 'CART'
//                ]);
//                event(new CartChangeNotifications($cartRepository->getModel()->whereHas('product')->where('user_id', $id)->sum('quantity'), $id));
//            }


            if ($params->featured_package_id && $params->featured_package_id != '') {
                $this->userFeaturedSubscriptionRepository->setProduct($params->featured_package_id, $product->id);
            }

            if ($params->id && $params->id != 0) {
                $productImages = $product->images()->get();
                if (count($productImages) > 0) {
                    foreach ($productImages as $image) {
                        $image->delete();
                    }
                }
            }

            foreach ($params->product_images as $image) {
                $this->imageRepository->save($image, $product->id);
            }

            DB::commit();
            return $product;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function getDataTableOffer($columns)
    {
        DataTable::init(new Product(), $columns);
        DataTable::with('store');
        $dateFormat = config('settings.date-format');
        $store_type = \request('datatable.query.type', '');

        if (!empty($store_type)) {
            DataTable::whereHas('store', function ($query) use ($store_type) {
                $query->where('store_type', 'like', '%' . $store_type . '%');
            });
        }

        DataTable::where('product_type', '=', 'offer');
        $offers = DataTable::get();
        $start = 1;
        if ($offers['meta']['start'] > 0 && $offers['meta']['page'] > 1) {
            $start = $offers['meta']['start'] + 1;
        }
        $count = $start;

        if (sizeof($offers['data']) > 0) {
            foreach ($offers['data'] as $key => $data) {
                $offers['data'][$key]['id'] = $count++;
                $offers['data'][$key]['name'] = $data['name']['en'];

                if ($data['store'] !== null) {
                    $offers['data'][$key]['store_name'] = $data['store']['supplier_name']['en'] ?? "No name available";
                }

                $offers['data'][$key]['price'] = $offers['data'][$key]['price'] . ' SAR ';
                $offers['data'][$key]['offer_percentage'] = $data['offer_percentage'] . '%';
                $offers['data'][$key]['status'] = ucwords($data['status']);
                $offers['data'][$key]['actions'] = '<a href="' . route('admin.dashboard.offers.change.status', ['id' => $data['id'], 'status' => 'approved']) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Approve Offer"><i class="fa fa-check-square"></i></a>' .
                    '<a href="' . route('admin.dashboard.offers.change.status', ['id' => $data['id'], 'status' => 'cancelled']) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Reject Offer"><i class="fa fa-ban"></i></a>';
                $offers['data'][$key]['created_at'] = Carbon::createFromTimestamp($data['created_at'])->format($dateFormat);
                $offers['data'][$key]['updated_at'] = Carbon::createFromTimestamp($data['updated_at'])->format($dateFormat);
            }
        }
        return $offers;
    }

    public function changeStatus($data)
    {
        $this->getModel()->where('id', $data['id'])->update(['status' => $data['status']]);
    }

    public function get($id = null, $storeId = null)
    {
        try {
            $query = $this->getModel()->query();
            if (!is_null($id)) {
                $query->where('id', $id);
            }

            if (!is_null($storeId)) {
                $query->where('user_id', $storeId);
            }
            return $query->select($this->getSelect())->with($this->getRelations())->first();
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function delete($id, $where = null, $fromAdmin = false, $adminId = null)
    {
        $product = $this->getModel()->find($id);
        $stadiumImages = $product->images()->get();
        if (count($stadiumImages) > 0) {
            foreach ($stadiumImages as $image) {
                $image->delete();
            }
        }
//        app('App\Http\Repositories\CartRepository')->delete($product->id, false, true);
        $product->delete();
        return true;
    }

    public function adminDelete($id, $where = null, $fromAdmin = false, $adminId = null)
    {
        if ($where != null) {
            $product = $this->getModel()->where($where)->first();
        } else {
            $product = $this->getModel()->find($id);
        }
        if (!is_null($product)) {
            $product->delete();
        }
        return true;
    }
}
