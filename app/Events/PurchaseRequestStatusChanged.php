<?php

namespace App\Events;

use App\Models\Purchase;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PurchaseRequestStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $purchase;
    public $fromStatus;
    public $toStatus;
    public $changedBy;
    public $note;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Purchase $purchase, $fromStatus, $toStatus, $changedBy, $note = null)
    {
        $this->purchase = $purchase;
        $this->fromStatus = $fromStatus;
        $this->toStatus = $toStatus;
        $this->changedBy = $changedBy;
        $this->note = $note;
    }
}
