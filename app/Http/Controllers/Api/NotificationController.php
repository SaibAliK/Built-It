<?php

namespace App\Http\Controllers\Api;

use App\Http\Libraries\ResponseBuilder;
use App\Http\Repositories\NotificationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{

    /**
     * @var NotificationRepository
     */
    private NotificationRepository $notificationRepository;

    public function __construct()
    {
        parent::__construct();
        $this->notificationRepository = new NotificationRepository();
    }

    public function notifications(Request $request)
    {
        $this->notificationRepository->setPaginate(20);
        $notifications = $this->notificationRepository->list();

        $response = new ResponseBuilder(200, __('Notifications'), $notifications);
        return $response->build();
    }

    public function notificationCount($type = '')
    {
        $notificationCount = $this->notificationRepository->count();
        $response = new ResponseBuilder(200, __('Notifications count'), ['notificationCount' => $notificationCount]);
        return $response->build();
    }

    public function isSeen()
    {
        $this->notificationRepository->seenAll();
        $response = new ResponseBuilder(200, __('notification seen'));
        return $response->build();
    }

    public function isViewed($notificationId)
    {
        $this->notificationRepository->read($notificationId);
        $response = new ResponseBuilder(200, __('notification viewed'));
        return $response->build();
    }

    public function deleteNotification(Request $request)
    {
        $this->notificationRepository->delete($request);
        $response = new ResponseBuilder(200, __('Notification deleted'), []);
        return $response->build();
    }

}
