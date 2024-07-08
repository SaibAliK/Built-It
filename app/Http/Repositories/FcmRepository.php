<?php


namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Fcm;


class FcmRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(new Fcm());
    }

    public function all()
    {
        return $this->getModel()->all();
    }

    public function save($fcm_token, $user_id, $id, $selected_lang)
    {
        $this->getModel()->updateOrCreate(['id' => $id], ['fcm_token'=>$fcm_token, 'user_id'=>$user_id, 'selected_lang'=>$selected_lang]);
    }

    public function get($user_id = 0, $fcm_token=null, $id = 0){
        $query = $this->getQuery();
        if ($user_id > 0){
            $query->where('user_id', $user_id);
        }
        if (!is_null($fcm_token)){
            $query->where('fcm_token', $fcm_token);
        }
        if ($id > 0){
            $query->where('id', $id);
        }

        return $query->first();
    }

    public function delete($user_id, $fcm_token){
        $fcmToken = $this->get($user_id,$fcm_token);
        if (!is_null($fcmToken)){
            return $fcmToken->delete();
        }
        return true;
    }

    public function checkAndSave($fcm_token, $user_id){
        $fcm = $this->get($user_id, $fcm_token);
        if (is_null($fcm)){
            $this->save($fcm_token, $user_id, 0, app()->getLocale());
        }
    }
}
