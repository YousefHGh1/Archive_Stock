<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PurchaseOrderNotification extends Notification
{
    use Queueable;

    private $purchaseOrderId;
    private $purchaseSubject;
    private $purchaseSerial;
    private $message;

    public function __construct($purchaseOrderId, $purchaseSubject, $purchaseSerial, $message)
    {
        $this->purchaseOrderId = $purchaseOrderId;
        $this->purchaseSubject = $purchaseSubject;
        $this->purchaseSerial = $purchaseSerial;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'purchase_order_id' => $this->purchaseOrderId,
            'purchase_subject' => $this->purchaseSubject,
            'purchase_serial' => $this->purchaseSerial,
            'message' => $this->message,
        ];
    }
}