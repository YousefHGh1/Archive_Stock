<?php

namespace Tests\Feature;

use App\Models\Purchase;
use App\Models\User;
use App\Models\PurchaseRequestStatusLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StalePurchaseRequestAlertTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that SLA alert is sent for stale purchase requests.
     *
     * @return void
     */
    public function test_sla_alert_sent_for_stale_requests()
    {
        $user = User::factory()->create();
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'updated_at' => now()->subDays(3), // 3 days old, threshold is 2 days
        ]);

        Notification::fake();

        $this->artisan('purchase-requests:check-stale')
            ->expectsOutput('SLA check completed successfully.');

        // Check that alert was logged
        $this->assertDatabaseHas('purchase_request_sla_alerts', [
            'purchase_id' => $purchase->id,
            'status' => 'pending',
            'days_stale' => 3,
        ]);
    }

    /**
     * Test that SLA alert is not sent for recent purchase requests.
     *
     * @return void
     */
    public function test_sla_alert_not_sent_for_recent_requests()
    {
        $user = User::factory()->create();
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'updated_at' => now()->subDay(), // 1 day old, threshold is 2 days
        ]);

        $this->artisan('purchase-requests:check-stale')
            ->expectsOutput('SLA check completed successfully.');

        // Check that no alert was created
        $this->assertDatabaseMissing('purchase_request_sla_alerts', [
            'purchase_id' => $purchase->id,
        ]);
    }

    /**
     * Test that SLA alert is not sent twice within 24 hours.
     *
     * @return void
     */
    public function test_sla_alert_not_sent_twice_within_24_hours()
    {
        $user = User::factory()->create();
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'updated_at' => now()->subDays(3),
        ]);

        // Create an alert that was sent 12 hours ago
        DB::table('purchase_request_sla_alerts')->insert([
            'purchase_id' => $purchase->id,
            'status' => 'pending',
            'days_stale' => 3,
            'created_at' => now()->subHours(12),
        ]);

        $this->artisan('purchase-requests:check-stale')
            ->expectsOutput('SLA check completed successfully.');

        // Check that only one alert exists
        $alertCount = DB::table('purchase_request_sla_alerts')
            ->where('purchase_id', $purchase->id)
            ->where('status', 'pending')
            ->count();

        $this->assertEquals(1, $alertCount);
    }

    /**
     * Test SLA alert for different statuses.
     *
     * @return void
     */
    public function test_sla_alert_for_different_statuses()
    {
        $user = User::factory()->create();

        // Create purchases with different statuses
        $purchaseOrder = Purchase::factory()->create([
            'user_id' => $user->id,
            'status' => 'purchase_order',
            'updated_at' => now()->subDays(6), // threshold is 5 days
        ]);

        $tenderCommittee = Purchase::factory()->create([
            'user_id' => $user->id,
            'status' => 'tender_committee',
            'updated_at' => now()->subDays(11), // threshold is 10 days
        ]);

        $rentRepair = Purchase::factory()->create([
            'user_id' => $user->id,
            'status' => 'rent_repair',
            'updated_at' => now()->subDays(4), // threshold is 3 days
        ]);

        $this->artisan('purchase-requests:check-stale')
            ->expectsOutput('SLA check completed successfully.');

        // Check that alerts were created for all three
        $this->assertDatabaseHas('purchase_request_sla_alerts', [
            'purchase_id' => $purchaseOrder->id,
            'status' => 'purchase_order',
            'days_stale' => 6,
        ]);

        $this->assertDatabaseHas('purchase_request_sla_alerts', [
            'purchase_id' => $tenderCommittee->id,
            'status' => 'tender_committee',
            'days_stale' => 11,
        ]);

        $this->assertDatabaseHas('purchase_request_sla_alerts', [
            'purchase_id' => $rentRepair->id,
            'status' => 'rent_repair',
            'days_stale' => 4,
        ]);
    }
}