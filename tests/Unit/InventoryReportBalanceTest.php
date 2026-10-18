<?php

namespace Tests\Unit;

use App\Services\Reports\HistoricalBalanceCalculator;
use App\Services\Reports\OperationalReportCalculator;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Tests\TestCase;

class InventoryReportBalanceTest extends TestCase
{
    public function test_inventory_running_balance_starts_at_opening_balance(): void
    {
        $transactions = new Collection([
            (object) ['movement_type' => 'وارد', 'quantity' => 5],
            (object) ['movement_type' => 'صادر', 'quantity' => 3],
        ]);

        $result = (new HistoricalBalanceCalculator)->inventory($transactions, 10.0);

        $this->assertSame([15.0, 12.0], $result->pluck('running_balance')->all());
    }

    public function test_fuel_running_balance_preserves_fractional_quantities(): void
    {
        $transactions = new Collection([
            (object) ['movement_type' => 'وارد', 'quantity' => 10],
            (object) ['movement_type' => 'صادر', 'quantity' => 2.5],
        ]);

        $result = (new HistoricalBalanceCalculator)->fuel($transactions);

        $this->assertSame([10.0, 7.5], $result->pluck('running_balance')->all());
    }

    public function test_monthly_inventory_keeps_empty_months_and_carries_forward_the_balance(): void
    {
        $rows = (new OperationalReportCalculator)->monthlyInventory(
            Carbon::parse('2025-12-01'),
            Carbon::parse('2026-02-01'),
            10.0,
            ['2026-01' => 4.5],
            ['2026-02' => 2.0]
        );

        $this->assertSame(['2025-12', '2026-01', '2026-02'], $rows->pluck('period')->all());
        $this->assertSame([10.0, 10.0, 14.5], $rows->pluck('opening_balance')->all());
        $this->assertSame([10.0, 14.5, 12.5], $rows->pluck('closing_balance')->all());
    }

    public function test_monthly_fuel_average_includes_months_without_outgoing(): void
    {
        $rows = (new OperationalReportCalculator)->monthlyFuel(
            collect([(object) ['id' => 3, 'name' => 'سولار']]),
            ['2026-01', '2026-02', '2026-03'],
            collect([
                (object) ['typesfuel_id' => 3, 'period' => '2026-01', 'quantity' => 12],
                (object) ['typesfuel_id' => 3, 'period' => '2026-03', 'quantity' => 6],
            ])
        );

        $this->assertSame([12.0, 0.0, 6.0], $rows->pluck('quantity')->all());
        $this->assertSame([6.0, 6.0, 6.0], $rows->pluck('period_average')->all());
    }
}
