<?php


namespace App\Http\Repositories;

use App\Http\Libraries\DataTable;
use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Faq;
use Carbon\Carbon;
use function request;


class FaqRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new Faq());
    }

    public function getDataTable($columns)
    {
        DataTable::init(new Faq(), $columns);
        $name = request('datatable.query.heading', '');

        if (!empty($name)) {
            DataTable::where('name', 'LIKE', '%' . addslashes($name) . '%');
        }

        $faqs = DataTable::get();
        $dateFormat = config('settings.date-format');

        $start = 1;
        if ($faqs['meta']['start'] > 0 && $faqs['meta']['page'] > 1) {
            $start = $faqs['meta']['start'] + 1;
        }
        $count = $start;
        if (sizeof($faqs['data']) > 0) {
            foreach ($faqs['data'] as $key => $data) {
                $data['count'] = $count++;
                $data['question'] = $data['question']['en'];
                $data['answer'] = $data['answer']['en'];
                $data['actions'] = '<a href="' . route('admin.dashboard.faqs.edit', $data['id']) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-fw fa-edit"></i></a>' .
                    '<a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete-record-button" href="javascript:{};" data-url="' . route('admin.dashboard.faqs.destroy', $data['id']) . '" title="Delete"><i class="fa fa-fw fa-trash-o"></i></a>';

                $faqs['data'][$key] = $data;
            }
        }
        return $faqs;

    }

    public function all()
    {
        if ($this->getPaginate() == 0) {
            $faqs = $this->getModel()->latest()->get();
        } else {
            $faqs = $this->getModel()->latest()->paginate($this->getPaginate());
        }
        return $faqs;
    }

    public function getViewParams($id = 0)
    {
        $faqs = new Faq();
        if ($id > 0) {
            $faqs = $this->getModel()->findOrFail($id);
        }
        if (is_null($faqs->question)) {
            $faqs->question = ['en' => '', 'ar' => ''];
        }
        if (is_null($faqs->answer)) {
            $faqs->answer = ['en' => '', 'ar' => ''];
        }
        return $faqs;
    }

    public function get($slug)
    {
        $faqs = $this->getModel()->where('slug', $slug)->first();
        return $faqs;
    }

    public function save($request, $id)
    {
        $data = $request->except('_token', '_method');
        $data = $this->getModel()->updateOrCreate(['id' => $id], $data);
        return $data;
    }

    public function show()
    {
        dd("show");
    }

    public function destroy($id)
    {
        $faqs = $this->getModel()->where('id', '=', $id)->firstOrFail();
        $faqs::destroy($id);
        return $faqs;
    }
}
