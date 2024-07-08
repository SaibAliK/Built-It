<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Repositories\ArticleRepository;
use App\Http\Requests\ArticleRequest;


class ArticlesController extends Controller
{
    protected ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        parent::__construct('adminData', 'admin');
        $this->articleRepository = $articleRepository;
        $this->breadcrumbTitle = "Articles";
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
    }

    public function index()
    {
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => 'Manage Articles'];
        return view('admin.articles.index');
    }

    public function view($id)
    {
        $heading = (($id > 0) ? 'View Store' : 'Add Store');
        $this->breadcrumbs[route('admin.dashboard.articles.index')] = ['icon' => 'fa fa-fw fa-building', 'title' => 'Manage Articles'];
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => $heading];
        return view('admin.articles.view', [
            'method' => 'PUT',
            'storeId' => $id,
            'action' => route('admin.dashboard.articles.update', $id),
            'heading' => $heading,
            'user' => $this->articleRepository->getViewParams($id),
        ]);
    }

    public function all()
    {
        $columns = [
            ['db' => 'id', 'dt' => 'id'],
            ['db' => 'name', 'dt' => 'name'],
            ['db' => 'content', 'dt' => 'content'],
            ['db' => 'author', 'dt' => 'author'],
        ];
        $articles = $this->articleRepository->getDataTable($columns);
        return response($articles);

    }

    public function edit($id)
    {
        $heading = (($id > 0) ? 'Edit Article' : 'Add Article');
        $this->breadcrumbs[route('admin.dashboard.articles.index')] = ['icon' => 'fa fa-fw fa-building', 'title' => 'Manage Article'];
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => $heading];
        return view('admin.articles.edit', [
            'heading' => $heading,
            'action' => route('admin.dashboard.articles.update', $id),
            'article' => $this->articleRepository->getViewParams($id),
            'articleId' => $id,
        ]);
    }

    public function update(ArticleRequest $request, $id)
    {
        $article = $this->articleRepository->save($request, $id);
        if ($article) {
            $status = 'Article Updated Successfully.';
            if ($id == 0) {
                $status = 'Article Added Successfully.';
            }
            return redirect()->route('admin.dashboard.articles.index')->with('status', $status);
        }
        return redirect()->back()->withErrors('something went wrong');
    }

    public function show($id)
    {
        dd("show");
    }

    public function destroy($id)
    {
        $data = $this->articleRepository->destroy($id);
        if (!$data) {
            return response(['err' => 'Unable to delete'], 400);
        }
        return response(['msg' => 'Successfully deleted']);
    }
}
