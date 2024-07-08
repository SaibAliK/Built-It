<?php

namespace App\Http\Controllers\Admin;
use App\Http\Dtos\PageUpdateDto;
use App\Http\Repositories\InfoPagesRepository;
use App\Http\Requests\PageRequest;
use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PagesController extends Controller {
    protected $infoPagesRepository;
    public function __construct(InfoPagesRepository $infoPagesRepository) {
        parent::__construct('adminData', 'admin');
        $this->infoPagesRepository = $infoPagesRepository;
        $this->breadcrumbTitle = 'Info Pages';
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home','title' => 'Dashboard'];
    }
    public function index()
    {
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => 'Manage Info Pages'];
        return view('admin.pages.index');
    }
    public function all(){
        $pages = $this->infoPagesRepository->allPages();
        return response($pages);
    }
    public function edit($id) {
        $heading = (($id > 0) ? 'Edit Page':'Add Page');
        $this->breadcrumbs[route('admin.dashboard.pages.index')] = ['icon' => 'fa fa-fw fa-building', 'title' => 'Manage Pages'];
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => $heading];
        return view('admin.pages.edit', [
            'method' => 'PUT',
            'pageId' => $id,
            'action' => route('admin.dashboard.pages.update', $id),
            'heading' => $heading,
            'page' => $this->infoPagesRepository->get($id)
        ]);
    }

    public function update(PageRequest $request, $id) {
        try {
            $pageDto = PageUpdateDto::fromRequest($request);
            $this->infoPagesRepository->save($pageDto);
            if ($id == 0){
                return redirect(route('admin.dashboard.pages.index'))->with('status', 'Page added successfully.');

            }
            return redirect(route('admin.dashboard.pages.index'))->with('status', 'Page updated successfully.');
        }
        catch (\Exception $e){
            return response(['err'=>$e->getMessage()]);
        }
    }

    public function destroy($id) {
        try {
            $this->infoPagesRepository->destroyPage($id);
            return response(['msg' => 'Page deleted']);
        }
        catch (\Exception $e){
            return response(['err'=>$e->getMessage()]);
        }
    }

}
