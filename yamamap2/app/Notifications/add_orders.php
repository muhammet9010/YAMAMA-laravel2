<?php

namespace App\Notifications;

use App\Models\order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class add_orders extends Notification
{
  use Queueable;

  /**
   * Create a new notification instance.
   */
  private $order;

  /**
   * Get the notification's delivery channels.
   *
   * @return array<int, string>
   */
  public function __construct(order $order)
  {
    $this->order = $order;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return ['database'];
  }



  public function toDatabase($notifiable)
  {
    return [

      'id' => $this->order->id,
      'title' => 'تم اضافة فاتورة جديد بواسطة :',
      'user' => Auth::user()?->name,

    ];
  }
}
