<?php

namespace App\Http\Controllers\Front\Dashboard;

use App\Models\Notification;
use App\Http\Controllers\Controller;
use App\Http\Repositories\NotificationRepository;

class NotificationController extends Controller
{
    private NotificationRepository $notificationRepository;

    public function __construct()
    {
        parent::__construct();
        $this->notificationRepository = new NotificationRepository();
        $this->breadcrumbs[route('front.index')] = ['title' => __('Home')];
    }

    public function index()
    {
        $page = request('page', 1);
        $this->breadcrumbTitle = __('Notifications');
        $this->breadcrumbs['javascript: {};'] = ['title' => __('Notifications')];
        $this->notificationRepository->setPaginate(25);
        $notifications = $this->notificationRepository->list();
        return view('front.dashboard.notification.index', ['notifications' => $notifications, 'page' => $page]);
    }
}
