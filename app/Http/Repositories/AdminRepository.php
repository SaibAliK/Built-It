<?php


namespace App\Http\Repositories;

use App\Http\Dtos\AdminDto;
use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Admin;
use Exception;
use Illuminate\Support\Facades\DB;

class AdminRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(new Admin());
    }

    /**
     * @throws Exception
     */
    public function save(AdminDto $params)
    {
        DB::beginTransaction();
        try {
            $paramsArray = $params->only('name', 'email')->toArray();
            if (!is_null($params->password) ) {
                $paramsArray['password'] = bcrypt($params->password);
            }
            if (!is_null($params->imageUrl) ) {
                $paramsArray['image'] = $params->imageUrl;
            }
            $admin = $this->getModel()->updateOrCreate(['id'=>$params->id],$paramsArray);
            DB::commit();
            return $admin;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }


}
