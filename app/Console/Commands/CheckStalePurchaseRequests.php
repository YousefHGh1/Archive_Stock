<?php

namespace App\Console\Commands;

use App\Models\Purchase;
use App\Models\User;
use App\Notifications\StalePurchaseRequestNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CheckStalePurchaseRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'purchase-requests:check-stale';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for stale purchase requests and send SLA alerts';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $slaThresholds = config('purchase.sla_thresholds');
        $responsibleRoles = config('purchase.responsible_roles');

        foreach ($slaThresholds as $status => $thresholdDays) {
            $cutoffDate = now()->subDays($thresholdDays);

            // Find stale requests
            $staleRequests = Purchase::where('status', $status)
                ->where('updated_at', '<', $cutoffDate)
                ->get();

            foreach ($staleRequests as $purchase) {
                // Check if alert was sent in the last 24 hours
                $recentAlert = DB::table('purchase_request_sla_alerts')
                    ->where('purchase_id', $purchase->id)
                    ->where('status', $status)
                    ->where('created_at', '>=', now()->subHours(24))
                    ->exists();

                if ($recentAlert) {
                    $this->info("Skipping purchase {$purchase->serial_number} - alert sent in last 24 hours");
                    continue;
                }

                // Calculate days stale
                $daysStale = now()->diffInDays($purchase->updated_at);

                // Get responsible role for this status
                $responsibleRole = $responsibleRoles[$status] ?? null;

                if ($responsibleRole) {
                    // Notify responsible users
                    $responsibleUsers = User::role($responsibleRole)->get();

                    if ($responsibleUsers->count() > 0) {
                        Notification::send($responsibleUsers, new StalePurchaseRequestNotification(
                            $purchase,
                            $status,
                            $daysStale
                        ));

                        // Log the alert
                        DB::table('purchase_request_sla_alerts')->insert([
                            'purchase_id' => $purchase->id,
                            'status' => $status,
                            'days_stale' => $daysStale,
                            'created_at' => now(),
                        ]);

                        $this->info("Alert sent for purchase {$purchase->serial_number} (status: {$status}, days stale: {$daysStale})");
                    }
                }
            }
        }

        $this->info('SLA check completed successfully.');
        return Command::SUCCESS;
    }
}
