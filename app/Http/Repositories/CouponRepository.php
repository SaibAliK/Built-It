<?php


namespace App\Http\Repositories;


use App\Http\Libraries\DataTable;
use App\Http\Repositories\BaseRepository\Repository;
use App\Jobs\ExpireCoupons;
use App\Models\Coupon;
use Exception;
use Carbon\Carbon;
use function RingCentral\Psr7\str;

class CouponRepository extends Repository
{
    public UserRepository $userRepository;

    public function __construct()
    {
        $this->setModel(new Coupon());
        $this->userRepository = new UserRepository();
    }

    public function listAll()
    {
        return $this->getModel()->all();
    }

    public function save($request, $id)
    {
        $data = $request->all();
        if ($id == 0) {
            $attribute = $this->isAttributeExist($request);
            if ($attribute) {
                throw new Exception('Attribute With Same Name Already Exists');
            }
        }

        $dateTime = Carbon::parse($request->get('end_date'))->setTimeFromTimeString(date('H:i:s', time()));
        if (!empty($request->get('end_date')) && $dateTime->greaterThanOrEqualTo(date('Y-m-d H:i:s', strtotime(now())))) {
            $data['status'] = 'active';
            $data['end_date'] = DateToUnixformat($request->get('end_date'));

            $days = Carbon::now()->diffInDays($request->get('end_date'));
            ExpireCoupons::dispatch()->delay(now()->addDays($days)->endOfDay());
        }
        return $this->getModel()->updateOrCreate(['id' => $id], $data);
    }

    public function all($columns, $count = 0, $type)
    {

        DataTable::init(new Coupon(), $columns);
        DataTable::where('deleted_at', '=', null);
        $createdAt = \request('datatable.query.created_at', '');
        $updatedAt = \request('datatable.query.updated_at', '');
        $deletedAt = \request('datatable.query.deleted_at', '');

        $name = \request('datatable.query.name', '');
        $coupon_code = \request('datatable.query.coupon_code', '');
        $status = \request('datatable.query.status', '');

        if (!empty($name)) {
            DataTable::where('name', 'LIKE', '%' . addslashes($name) . '%');
        }

        if (!empty($coupon_code)) {
            DataTable::where('coupon_code', 'LIKE', '%' . addslashes($coupon_code) . '%');
        }
        if (!empty($status)) {
            DataTable::where('status', 'LIKE', '%' . addslashes($status) . '%');
        }

        if (!empty($trashedItems)) {
            DataTable::getOnlyTrashed();
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
            $where = function ($query) use ($deletedAt) {
                $deletedAt = Carbon::createFromFormat('m/d/Y', $deletedAt);
                $dBetween = [$deletedAt->hour(0)->minute(0)->second(0)->timestamp, $deletedAt->hour(23)->minute(59)->second(59)->timestamp];
                $query->whereBetween('deleted_at', $dBetween);
            };
            DataTable::getOnlyTrashed($where);
        }
        $coupon = DataTable::get();
        $dateFormat = config('settings.date-format');
        $start = 1;

        if ($coupon['meta']['start'] > 0 && $coupon['meta']['page'] > 1) {
            $start = $coupon['meta']['start'] + 1;
        }
        $count = $start;

        if (sizeof($coupon['data']) > 0) {
            foreach ($coupon['data'] as $key => $data) {
                //                $count = $count + 1;
                $coupon['data'][$key]['id'] = $count++;
                $coupon['data'][$key]['name'] = $data['name']['en'];
                $coupon['data'][$key]['coupon_code'] = $data['coupon_code'];
                $coupon['data'][$key]['discount'] = $data['discount'] . '%';
                $coupon['data'][$key]['end_date'] = Carbon::parse(unixTODateformate($data['end_date']))->format('d-m-Y');
                $coupon['data'][$key]['status'] = ucfirst($data['status']);
                $coupon['data'][$key]['coupon_type'] = $data['coupon_type'];
                $coupon['data'][$key]['coupon_number'] = $data['coupon_number'];
                if ($data['coupon_number'] <= 0 && $data['coupon_type'] == 'infinite') {
                    $coupon['data'][$key]['coupon_number'] = 'Unlimited';
                }
                $coupon['data'][$key]['actions'] = '<a href="' . route('admin.dashboard.coupons.edit', $data['id']) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="fa fa-fw fa-edit"></i></a>' .
                    '<a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete-record-button" href="javascript:{};" data-url="' . route('admin.dashboard.coupons.destroy', $data['id']) . '" title="Delete"><i class="fa fa-fw fa-trash-o"></i></a>';
                $coupon['data'][$key]['created_at'] = Carbon::createFromTimestamp($data['created_at'])->format($dateFormat);
                $coupon['data'][$key]['updated_at'] = Carbon::createFromTimestamp($data['updated_at'])->format($dateFormat);
            }
        }

        return $coupon;
    }

