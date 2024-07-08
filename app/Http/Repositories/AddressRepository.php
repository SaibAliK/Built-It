<?php


namespace App\Http\Repositories;


use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Address;
use App\Models\AreaUser;
use App\Models\City;
use http\Env\Request;
use Exception;


class AddressRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new Address());
    }

    public function get($address_id = null, $ss = [])
    {
        $user = $this->getUser();
        $query = $this->getQuery();
        if (!empty($address_id)) {
            $data = $query->where([['id', $address_id], ['user_id', $user->id]])->where('deleted_at', null)->first();
        } else {
            if (!empty($ss)) {
                $data = $query->where('user_id', $user->id)->where('city_id', $ss['city_id'])->where('area_id', $ss['area_id'])->where('deleted_at', null)->get();
            } else {
                $data = $query->where('user_id', $user->id)->where('deleted_at', null)->get();
            }
        }
        return $data;
    }

    public function save($request, $id)
    {
        $city = City::where('id', $request->area_id)->where('parent_id', $request->city_id)->first();
        $polygon_lat = [];
        $polygon_lng = [];
        if ($city) {
         $count_polygon = count($city->polygon);
            foreach ($city->polygon as $item) {
                array_push($polygon_lat, $item['lat']);
                array_push($polygon_lng, $item['lng']);
            }

           // if (Check_is_in_polygon($count_polygon - 1, $polygon_lng, $polygon_lat, $request->longitude, $request->latitude)) {
            if (true) {
            $user = $this->getUser();
                $query = $this->getQuery();
                $data = [];
                $data = $request->all();
                $ifDefault = $this->getModel()->where('user_id', $user->id)->count();
                if ($ifDefault == 0) {
                    $data['default_address'] = 1;
                }
                $user = $this->getUser();
                if($id == 0){
                $this->getModel()->where('user_id', $user->id)->update(['default_address' => 0]);
                $data['default_address'] = 1;
                }
                $data['user_id'] = $user->id;

                return $query->where('user_id', $user->id)->updateOrCreate(['id' => $id], $data);
            } else {
                throw new Exception(__('Address is not found in delivery areas'));
            }
        } else {
            throw new Exception(__('Address is not found in delivery areas'));
        }
    }

    public function delete($id)
    {
        $user = $this->getUser();

        $this->getQuery()->where('id', $id)->delete();
        $updateDefault = $this->getModel()->where('user_id', $user->id)->get();
        if (count($updateDefault) > 0) {

            $already = $this->getModel()->where('user_id', $user->id)->where('default_address', 1)->first();
            if (!$already) {
                if (!empty($updateDefault[0])) {
                    $updateDefault[0]->default_address = 1;
                    $updateDefault[0]->save();
                }
                return true;
            }

        }
    }

    public function all($params)
    {
        $user = $this->getUser();
        $query = $this->getModel()->query();

        if (isset($params['city_id']) && isset($params['area_id'])) {
            $city_id = $params['city_id'];
            $area_id = $params['area_id'];
            $query->where('city_id', $city_id)->where('area_id', $area_id);
        }
        return $query->where('user_id', $user->id)->where('deleted_at', null)->with($this->getRelations())->get();
    }

    public function getAddressCheckout($params)
    {
        $user = $this->getUser();
        $query = $this->getModel()->query();
        $query->whereIn('area_id', $params);
        return $query->where('user_id', $user->id)->where('deleted_at', null)->with($this->getRelations())->get();
    }

    public function all_for_listing($params)
    {
        $user = $this->getUser();
        $query = $this->getModel()->query();
        if (isset($params['city_id']) && isset($params['area_id'])) {
            $city_id = $params['city_id'];
            $area_id = $params['area_id'];
            $query->where('city_id', $city_id)->where('area_id', $area_id);
        }
        return $query->where('user_id', $user->id)->where('deleted_at', null)->with($this->getRelations())->latest()->paginate($this->getPaginate());
    }

    public function getAreaPrice($request)
    {
        if ($request->has('area_id') && $request->has('store_id')) {
            $data = AreaUser::where('user_id', $request->store_id)->where('area_id', $request->area_id)->select('price')->first()->toArray();
            return $data;
        } else {
            return "false";
        }
    }

    public function makeDefault($request)
    {

        $user = $this->getUser();
         $this->getModel()->where('user_id', $user->id)->update(['default_address' => 0]);
        if(isset($request['radio-default-address'])){
             $this->getModel()->where('id', $request->get('radio-default-address'))->update(['default_address' => 1]);

        }else{
         $this->getModel()->where('id', $request->get('id'))->update(['default_address' => 1]);

        }
    }
}
