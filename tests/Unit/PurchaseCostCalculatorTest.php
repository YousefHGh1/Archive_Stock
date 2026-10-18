<?php

namespace Tests\Unit;

use App\Services\Reports\PurchaseCostCalculator;
use Tests\TestCase;

class PurchaseCostCalculatorTest extends TestCase
{
    public function test_weighted_average_applies_currency_rate_at_purchase_time(): void
    {
        $purchases = collect([
            (object) ['item_id' => 1, 'quantity' => 5, 'price' => 10, 'currency_value_at_time' => 2, 'voucher_date' => '2026-01-01', 'invoice_id' => 10],
            (object) ['item_id' => 1, 'quantity' => 3, 'price' => 12, 'currency_value_at_time' => 1, 'voucher_date' => '2026-02-01', 'invoice_id' => 11],
        ]);

        $result = (new PurchaseCostCalculator)->compute($purchases);

        // (10×2×5 + 12×1×3) ÷ (5+3) = 136 ÷ 8 = 17
        $this->assertEqualsWithDelta(17.0, $result[1]['avg_cost'], 0.0001);
        $this->assertSame(2, $result[1]['purchases_count']);
        $this->assertSame('2026-02-01', $result[1]['last_purchase_date']);
    }

    public function test_missing_currency_rate_defaults_to_one(): void
    {
        $purchases = collect([
            (object) ['item_id' => 2, 'quantity' => 4, 'price' => 25, 'currency_value_at_time' => null, 'voucher_date' => '2026-03-05', 'invoice_id' => 20],
        ]);

        $result = (new PurchaseCostCalculator)->compute($purchases);

        $this->assertEqualsWithDelta(25.0, $result[2]['avg_cost'], 0.0001);
    }

    public function test_duplicate_lines_of_the_same_invoice_count_as_one_purchase(): void
    {
        $purchases = collect([
            (object) ['item_id' => 3, 'quantity' => 2, 'price' => 100, 'currency_value_at_time' => 1, 'voucher_date' => '2026-04-01', 'invoice_id' => 30],
            (object) ['item_id' => 3, 'quantity' => 6, 'price' => 200, 'currency_value_at_time' => 1, 'voucher_date' => '2026-04-01', 'invoice_id' => 30],
        ]);

        $result = (new PurchaseCostCalculator)->compute($purchases);

        // (2×100 + 6×200) ÷ 8 = 1400 ÷ 8 = 175
        $this->assertEqualsWithDelta(175.0, $result[3]['avg_cost'], 0.0001);
        $this->assertSame(1, $result[3]['purchases_count']);
    }

    public function test_zero_quantity_returns_null_average_instead_of_division_by_zero(): void
    {
        $purchases = collect([
            (object) ['item_id' => 4, 'quantity' => 0, 'price' => 50, 'currency_value_at_time' => 1, 'voucher_date' => '2026-05-01', 'invoice_id' => 40],
        ]);

        $result = (new PurchaseCostCalculator)->compute($purchases);

        $this->assertNull($result[4]['avg_cost']);
        $this->assertSame(1, $result[4]['purchases_count']);
    }

    public function test_items_without_purchases_produce_no_cost_entry(): void
    {
        $result = (new PurchaseCostCalculator)->compute([]);

        $this->assertSame([], $result);
    }
}
