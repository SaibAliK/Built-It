<?php

namespace App\Http\Controllers\Admin;

use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\UserRepository;
use App\Models\Product;
use App\Http\Libraries\DataTable;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductsController extends Controller
{

    protected CategoryRepository $categoryRepository;
    protected ProductRepository $productRepository;
    protected UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct('adminData', 'admin');
        $this->categoryRepository = new CategoryRepository();
        $this->breadcrumbTitle = 'Products';
        $this->userRepository = new UserRepository();
        $this->productRepository = new ProductRepository();
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
    }

    public function index(Request $request)
    {
        $columns = [
            ['db' => 'id', 'dt' => 'id'],
            ['db' => 'supplier_name', 'dt' => 'supplier_name'],
            ['db' => 'email', 'dt' => 'email'],
            ['db' => 'city_id', 'dt' => 'city_id'],
            ['db' => 'rating', 'dt' => 'rating'],
            ['db' => 'user_type', 'dt' => 'user_type'],
            ['db' => 'is_id_card_verified', 'dt' => 'is_id_card_verified'],
        ];
        $type = 'supplier';
        $store = $this->userRepository->adminDataTable($columns, $type);
        $categories = $this->categoryRepository->all(true);
        $subCategories = $this->categoryRepository->all(false);
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => 'Manage Products'];
        return view('admin.products.index', ['stores' => $store, 'categories' => $categories, 'subcategories' => $subCategories]);
    }

    public function all()
    {
        $columns = [
            ['db' => 'id', 'dt' => 'id'],
            ['db' => 'price', 'dt' => 'price'],
            ['db' => 'name', 'dt' => 'name'],
            ['db' => 'user_id', 'dt' => 'user_id'],
            ['db' => 'category_id', 'dt' => 'category_id'],
            ['db' => 'subcategory_id', 'dt' => 'subcategory_id'],
            ['db' => 'product_type', 'dt' => 'product_type'],
            ['db' => 'created_at', 'dt' => 'created_at'],
            ['db' => 'updated_at', 'dt' => 'updated_at'],
        ];
        $count = 1;
        DataTable::init(new Product(), $columns);
        DataTable::where('product_type', '=', 'product');
        DataTable::whereHas('category');
        DataTable::whereHas('subCategory');
        DataTable::whereHas('store');
        DataTable::with('category');
        DataTable::with('subCategory');
        DataTable::with('store');
        $title = \request('datatable.query.title', '');
        $store_id = \request('datatable.query.stores_name', '');
        $category_id = \request('datatable.query.category_id', '');
        $product_type = \request('datatable.query.product_type12', '');


        if (!empty($title)) {
            DataTable::where('name', 'like', '%' . $title . '%');
        }

        if (!empty($product_type == 1)) {
            DataTable::where('product_type', '=', 'product');
        }
        if (!empty($product_type == 2)) {
            DataTable::where('product_type', '=', 'offer');
        }

        if (!empty($category_id)) {
            DataTable::whereHas('category', function ($query) use ($category_id) {
                $query->where('category_id', $category_id);
            });
        }
        if (!empty($store_id)) {
            DataTable::where('user_id', '=', intval($store_id));
        }
        DataTable::whereIn('product_type', ['offer', 'product']);;
        $product = DataTable::get();
        $dateFormat = config('settings.date-format');
        $start = 1;
        if ($product['meta']['start'] > 0 && $product['meta']['page'] > 1) {
            $start = $product['meta']['start'] + 1;
        }

        $count = $start;
        if (sizeof($product['data']) > 0) {
            foreach ($product['data'] as $key => $data) {

                $product['data'][$key]['id'] = $count++;
                $product['data'][$key]['title'] = $data['name']['en'];
                $product['data'][$key]['category'] = '';
                $product['data'][$key]['store'] = '';
                $product['data'][$key]['created_at'] = Carbon::createFromTimestamp($data['created_at'])->format($dateFormat);
                $product['data'][$key]['updated_at'] = Carbon::createFromTimestamp($data['updated_at'])->format($dateFormat);

                $product['data'][$key]['actions'] =
                    '<a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete-record-button" href="javascript:{};" data-url="' . route('admin.dashboard.products.destroy', $data['id']) . '" title="Delete"><i class="fa fa-fw fa-trash-o"></i></a>';
                if ($data['store']['supplier_name'] !== null) {
                    $product['data'][$key]['store'] = $data['store']['supplier_name']['en'];
                } else {
                    $product['data'][$key]['store'] = 'N/A';
                }

                if ($data['category']['name'] !== null || $data['subCategory']['name'] !== null) {
                    $product['data'][$key]['category'] = $data['category']['name']['en'] . "/" . $data['subCategory']['name']['en'];
                } else {
                    $product['data'][$key]['category'] = 'N/A';
                }

                $product['data'][$key]['price'] = $product['data'][$key]['price'] . ' SAR ';

            }
            $data = response($product);
        } else {
            $data = response($product);
        }
        return $data;
    }

    public function destroy($id)
    {
        try {
            $this->productRepository->adminDelete($id);
            return response(['msg' => 'Product deleted']);
        } catch (\Exception $e) {
            return response(['msg' => $e->getMessage()]);
        }
    }
}
