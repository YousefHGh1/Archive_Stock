<?php

namespace App\Services\Reports;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class OperationalReportCalculator
{
    public function monthlyInventory(
        Carbon $startMonth,
        Carbon $endMonth,
        float $openingBalance,
        array $incomingByMonth,
        array $outgoingByMonth
    ): Collection {
        $balance = $openingBalance;

        return collect(CarbonPeriod::create(
            $startMonth->copy()->startOfMonth(),
            '1 month',
            $endMonth->copy()->startOfMonth()
        ))->map(function (Carbon $month) use (&$balance, $incomingByMonth, $outgoingByMonth) {
            $period = $month->format('Y-m');
            $incoming = (float) ($incomingByMonth[$period] ?? 0);
            $outgoing = (float) ($outgoingByMonth[$period] ?? 0);
            $opening = $balance;
            $balance += $incoming - $outgoing;

            return (object) [
                'period' => $period,
                'opening_balance' => $opening,
                'incoming' => $incoming,
                'outgoing' => $outgoing,
                'closing_balance' => $balance,
            ];
        });
    }

    public function monthlyFuel(
        Collection $types,
        array $months,
        Collection $movementTotals
    ): Collection {
        $totals = $movementTotals->keyBy(function ($movement) {
            return $movement->typesfuel_id.'|'.$movement->period;
        });

        return $types->flatMap(function ($type) use ($months, $totals) {
            $typeMonths = collect($months)->map(function ($month) use ($type, $totals) {
                $key = $type->id.'|'.$month;
                $movement = $totals->get($key);

                return (object) [
                    'typesfuel_id' => $type->id,
                    'type_name' => $type->name,
                    'period' => $month,
                    'quantity' => (float) ($movement->quantity ?? 0),
                ];
            });
            $average = $typeMonths->sum('quantity') / max(count($months), 1);

            return $typeMonths->map(function ($row) use ($average) {
                $row->period_average = $average;

                return $row;
            });
        })->values();
    }
}
