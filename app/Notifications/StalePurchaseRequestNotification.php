<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StalePurchaseRequestNotification extends Notification
{
    use Queueable;

    public $purchase;
    public $status;
    public $daysStale;

    public function __construct($purchase, $status, $daysStale)
    {
        $this->purchase = $purchase;
        $this->status = $status;
        $this->daysStale = $daysStale;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $statusLabels = config('purchase.status_labels');
        $statusLabel = $statusLabels[$this->status] ?? $this->status;

        return (new MailMessage)
                    ->subject('تنبيه: طلب شراء متأخر - SLA')
                    ->line('طلب شراء رقم: ' . $this->purchase->serial_number . ' متأخر عن المعالجة')
                    ->line('الحالة الحالية: ' . $statusLabel)
                    ->line('مدة التأخير: ' . $this->daysStale . ' يوم')
                    ->line('الموضوع: ' . $this->purchase->subject)
                    ->action('عرض الطلب', url('/purchase/' . $this->purchase->id))
                    ->line('يرجى مراجعة الطلب واتخاذ الإجراء اللازم.');
    }

    public function toArray($notifiable)
    {
        return [
            'purchase_id' => $this->purchase->id,
            'serial_number' => $this->purchase->serial_number,
            'status' => $this->status,
            'days_stale' => $this->daysStale,
            'message' => 'طلب شراء متأخر عن المعالجة',
        ];
    }
}
