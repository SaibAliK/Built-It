<?php

namespace App\Http\Controllers\Admin;

use App\Http\Repositories\AdminRepository;
use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Repositories\UserRepository;

//use App\Models\OrderDetailItems;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OffersController extends Controller
{
    protected ProductRepository $productRepository;
    protected CategoryRepository $categoryRepository;
    protected AdminRepository $adminRepository;
    protected UserRepository $userRepository;
    public $user;

    public function __construct()
    {
        parent::__construct('adminData', 'admin');
        $this->productRepository = new ProductRepository();
        $this->userRepository = new UserRepository();
        $this->adminRepository = new  AdminRepository();
        $this->user = new User();
        $this->categoryRepository = new CategoryRepository();
        $this->breadcrumbTitle = 'Offers';
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
    }

    public function index()
    {
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-user', 'title' => 'Manage Offers'];
        return view('admin.offers.index');
    }

    public function all()
    {

        $columns = [
            ['db' => 'id', 'dt' => 'id'],
            ['db' => 'name', 'dt' => 'name'],
            ['db' => 'user_id', 'dt' => 'user_id'],
            ['db' => 'price', 'dt' => 'price'],
            ['db' => 'offer_percentage', 'dt' => 'offer_percentage'],
            ['db' => 'status', 'dt' => 'status'],
            ['db' => 'created_at', 'dt' => 'created_at'],
            ['db' => 'updated_at', 'dt' => 'updated_at'],
        ];
        $type = 'offer';
        $count = 0;
        $users = $this->productRepository->getDataTableOffer($columns);
        return response($users);
    }


    public function changeStatus(Request $request)
    {
        $this->productRepository->changeStatus($request->all());
        return redirect(route('admin.dashboard.offers.index'));
    }
}
