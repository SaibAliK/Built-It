<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Repositories\CategoryRepository;
use App\Http\Requests\FromValidation;
use Illuminate\Support\Arr;

class SubCategoriesController extends Controller
{

    protected CategoryRepository $categoryRepository;

    public function __construct()
    {
        parent::__construct('adminData', 'admin');
        $this->categoryRepository = new CategoryRepository();
        $this->breadcrumbTitle = 'Sub Categories';
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
    }

    public function index($id)
    {
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => 'Manage Categories '];
        return view('admin.sub-categories.subCategory', ['id' => $id]);
    }

    public function show($id)
    {
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => 'Manage Sub Categories'];
        return view('admin.sub-categories.subCategory', ['id' => $id]);
    }

    public function edit($parentId, $id)
    {
        $Category = $this->categoryRepository->get($parentId);
        $this->breadcrumbTitle = $Category->name['en'];
        $heading = (($id > 0) ? 'Edit Subcategory' : 'Add Subcategory');
        $this->breadcrumbs[route('admin.dashboard.categories.index')] = ['icon' => 'fa fa-fw fa-building', 'title' => 'Manage Categories'];
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => $heading];

        $categoryData[] = '';
        $categoryData = arr::prepend($categoryData, 'Select Attribute', 0);

        return view('admin.sub-categories.edit', [
            'categoryId' => $id,
            'category' => $this->categoryRepository->get($id),
            'action' => route('admin.dashboard.categories.sub-categories.update', [$parentId, $id]),
            'parent' => $parentId,
        ]);
    }

    public function update(FromValidation $request, $parent_id, $id)
    {
        try {
            $this->categoryRepository->save($request, $id);
            if ($id == 0) {
                return redirect(route('admin.dashboard.categories.index'))->with('status', 'Subcategory Added Successfully.');
            } else {
                return redirect(route('admin.dashboard.categories.index'))->with('status', 'Subcategory Updated Successfully.');

            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err', $e->getMessage());
        }
    }
}
