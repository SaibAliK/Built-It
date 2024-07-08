<?php

namespace App\Http\Controllers\Admin;

use App\Http\Repositories\FaqRepository;
use App\Http\Requests\FaqRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqsController extends Controller
{
    protected FaqRepository $faqRepository;

    public function __construct(FaqRepository $faqRepository)
    {
        parent::__construct('adminData', 'admin');
        $this->faqRepository = $faqRepository;
        $this->breadcrumbTitle = 'FAQs';
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
    }

    public function index()
    {
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => 'Manage FAQs'];
        return view('admin.faqs.index');
    }

    public function all()
    {
        $columns = [
            ['db' => 'id', 'dt' => 'id'],
            ['db' => 'question', 'dt' => 'question'],
            ['db' => 'answer', 'dt' => 'answer'],
        ];
        $pages = $this->faqRepository->getDataTable($columns);
        return response($pages);
    }

    public function edit($id)
    {
        $heading = (($id > 0) ? 'Edit FAQ' : 'Add FAQ');
        $this->breadcrumbs[route('admin.dashboard.faqs.index')] = ['icon' => 'fa fa-fw fa-building', 'title' => 'Manage FAQs'];
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => $heading];
        return view('admin.faqs.edit', [
            'faqId' => $id,
            'action' => route('admin.dashboard.faqs.update', $id),
            'heading' => $heading,
            'faq' => $this->faqRepository->getViewParams($id)
        ]);
    }

    public function update(FaqRequest $request, $id)
    {
        $faq = $this->faqRepository->save($request, $id);
        if ($faq) {
            $status = 'Faq Updated Successfully.';
            if ($id == 0) {
                $status = 'Faq Added Successfully.';
            }
            return redirect()->route('admin.dashboard.faqs.index')->with('status', $status);
        }
        return redirect()->back()->withErrors('something went wrong');
    }

    public function destroy($id)
    {
        try {
            $this->faqRepository->destroy($id);
            return response(['msg' => 'FAQ deleted']);
        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()]);
        }
    }

}
