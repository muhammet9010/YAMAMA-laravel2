<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotifcationController extends Controller
{
  public function MarkAsRead_all(Request $request)
  {

    $userUnreadNotification = auth()->user()->unreadNotifications;

    if ($userUnreadNotification) {
      $userUnreadNotification->markAsRead();
      return back();
    }
  }


  public function unreadNotifications_count()

  {
    return auth()->user()->unreadNotifications->count();
  }

  public function unreadNotifications()

  {
    foreach (auth()->user()->unreadNotifications as $notification) {

      return $notification->data['title'];
    }
  }
}
