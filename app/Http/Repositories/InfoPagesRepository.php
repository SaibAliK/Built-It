<?php

namespace App\Http\Repositories;

use App\Http\Dtos\PageUpdateDto;
use App\Http\Libraries\DataTable;
use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Page;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use function request;

class InfoPagesRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(new Page());
    }

    public function allPages()
    {
        $columns = [
            ['db' => 'id', 'dt' => 'id'],
            ['db' => 'slug', 'dt' => 'slug'],
            ['db' => 'page_type', 'dt' => 'page_type'],
            ['db' => 'created_at', 'dt' => 'created_at'],
            ['db' => 'updated_at', 'dt' => 'updated_at'],
//            ['db' => 'deleted_at', 'dt' => 'deleted_at'],
            ['db' => 'name', 'dt' => 'name'],
            ['db' => 'content', 'dt' => 'content'],
        ];
        DataTable::init(new Page, $columns);
        DataTable::where('page_type', '=', 'page');
        $slug = request('datatable.query.slug', '');
        $trashedPages = request('datatable.query.trashedPages', NULL);
        $createdAt = request('datatable.query.createdAt', '');
        $updatedAt = request('datatable.query.updatedAt', '');
        $deletedAt = request('datatable.query.deletedAt', '');
        $sortOrder = request('datatable.sort.sort');
        $sortColumn = request('datatable.sort.field');
        if (!empty($trashedPages)) {
            DataTable::getOnlyTrashed();
        }
        if ($slug != '') {
            DataTable::where('slug', 'LIKE', '%' . addslashes($slug) . '%');
        }
        if ($createdAt != '') {
            $createdAt = Carbon::createFromFormat('m/d/Y', $createdAt);
            $cBetween = [$createdAt->hour(0)->minute(0)->second(0)->timestamp, $createdAt->hour(23)->minute(59)->second(59)->timestamp];
            DataTable::whereBetween('created_at', $cBetween);
        }
        if ($updatedAt != '') {
            $updatedAt = Carbon::createFromFormat('m/d/Y', $updatedAt);
            $uBetween = [$updatedAt->hour(0)->minute(0)->second(0)->timestamp, $updatedAt->hour(23)->minute(59)->second(59)->timestamp];
            DataTable::whereBetween('updated_at', $uBetween);
        }
        if (!empty($deletedAt)) {
            $sWhere = function ($query) use ($deletedAt) {
                $deletedAt = Carbon::createFromFormat('m/d/Y', $deletedAt);
                $dBetween = [$deletedAt->hour(0)->minute(0)->second(0)->timestamp, $deletedAt->hour(23)->minute(59)->second(59)->timestamp];
                $query->whereBetween('deleted_at', $dBetween);
            };
            DataTable::getOnlyTrashed($sWhere);
        }
        $title = request('datatable.query.title', '');
        if (!empty($title)) {
            DataTable:: where('name', 'LIKE', '%' . addslashes($title) . '%');
//                DataTable::whereJsonContains('name->en',$title);
        }

        if (!empty($sortOrder) && !empty($sortColumn)) {
            DataTable::orderBy($sortColumn, $sortOrder);
        }
        $pages = DataTable::get();
//        $count = 0;
//        $perPage = \request('datatable.pagination.perpage', 1);
//        $page = \request('datatable.pagination.page', 1);
//        $perPage = ($page * $perPage) - $perPage;

        $start = 1;
        if ($pages['meta']['start'] > 0 && $pages['meta']['page'] > 1) {
            $start = $pages['meta']['start'] + 1;
        }
        $count = $start;


        if (sizeof($pages['data']) > 0) {
            $dateFormat = config('settings.date-format');
            foreach ($pages['data'] as $key => $page) {
                $pages['data'][$key]['count'] = $count++;
                $pages['data'][$key]['en_title'] = $page['name']['en'];
                $pages['data'][$key]['ar_title'] = '';

                if (isset($page['name']['ar'])) {
                    $pages['data'][$key]['ar_title'] = $page['name']['ar'];
                }
                $pages['data'][$key]['slug'] = '';
                $pages['data'][$key]['created_at'] = Carbon::createFromTimestamp($page['created_at'])->format($dateFormat);
                $pages['data'][$key]['updated_at'] = Carbon::createFromTimestamp($page['updated_at'])->format($dateFormat);
                if (!empty($trashedPages)) {
                    $pages['data'][$key]['actions'] = '<a class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill restore-record-button" href="javascript:{};" data-url="' . route('admin.dashboard.pages.restore', $page['id']) . '" title="Restore Page"><i class="fa fa-fw fa-undo"></i></a>' . '<span class="m-badge m-badge--danger">' . Carbon::parse($page['deleted_at'])->format($dateFormat) . '</span>';

                } else {
                    $pages['data'][$key]['actions'] = '<a href="' . route('admin.dashboard.pages.edit', $page['id']) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-fw fa-edit"></i></a>';
//                       . '<a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete-record-button" href="javascript:{};" data-url="'. route('admin.dashboard.pages.destroy', $page['id']).'" title="Delete"><i class="fa fa-fw fa-trash-o"></i></a>';
                }
                $pages['data'][$key]['slug'] = $page['slug'];
            }
        }
        return $pages;
    }

    public function save(PageUpdateDto $params)
    {
        DB::beginTransaction();
        try {
            $data = $params->except('id', 'image', 'icon')->toArray();
            if (!is_null($params->image)) {
                $data['image'] = $params->image;
            }
            if (!is_null($params->icon)) {
                $data['icon'] = $params->icon;
            }
            $page = $this->getModel()->updateOrCreate(['id' => $params->id], $data);
            DB::commit();
            return $page;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function get($id = 0)
    {
        $page = $this->getModel();
        if ($id > 0) {
            $page = $this->getModel()->findOrFail($id);
        }
        if (!empty($page->name) && !is_null($page->name)) {
            if (empty($page->name['ar'])) {
                $page->name = ['en' => $page->name['en'], 'ar' => ''];
            }
            if (empty($page->name['en'])) {
                $page->name = ['en' => '', 'ar' => $page->name['ar']];
            }
        } else {
            if (empty($page->name['en']) && empty($page->name['ar'])) {
                $page->name = ['en' => '', 'ar' => ''];
            }
        }

        if (!empty($page->content) && !is_null($page->content)) {

            if (empty($page->content['ar'])) {
                $page->content = ['en' => $page->content['en'], 'ar' => ''];
            }
            if (empty($page->content['en'])) {
                $page->content = ['en' => '', 'ar' => $page->content['ar']];
            }

        } else {
            if (empty($page->content['en']) && empty($page->content['ar'])) {
                $page->content = ['en' => '', 'ar' => ''];
            }
        }

        return $page;
    }

    public function destroyPage($id)
    {
        Page::destroy($id);
    }

    public function getslug($slug)
    {
        $pageData = $this->getModel()->where('slug', $slug)->first();
        if (is_null($pageData)) {
            throw new Exception(__('Invalid Url'));
        }
        return $pageData;
    }

    public function all()
    {
        if ($this->getPaginate() == 0) {
            return $this->getModel()->all();
        } else {
            return $this->getModel()->paginate($this->getPaginate());
        }

    }


}
