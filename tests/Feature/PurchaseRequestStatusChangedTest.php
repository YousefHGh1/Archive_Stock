<?php

namespace Tests\Feature;

use App\Models\Purchase;
use App\Models\User;
use App\Models\PurchaseRequestStatusLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PurchaseRequestStatusChangedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that status change creates a log entry.
     *
     * @return void
     */
    public function test_status_change_creates_log()
    {
        $user = User::factory()->create();
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $this->actingAs($user);

        $response = $this->post(route('purchase.update-status', $purchase->id), [
            'status' => 'purchase_order',
            'description' => 'Test description',
            'notes' => 'Test notes',
        ]);

        $response->assertRedirect();

        // Check that log was created
        $this->assertDatabaseHas('purchase_request_status_logs', [
            'purchase_id' => $purchase->id,
            'from_status' => 'pending',
            'to_status' => 'purchase_order',
            'changed_by' => $user->id,
        ]);

        // Check that purchase status was updated
        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'status' => 'purchase_order',
        ]);
    }

    /**
     * Test that event is fired when status changes.
     *
     * @return void
     */
    public function test_event_is_fired_on_status_change()
    {
        $user = User::factory()->create();
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $this->actingAs($user);

        // Listen for the event
        \Event::fake(\App\Events\PurchaseRequestStatusChanged::class);

        $this->post(route('purchase.update-status', $purchase->id), [
            'status' => 'purchase_order',
            'description' => 'Test description',
            'notes' => 'Test notes',
        ]);

        \Event::assertDispatched(\App\Events\PurchaseRequestStatusChanged::class, function ($event) use ($purchase) {
            return $event->purchase->id === $purchase->id
                && $event->fromStatus === 'pending'
                && $event->toStatus === 'purchase_order';
        });
    }

    /**
     * Test that notification is sent to requester on status change.
     *
     * @return void
     */
    public function test_notification_sent_to_requester()
    {
        $user = User::factory()->create();
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        $this->actingAs($user);

        \Notification::fake();

        $this->post(route('purchase.update-status', $purchase->id), [
            'status' => 'purchase_order',
            'description' => 'Test description',
            'notes' => 'Test notes',
        ]);

        \Notification::assertSentTo($user, \App\Notifications\PurchaseRequestStatusChangedNotification::class);
    }

    /**
     * Test that status cannot be changed without president signature.
     *
     * @return void
     */
    public function test_status_change_requires_president_signature()
    {
        $user = User::factory()->create();
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'president_signature' => false,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('purchase.update-status', $purchase->id), [
            'status' => 'purchase_order',
            'description' => 'Test description',
            'notes' => 'Test notes',
        ]);

        $response->assertSessionHas('warning');

        // Check that status was not changed
        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'status' => 'pending',
        ]);
    }

    /**
     * Test that status can be changed with president signature.
     *
     * @return void
     */
    public function test_status_change_with_president_signature()
    {
        $user = User::factory()->create();
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'president_signature' => true,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('purchase.update-status', $purchase->id), [
            'status' => 'purchase_order',
            'description' => 'Test description',
            'notes' => 'Test notes',
        ]);

        $response->assertRedirect();

        // Check that status was changed
        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'status' => 'purchase_order',
        ]);
    }
}
