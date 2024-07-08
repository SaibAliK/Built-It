<?php

namespace App\Http\Repositories;

use App\Http\Libraries\DataTable;
use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Category;
use Carbon\Carbon;


class CategoryRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new Category());
    }

    public function save($request, $id)
    {
        $route_name = $request->route()->getName();
        $data = $request->except('_method', '_token', 'language_id');
        $category = $this->getModel()->updateOrCreate(['id' => $id], $data);
    }

    public function destroy($id)
    {
        $category = $this->getModel()->where('id', '=', $id)->firstOrFail();
        $category->subCategories()->delete();
        $category::destroy($id);
    }

    public function all($onlyParent = false, $whereSubCat = false)
    {
        $query = $this->getQuery();
        $query->select(['id', 'parent_id', 'image', 'name']);
        if ($onlyParent) {
            $query->where('parent_id', 0);
        }

        if ($this->getPaginate() == 0) {
            $categories = $this->getModel()->latest()->all();
        } else {
            if ($whereSubCat) {
                $categories = $this->getModel()->with($this->getRelations())->latest()->wherehas('subCategories')->paginate($this->getPaginate());
            } else {
                $categories = $this->getModel()->with($this->getRelations())->latest()->paginate($this->getPaginate());
            }
        }
        return $categories;
    }

    public function getChildren($category_id, $paginate = false)
    {
        $query = $this->getModel()
            ->with($this->getRelations())
            ->where('parent_id', $category_id)
            ->latest();
        if ($paginate) {
            return $query->paginate($this->getPaginate());
        }
        return $query->get();
    }

    public function getSubCategories($id)
    {
        $subCategories = $this->getModel()->where('parent_id', $id)->where('parent_id', '>', 0)->get();
        return $subCategories;
    }

    public function get($id = 0)
    {
        $category = new Category();
        if ($id > 0) {
            $category = $this->getModel()->findOrFail($id);
        }
        if (is_null($category->name)) {
            $category->name = ['en' => '', 'ar' => ''];
        }

        return $category;
    }
}