    public function destroy($id, $used = false)
    {

        $this->userRepository->setFromWeb($this->getFromWeb());
        $userQuery = $this->userRepository->getModel();
        $user = $this->getUser();
        $query = $this->getQuery();

        if (empty($id)) {

            $data = $this->getModel()->where('coupon_code', '=', $user->coupon)->first();
            if ($used) {
                $data->reserved = $data->reserved - 1;
                $data->used = $data->used + 1;
            } else {
                $data->coupon_number = $data->coupon_number + 1;
                $data->reserved = $data->reserved - 1;
            }

            $data->save();
            return $userQuery->where('id', '=', $user->id)->update(['coupon' => null]);
        }
        if (!empty($id)) {
            $coupon = $query->where('id', '=', $id)->firstOrFail();
            $coupon::destroy($id);
        }
    }

    public function getViewParams($id = 0)
    {
        $coupon = new Coupon();
        $coupon->name = ['en' => '', 'ar' => ''];
        if ($id > 0) {
            $coupon = $this->getModel()->findOrFail($id);
            if (!empty($coupon->end_date)) {
                $coupon->end_date = unixTODateformate($coupon->end_date);
            }
        }
        return $coupon;
    }

    public function isAttributeExist($request)
    {
        return $this->getModel()->where('name', $request->name)->first();
    }

    public function isValid($user)
    {
        if (empty($user)) {
            return false;
        }

        $coupon = $this->getModel()->where([['coupon_code', $user->coupon], ['status', 'active']])
            ->where('end_date', '>=', DateToUnixformat(date('Y-m-d', strtotime(now()))))
            ->first();
        if (empty($coupon) || empty($coupon->coupon_code)) {
            return false;
        }
        if (!empty($user->id)) {
            $user->coupon = $coupon->coupon_code;
            $user->save();
            return $coupon->discount;
        }
    }

    public function addUserCoupon($coupon)
    {
        $this->userRepository->setFromWeb($this->getFromWeb());
        $user = $this->getUser();

        $data = $this->getModel()->where([['coupon_code', $coupon], ['status', 'active']])
            ->where('coupon_number', '>', 0)
            ->orwhere([
                ['coupon_number', '<=', 0],
                ['coupon_code', $coupon],
                ['coupon_type', 'infinite'],
                ['status', 'active']
            ])
            ->first();

        if (empty($data->id) || empty($data->end_date)) {
            return "finished";
        }

        $dateTime = Carbon::parse(unixTODateformate($data->end_date))->setTimeFromTimeString(date('H:i:s', time()));

        if ($dateTime->greaterThanOrEqualTo(date('Y-m-d H:i:s', strtotime(now())))) {
            $this->userRepository->getModel()->where('id', $user->id)->update(['coupon' => $coupon]);
            $data->coupon_number = $data->coupon_number - 1;
            $data->reserved = $data->reserved + 1;
            $data->save();
        } else {
            return "expired";
        }
    }
}
