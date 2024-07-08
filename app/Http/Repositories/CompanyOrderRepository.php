<?php


namespace App\Http\Repositories;

use App\Models\RiderOrder;
use App\Models\CompanyOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Repositories\BaseRepository\Repository;


class CompanyOrderRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new CompanyOrder());
    }


    public function save($id, $company_id, $orderDetailId, $order_id, $status)
    {
        $data['company_id']      = $company_id;
        $data['status']          = $status;
        $data['order_detail_id'] = $orderDetailId;
        $data['order_id']        = $order_id;

        return $this->getModel()->updateOrCreate(['id' => $id], $data);
    }

    public function delete($order_id,$order_detail_id)
    {

        return $this->getModel()->where('order_id' ,$order_id )->where('company_id' ,auth()->user()->id )->delete();
    }

    public function get($id)
    {
        return $this->getModel()->find($id);
    }

    public function assign(Request $request, $id = 0)
    {
        DB::beginTransaction();
        try {

            $data = $request->all();
            RiderOrder::updateOrCreate(['id' => $id], $data);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
