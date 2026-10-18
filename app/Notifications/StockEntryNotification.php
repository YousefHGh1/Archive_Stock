<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockEntryNotification extends Notification
{
    use Queueable;

    public $purchaseId;
    public $serialNumber;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($purchaseId, $serialNumber, $message)
    {
        $this->purchaseId = $purchaseId;
        $this->serialNumber = $serialNumber;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('إشعار إدخال للمخازن')
                    ->line($this->message)
                    ->action('عرض الطلب', url('/purchase/' . $this->purchaseId))
                    ->line('شكراً لاستخدام النظام!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'purchase_id' => $this->purchaseId,
            'serial_number' => $this->serialNumber,
            'message' => $this->message,
        ];
    }
}
