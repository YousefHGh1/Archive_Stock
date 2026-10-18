<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PurchaseRequestStatusChangedNotification extends Notification
{
    use Queueable;

    public $purchase;
    public $fromStatus;
    public $toStatus;
    public $note;

    public function __construct($purchase, $fromStatus, $toStatus, $note = null)
    {
        $this->purchase = $purchase;
        $this->fromStatus = $fromStatus;
        $this->toStatus = $toStatus;
        $this->note = $note;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $statusLabels = config('purchase.status_labels');
        $fromLabel = $statusLabels[$this->fromStatus] ?? $this->fromStatus;
        $toLabel = $statusLabels[$this->toStatus] ?? $this->toStatus;

        return (new MailMessage)
                    ->subject('تغيير حالة طلب الشراء')
                    ->line('تم تغيير حالة طلب الشراء رقم: ' . $this->purchase->serial_number)
                    ->line('من: ' . $fromLabel)
                    ->line('إلى: ' . $toLabel)
                    ->line('الموضوع: ' . $this->purchase->subject)
                    ->when($this->note, function ($message) {
                        return $message->line('ملاحظة: ' . $this->note);
                    })
                    ->action('عرض الطلب', url('/purchase/' . $this->purchase->id))
                    ->line('شكراً لاستخدام النظام!');
    }

    public function toArray($notifiable)
    {
        return [
            'purchase_id' => $this->purchase->id,
            'serial_number' => $this->purchase->serial_number,
            'from_status' => $this->fromStatus,
            'to_status' => $this->toStatus,
            'note' => $this->note,
        ];
    }
}
