<?php


namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository\Repository;
use App\Models\AreaProduct;
use App\Models\AreaUser;
use App\Models\City;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CityRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new City());
    }

    public function all($onlyParent = false)
    {
        $query = $this->getQuery();
        $query->where('deleted_at', null);
        if ($onlyParent) {
            $query->where('parent_id', 0);
        }
        return $query->with($this->getRelations())->latest()->get();
    }


    function getAreas($id, $store_id)
    {
        $query = $this->getQuery();
        $query = $query->where('parent_id', $id)->where('deleted_at', null);
        if ($store_id > 0) {
            $store = User::with('coveredAreas')->where('id', $store_id)->first();
            $area_ids = [];
            foreach ($store->coveredAreas as $areas) {
                $area_ids[] = $areas->id;
            }
            $query = $query->whereIn('id', $area_ids);
        }
        $data = $query->get();
        return $data;
    }

    public function saveArea($request)
    {
        DB::beginTransaction();
        try {
            $user = $this->getUser();
            $already_area = AreaUser::where('user_id', $user->id)->where('area_id', $request->selected_area)->first();
            if (!$already_area) {
                if ($request->id != null) {
                    $area = AreaUser::where('id', $request->get('id'))->select('id', 'area_id', 'user_id', 'price')->first();
                    if ($area->area_id != $request->selected_area) {
                        $area->price = $request->price;
                        $area->area_id = $request->selected_area;
                        $area->save();
                    } else {
                        throw new Exception(__('Area is already selected'));
                    }
                } else {
                    $user->areas()->attach($request->selected_area, [
                        'price' => $request->price ?? '',
                        'user_id' => $user->id,
                    ]);
                    return true;
                }
            } else {
                if ($request->has('id') && $request->id != 0) {
                    $area = AreaUser::where('id', $request->get('id'))->select('id', 'area_id', 'user_id', 'price')->first();
                    if ($area->area_id != $request->selected_area) {
                        $area->price = $request->price;
                        $area->area_id = $request->selected_area;
                        $area->save();
                    } else {
                        throw new Exception(__('Area is already selected'));
                    }
                } else {
                    throw new Exception(__('Area is already selected'));
                }
            }
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function saveDArea($request)
    {
        DB::beginTransaction();
        try {
            $user = $this->getUser();
            $already_area = AreaUser::where('user_id', $user->id)->where('area_id', $request->selected_area)->first();
            if (!$already_area) {
                $user->areas()->attach($request->selected_area, [
                    'price' => $request->price ?? '',
                    'user_id' => $user->id,
                ]);
            } else {
                if ($request->id != 0 && $request->id != null) {
                    $area = AreaUser::where('id', $request->get('id'))->select('id', 'area_id', 'user_id', 'price')->first();
                    if ($area->area_id == $request->selected_area) {
                        $area->price = $request->price;
                        $area->area_id = $request->selected_area;
                        $area->save();
                    } elseif ($area->area_id != $request->selected_area && $already_area) {
                        $area->price = $request->price;
                        $area->save();
                    } else {
                        throw new Exception(__('Area is already selected'));
                    }
                } else {
                    if ($already_area->area_id != $request->selected_area) {
                        $user->areas()->attach($request->selected_area, [
                            'price' => $request->price ?? '',
                            'user_id' => $user->id,
                        ]);
                    } else {
                        throw new Exception(__('Area is already selected'));
                    }
                }
            }

            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function checkAlreadyArea($request)
    {
        $user = $this->getUser();
        $already_area = AreaUser::where('user_id', $user->id)->where('area_id', $request->selected_id)->first();
        if ($already_area) {
            throw new Exception(__('Area is already selected'));
        }
    }

    public function deleteAreas($id)
    {

        DB::beginTransaction();
        try {
            $user = $this->getUser();
            $user_id = $user->id;
            $area = AreaUser::where('id', $id)->first();
            if ($area) {
                $area->delete();
                DB::commit();
                return true;
            } else {
                throw new Exception("No Record Found");
            }
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }

    }

    public function getSavedArea()
    {
        $user = $this->getUser();
        return AreaUser::where('user_id', $user->id)->select('id', 'user_id', 'area_id', 'price')->with('area')->get();
    }

    public function getSavedAreaForApi($request)
    {
        $user = $request['store_id'];
        return AreaUser::where('user_id', $user)->with('area')->get();
    }

    public function getArea($id)
    {
        return AreaUser::where('id', $id)->select('id', 'user_id', 'area_id', 'price')->with('area')->first();
    }

    public function areas($id = 0)
    {
        $query = $this->getQuery();
        if ($id) {
            $data = $query->where('parent_id', $id)->where('deleted_at', null)->get();
        } else {
            $data = $query->where('parent_id', '!=', 0)->where('deleted_at', null)->get();
        }
        return $data;
    }

    public function areasForPopup($id)
    {
        DB::beginTransaction();
        try {
            $query = $this->getQuery();
            $data = $query->where('parent_id', $id)->where('deleted_at', null)->get();
            $startItem = [];
            $startItem['id'] = 0;
            $startItem['name']['en'] = 'Select Delivery Area';
            $startItem['name']['ar'] = __('Select Delivery Area');
            $data->prepend($startItem);
            DB::commit();
            return $data;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }

    }

    public function save($request, $id)
    {
        DB::beginTransaction();
        try {

            if (count(json_decode($request->polygon)) == 0) {
                return redirect()->back()->withErrors('Please create a polygon in the map to mark boundary');
            }

            $data = $request->except('_method', '_token', 'name');
            if ($id == 0) {
                $data['name->ar'] = '';
            }

            $data = [
                'parent_id' => $request->city_id,
                'polygon' => json_decode($request->polygon),
            ];

            if ($request->language_id == 2) {
                $data['name->en'] = $request->get('name->en');
                $data['name->ar'] = $request->get('name->ar');
            } else {
                $data['name->en'] = $request->get('name->en');
                $data['name->ar'] = $request->get('name->ar');
            }

            $data['latitude'] = $request->latitude;
            $data['longitude'] = $request->longitude;

            $category = $this->getModel()->updateOrCreate(['id' => $id], $data);
            DB::commit();
            return true;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function destroy($id)
    {
        $city = City::where('id', '=', $id)->firstOrFail();
        $city_name = translate($city->name);

        $area_id = City::where('parent_id', '=', $id)->pluck('id');

        $user_list = User::where('city_id', $id)->pluck('id');


        if (count($area_id) > 0) {
            $area_user = AreaUser::whereIn('area_id', $area_id)->get();
            if (count($area_user) > 0) {
                foreach ($area_user as $item) {
                    $item->delete();
                }
            }


        }

        $user = User::where('city_id', $id)->update([
            'city_id' => null
        ]);

        $city->areas()->delete();


        if (count($user_list) > 0) {
            foreach ($user_list as $item) {
                $notificationArray = ['store_id' => $item, 'sender_id' => $item, 'receiver_id' => $item, 'title->en' => $city_name . " " . "is deleted by admin, please update city and its delivery areas", 'description->en' => $city_name . " " . "is deleted by admin, please update city and its delivery areas", 'title->ar' => $city_name . " " . "is deleted by admin, please update city and its delivery areas", 'description->ar' => $city_name . " " . "is deleted by admin, please update city and its delivery areas"
                ];
                $this->notification($notificationArray);
            }
        }

        $city::destroy($id);
        return $city;
    }

    public function destroyArea($ar, $id)
    {
        DB::beginTransaction();
        try {
            $area = City::where('id', $id)->firstOrFail();
            $area_name = translate($area->name);

            $area_user = AreaUser::where('area_id', $id)->get();

            $user_ids = AreaUser::where('area_id', $id)->pluck('user_id');

            if (count($area_user) > 0) {
                foreach ($area_user as $item) {
                    $item->delete();
                }
                foreach ($user_ids as $item) {
                    $notificationArray = ['store_id' => $item, 'sender_id' => $item, 'receiver_id' => $item, 'title->en' => $area_name . " " . 'is deleted by admin, please update delivery areas and its products', 'description->en' => $area_name . " " . 'is deleted by admin, please update delivery areas and its products', 'title->ar' => $area_name . " " . "is deleted by admin, please update delivery areas and its products", 'description->ar' => $area_name . " " . "is deleted by admin, please update delivery areas and its products"
                    ];
                    $this->notification($notificationArray);
                }
            }


            $area::destroy($id);
            DB::commit();
            return $area;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }

    }

    public function notification($notificationArray)
    {
        sendNotification([
            'sender_id' => $notificationArray['sender_id'],
            'receiver_id' => $notificationArray['receiver_id'],
            'title->en' => $notificationArray['title->en'],
            'title->ar' => $notificationArray['title->ar'],
            'extras->store_id' => $notificationArray['store_id'],
            'description->en' => $notificationArray['description->en'],
            'description->ar' => $notificationArray['description->ar'],
            'action' => 'UPDATE_PRODUCT_AREA'
        ]);
    }

    public function update($request, $id)
    {

        DB::beginTransaction();
        try {
            if ($id == 0) {
                $city = City::where('name', $request->get('name->en'))->first();
                if ($city !== null) {
                    return redirect()->back()->withInput()->with('err', 'City With Same Name Already Exists');
                }
            }
            $data['name->en'] = $request->get('name->en');
            $data['name->ar'] = $request->get('name->ar');
            $data['parent_id'] = $request->get('parent_id');

            $city = City::updateOrCreate(['id' => $id], $data);
            DB::commit();
            return $city;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function get($id = 0)
    {
        $city = new City();
        if ($id > 0) {
            $city = City::findOrFail($id);
        }
        if (is_null($city->name)) {
            $city->name = ['en' => '', 'ar' => ''];
        }
        return $city;
    }
}
