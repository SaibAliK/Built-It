<?php

namespace App\Http\Repositories;

use App\Http\Libraries\DataTable;
use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Subscription;

class SubscriptionRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(new Subscription());
    }

    public function dataTable($columns)
    {
        DataTable::init(new Subscription(), $columns);
        $news = DataTable::get();
        $dateFormat = config('settings.date-format');

        if (sizeof($news['data']) > 0) {

            $index = 1;
            if ($news['meta']['start'] > 0 && $news['meta']['page'] > 1) {
                $index = $news['meta']['start'] + 1;
            }
            $count = $index;

            $pageNumber = (\request('datatable.pagination.page') - 1) * \request('datatable.pagination.perpage');

            foreach ($news['data'] as $key => $data) {

                $news['data'][$key]['index'] = $count++;
                $index = $index + 1;
            }
        }
        return $news;
    }

    public function all($type = null)
    {
        return $this->getModel()->all();
    }

    public function get($id)
    {
        return $this->getModel()->find($id);

    }

    public function save($request, $id){
        $data['email'] = $request->email_for_subscribe;
        if ($request->has('notify')){
            $data['notify'] = 1;
        }else{
            $data['notify'] = 0;
        }
        return $this->getModel()->updateOrCreate(['id' => $id], $data);

    }

}
