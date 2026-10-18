<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CreatepurchaseNot extends Notification
{
    use Queueable;

    private $purchase_id;
    private $purchase_subject;
    private $purchase_serial;
    private $purchase_create;

    public function __construct($purchase_id, $purchase_subject, $purchase_serial, $purchase_create)
    {
        $this->purchase_id = $purchase_id;
        $this->purchase_subject = $purchase_subject;
        $this->purchase_serial = $purchase_serial;
        $this->purchase_create = $purchase_create;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'purchase_id' => $this->purchase_id,
            'purchase_subject' => $this->purchase_subject,
            'purchase_serial' => $this->purchase_serial,
            'purchase_create' => $this->purchase_create,
        ];
    }
}
