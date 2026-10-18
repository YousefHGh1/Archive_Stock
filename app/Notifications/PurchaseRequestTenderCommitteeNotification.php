<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchaseRequestTenderCommitteeNotification extends Notification
{
    use Queueable;

    public $purchase;

    public function __construct($purchase)
    {
        $this->purchase = $purchase;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('طلب شراء جديد - لجنة العطاءات')
                    ->line('تم تحويل طلب شراء جديد للجنة العطاءات')
                    ->line('رقم الطلب: ' . $this->purchase->serial_number)
                    ->line('الموضوع: ' . $this->purchase->subject)
                    ->line('مقدم الطلب: ' . ($this->purchase->requester_name ?? '-'))
                    ->action('عرض الطلب', url('/purchase/' . $this->purchase->id))
                    ->line('يرجى مراجعة الطلب وإضافة قرار المجلس البلدي.');
    }

    public function toArray($notifiable)
    {
        return [
            'purchase_id' => $this->purchase->id,
            'serial_number' => $this->purchase->serial_number,
            'subject' => $this->purchase->subject,
            'message' => 'تم تحويل طلب شراء جديد للجنة العطاءات',
        ];
    }
}
