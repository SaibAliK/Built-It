<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\CategoryRepository;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    protected CategoryRepository $categoryRepository;

    public function __construct()
    {
        parent::__construct();
        $this->categoryRepository = new CategoryRepository();
    }

    public function getCategories()
    {
        $cat = new Category();
        $query = $cat->query();
        $query->withcount(['subCategories' => function ($q) {
            $q->where('categories.deleted_at', '!=', null);
        }]);
        $categories = Category::select('id', 'parent_id', 'name', 'image')
            ->where('parent_id', 0)->whereHas('subcategories')->with(['subcategories:id,parent_id,name,image'])
            ->orderBy('created_at', 'DESC')->get();
        return responseBuilder()->success(__('Categories'), $categories);
    }

    public function getSubCategories(Request $request, $id)
    {
        $categories = Category::select('id', 'parent_id', 'name', \DB::raw('concat("' . url('/') . '/",image)as image'))
            ->where('parent_id', $id)
            ->orderBy('created_at', 'DESC')->get();
        $startItem = [];
        $startItem['id'] = 0;
        $startItem['name']['en'] = 'Select Sub Category';
        $startItem['name']['ar'] = __('Select Category');
        $categories->prepend($startItem);

        return responseBuilder()->success(__('Subcategories'), $categories);
    }

    public function getRoleCategories($id)
    {
        $cat = new Category();
        $query = $cat->query();

        $query->withcount(['subCategories' => function ($q) {
            $q->where('categories.deleted_at', '!=', null);
        }]);

        $categories = Category::select('id', 'parent_id', 'name', 'image','type')->where('role_id','like', '%"' . $id . '"%')
            ->where('parent_id', 0)->whereHas('subcategories')->with(['subcategories:id,parent_id,name,image'])
            ->orderBy('created_at', 'DESC')->get();
        return responseBuilder()->success(__('Categories'), $categories);
    }

    public function getSubCateWithAttr(Request $request)
    {
        try {
            $this->validate(request(), ['category_id' => 'required']);
            $categories = $this->categoryRepository->getChildren($request->category_id);
            return responseBuilder()->success(__('Subcategories'), $categories);
        } catch (\Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

}
