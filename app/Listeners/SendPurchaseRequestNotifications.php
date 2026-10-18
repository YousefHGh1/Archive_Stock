<?php

namespace App\Listeners;

use App\Events\PurchaseRequestStatusChanged;
use App\Models\User;
use App\Notifications\PurchaseRequestStatusChangedNotification;
use App\Notifications\PurchaseRequestFinanceNotification;
use App\Notifications\PurchaseRequestTenderCommitteeNotification;
use App\Notifications\CreatepurchaseNot;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendPurchaseRequestNotifications implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  \App\Events\PurchaseRequestStatusChanged  $event
     * @return void
     */
    public function handle(PurchaseRequestStatusChanged $event)
    {
        $purchase = $event->purchase;
        $toStatus = $event->toStatus;
        $fromStatus = $event->fromStatus;

        // Always notify the requester (user who created the request)
        if ($purchase->user) {
            $purchase->user->notify(new PurchaseRequestStatusChangedNotification(
                $purchase,
                $fromStatus,
                $toStatus,
                $event->note
            ));
        }

        // Notify specific departments based on status change
        switch ($toStatus) {
            case 'purchase_order':
                // Notify purchases department (existing notification)
                $purchaseUsers = User::role('purchase')->get();
                if ($purchaseUsers->count() > 0) {
                    Notification::send($purchaseUsers, new CreatepurchaseNot($purchase));
                }
                break;

            case 'rent_repair':
                // Notify finance department
                $financeUsers = User::role('finance')->get();
                if ($financeUsers->count() > 0) {
                    Notification::send($financeUsers, new PurchaseRequestFinanceNotification($purchase));
                }
                break;

            case 'tender_committee':
                // Notify tender committee
                $tenderUsers = User::role('tender_committee')->get();
                if ($tenderUsers->count() > 0) {
                    Notification::send($tenderUsers, new PurchaseRequestTenderCommitteeNotification($purchase));
                }
                break;
        }
    }
}
