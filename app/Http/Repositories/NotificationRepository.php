<?php

namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Notification;

class NotificationRepository extends Repository
{
    public function __construct()
    {
        $this->setModel(new Notification());
    }

    public function count()
    {
        $userId = $this->getUser()->id;
        return $this->getModel()->where(['receiver_id' => $userId, 'is_seen' => 0])->count();

    }

    public function list()
    {
        $userId = $this->getUser()->id;
        return $this->getModel()->where(['receiver_id' => $userId])->whereHas('sender')->whereHas('receiver')->with('sender:id,image,user_name,supplier_name,user_type')->orderBy('id', 'DESC')->paginate($this->getPaginate());
    }

    public function seenAll()
    {
        $userId = $this->getUser()->id;
        $this->getModel()->where(['receiver_id' => $userId])->update(['is_seen' => 1]);
        return true;
    }

    public function read($notificationId)
    {
        $userId = $this->getUser()->id;
        $this->getModel()->where(['receiver_id' => $userId, 'id' => $notificationId])->update(['is_read' => 1]);
        return 1;
    }

    public function delete($request)
    {
        $userId = $this->getUser()->id;
        $query = $this->getModel()->where(['receiver_id' => $userId]);
        if ($request->has('notifications')) {
            if (count($request->notifications) > 0) {
                $query->whereIn('id', $request->notifications);
            }
        }
        $query->forceDelete();
        return 1;
    }
}
