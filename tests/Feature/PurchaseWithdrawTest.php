<?php

namespace Tests\Feature;

use App\Models\Purchase;
use App\Models\User;
use App\Models\PurchaseRequestStatusLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseWithdrawTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that owner can withdraw a pending purchase request.
     *
     * @return void
     */
    public function test_owner_can_withdraw_pending_purchase()
    {
        $user = User::factory()->create();
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'president_signature' => false,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('purchase.withdraw', $purchase->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Check that purchase was marked as withdrawn
        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'status' => 'withdrawn',
            'withdrawn' => true,
        ]);

        // Check that log was created
        $this->assertDatabaseHas('purchase_request_status_logs', [
            'purchase_id' => $purchase->id,
            'from_status' => 'pending',
            'to_status' => 'withdrawn',
            'changed_by' => $user->id,
        ]);
    }

    /**
     * Test that non-owner cannot withdraw a purchase request.
     *
     * @return void
     */
    public function test_non_owner_cannot_withdraw_purchase()
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $purchase = Purchase::factory()->create([
            'user_id' => $owner->id,
            'status' => 'pending',
            'president_signature' => false,
        ]);

        $this->actingAs($otherUser);

        $response = $this->post(route('purchase.withdraw', $purchase->id));

        $response->assertStatus(403);

        // Check that purchase was not withdrawn
        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'status' => 'pending',
            'withdrawn' => false,
        ]);
    }

    /**
     * Test that purchase cannot be withdrawn after president signature.
     *
     * @return void
     */
    public function test_cannot_withdraw_after_president_signature()
    {
        $user = User::factory()->create();
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'president_signature' => true,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('purchase.withdraw', $purchase->id));

        $response->assertStatus(403);

        // Check that purchase was not withdrawn
        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'status' => 'pending',
            'withdrawn' => false,
        ]);
    }

    /**
     * Test that purchase cannot be withdrawn if not in pending status.
     *
     * @return void
     */
    public function test_cannot_withdraw_non_pending_purchase()
    {
        $user = User::factory()->create();
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'status' => 'purchase_order',
            'president_signature' => true,
        ]);

        $this->actingAs($user);

        $response = $this->post(route('purchase.withdraw', $purchase->id));

        $response->assertStatus(403);

        // Check that purchase was not withdrawn
        $this->assertDatabaseHas('purchases', [
            'id' => $purchase->id,
            'status' => 'purchase_order',
            'withdrawn' => false,
        ]);
    }
}