<?php

namespace App\Services\Reports;

use Illuminate\Support\Collection;

class HistoricalBalanceCalculator
{
    public function inventory(Collection $transactions, float $openingBalance): Collection
    {
        return $this->withRunningBalance($transactions, $openingBalance);
    }

    public function fuel(Collection $transactions): Collection
    {
        return $this->withRunningBalance($transactions, 0.0);
    }

    private function withRunningBalance(Collection $transactions, float $balance): Collection
    {
        return $transactions->map(function ($transaction) use (&$balance) {
            if ($transaction->movement_type === 'وارد') {
                $balance += (float) $transaction->quantity;
            } else {
                $balance -= (float) $transaction->quantity;
            }

            $transaction->running_balance = $balance;

            return $transaction;
        });
    }
}
