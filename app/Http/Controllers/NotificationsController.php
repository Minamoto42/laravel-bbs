<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    /**
     * NotificationsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Notifications list page
     *
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        // Get the current user's notifications
        $notifications = Auth::user()->notifications()->paginate(20);

        // Mark the unread notifications as read
        Auth::user()->markAsRead();
        return view('notifications.index', compact('notifications'));
    }
}
