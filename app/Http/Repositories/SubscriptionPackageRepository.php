<?php


namespace App\Http\Repositories;

use App\Http\Dtos\SendEmailDto;
use App\Http\Dtos\SubscriptionUpdateDto;
use App\Http\Dtos\UserDto;
use App\Http\Dtos\UserRegisterDto;
use App\Http\Libraries\DataTable;
use App\Http\Repositories\BaseRepository\Repository;
use App\Jobs\SendMail;
use App\Models\SubscriptionPackage;
use App\Models\User;
use App\Traits\EMails;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Auth\Events\Registered;

//use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use Spatie\DataTransferObject\DataTransferObject;

class SubscriptionPackageRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(new SubscriptionPackage());
    }

    /**
     * @throws Exception
     */
    public function save(SubscriptionUpdateDto $params)
    {
        DB::beginTransaction();
        try {
            $data = $params->except('id')->toArray();
            $subscription = $this->getModel()->updateOrCreate(['id' => $params->id], $data);
            DB::commit();
            return $subscription;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function get($id = 0)
    {
        $package = $this->getModel();
        if ($id > 0) {
            $package = $this->getModel()->select($this->getSelect())->find($id);
        }
        if (!is_null($package)) {
            if (is_null($package->name)) {
                $package->name = ['en' => '', 'ar' => ''];
            }
            if (is_null($package->description)) {
                $package->description = ['en' => '', 'ar' => ''];
            }
        }
        return $package;
    }

    public function adminDataTable($columns)
    {

        DataTable::init(new SubscriptionPackage(), $columns);
        if (Route::currentRouteName() == 'admin.dashboard.subscription_packages.ajax.list') {
            DataTable::where('subscription_type', '=', 'subscription');
        } else {
            DataTable::where('subscription_type', '=', 'featured');
        }

        $articles = DataTable::get();
        $start = 1;
        if ($articles['meta']['start'] > 0 && $articles['meta']['page'] > 1) {
            $start = $articles['meta']['start'] + 1;
        }
        $count = $start;

        if (sizeof($articles['data']) > 0) {
            $dateFormat = config('settings.date-format');
            foreach ($articles['data'] as $key => $article) {
//                $count = $count + 1;
                $articles['data'][$key]['count'] = $count++;
                $articles['data'][$key]['name'] = ucwords($article['name']['en']);
                $articles['data'][$key]['description'] = $article['description']['en'];
                $articles['data'][$key]['duration'] = $article['duration'];
                $articles['data'][$key]['duration_type'] = $article['duration_type'];
                $articles['data'][$key]['price'] = $article['price'];
                if (Route::currentRouteName() == 'admin.dashboard.featured_packages.ajax.list') {
                    $articles['data'][$key]['actions'] = '<a href="' . route('admin.dashboard.featured-subscription.edit', $article['id']) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-fw fa-edit"></i></a>' .
                        '<a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete-article-button" href="javascript:{};" data-url="' . route('admin.dashboard.featured-subscription.destroy', $article['id']) . '" title="Delete"><i class="fa fa-fw fa-trash-o"></i></a>';
                } else {
                    $articles['data'][$key]['actions'] = '<a href="' . route('admin.dashboard.subscriptions.edit', $article['id']) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-fw fa-edit"></i></a>' .
                        '<a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete-article-button" href="javascript:{};" data-url="' . route('admin.dashboard.featured-subscription.destroy', $article['id']) . '" title="Delete"><i class="fa fa-fw fa-trash-o"></i></a>';
                }

            }
        }


        return $articles;
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $object = $this->get($id);
            if ($object->delete()) {

                DB::commit();
                return true;
            } else {
                throw new Exception('Unable to delete');
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }


    }

    public function all($type = 'subscription', $isFree = null)
    {
        $query = $this->getQuery();
        $query->where('subscription_type', $type);
        if (!is_null($isFree)) {
            $query->where('is_free', $isFree);
        }
        if ($this->getPaginate() > 0) {
            return $query->select($this->getSelect())->paginate($this->getPaginate());
        } else {
            return $query->select($this->getSelect())->get();
        }
    }


}
