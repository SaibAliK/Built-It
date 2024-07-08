<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Repositories\CategoryRepository;
use App\Http\Requests\FromValidation;
use Illuminate\Support\Arr;


class CategoryController extends Controller
{
    protected CategoryRepository $categoryRepository;

    public function __construct()
    {
        parent::__construct('adminData', 'admin');
        $this->categoryRepository = new CategoryRepository;
        $this->breadcrumbTitle = 'Categories';
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
        $this->breadcrumbs[route('admin.dashboard.categories.index')] = ['icon' => 'fa fa-fw fa-money', 'title' => 'Manage Categories'];
    }

    public function index()
    {
        $categories = $this->categoryRepository->all(true);
        return view('admin.categories.index', ['categories' => $categories]);
    }

    public function all()
    {
        $category = $this->categoryRepository->getDataTable();
        return response($category);
    }

    public function edit($id)
    {
        $heading = (($id > 0) ? 'Edit Category' : 'Add Category');
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => $heading];
        $category = $this->categoryRepository->get($id);

        return view('admin.categories.edit', [
            'categoryId' => $id,
            'category' => $category,
            'action' => route('admin.dashboard.categories.update', $id),
            'selectedAttributes' => NULL,
        ]);

    }

    public function update(FromValidation $request, $id)
    {
        try {
            $this->categoryRepository->save($request, $id);
            if (!empty($id)) {
                return redirect(route('admin.dashboard.categories.index'))->with('status', 'Category Updated Successfully.');
            }
            return redirect(route('admin.dashboard.categories.index'))->with('status', 'Category Added Successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->categoryRepository->destroy($id);
            return response(['msg' => 'Category deleted Successfully']);
        } catch (\Exception $e) {
            return response(['err' => 'Unable to delete'], 400);
        }
    }

}
