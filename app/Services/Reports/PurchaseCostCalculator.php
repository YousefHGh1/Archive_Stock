<?php

namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;

class PurchaseCostCalculator
{
    /**
     * متوسط كلفة الوحدة المرجح لكل صنف من فواتير الشراء الفعّالة حتى تاريخ محدّد.
     *
     * تُطبَّق نفس سياسة التقييم المعتمدة في تقارير الصرف: سعر الوحدة مضروبًا
     * في معامل العملة المحفوظ وقت فاتورة الشراء (currency_value_at_time).
     *
     * @param  array<int>  $itemIds
     * @return array<int, array{avg_cost: float|null, purchases_count: int, last_purchase_date: string|null}>
     */
    public function averageCosts(array $itemIds, string $asOf): array
    {
        if ($itemIds === []) {
            return [];
        }

        $purchases = DB::table('invoice_products')
            ->join('invoices', 'invoices.id', '=', 'invoice_products.invoice_id')
            ->whereIn('invoice_products.item_id', $itemIds)
            ->where('invoices.status', 'active')
            ->whereNull('invoices.deleted_at')
            ->whereDate('invoices.voucher_date', '<=', $asOf)
            ->select(
                'invoice_products.item_id',
                'invoice_products.quantity',
                'invoice_products.price',
                'invoices.currency_value_at_time',
                'invoices.voucher_date',
                'invoices.id as invoice_id'
            )
            ->orderBy('invoices.voucher_date')
            ->orderBy('invoices.id')
            ->get();

        return $this->compute($purchases);
    }

    /**
     * حساب النتائج من صفوف المشتريات — منطق خالص قابل للاختبار دون اتصال بقاعدة البيانات.
     *
     * @return array<int, array{avg_cost: float|null, purchases_count: int, last_purchase_date: string|null}>
     */
    public function compute(iterable $purchases): array
    {
        $aggregated = [];

        foreach ($purchases as $purchase) {
            $itemId = (int) $purchase->item_id;
            $aggregated[$itemId] ??= [
                'total_cost' => 0.0,
                'total_qty' => 0.0,
                'invoices' => [],
                'last_purchase_date' => null,
            ];

            $quantity = (float) $purchase->quantity;
            $currencyValue = (float) ($purchase->currency_value_at_time ?? 1);

            $aggregated[$itemId]['total_cost'] += (float) $purchase->price * $currencyValue * $quantity;
            $aggregated[$itemId]['total_qty'] += $quantity;
            $aggregated[$itemId]['invoices'][(int) $purchase->invoice_id] = true;

            $date = $purchase->voucher_date ?? null;
            if ($date !== null && ($aggregated[$itemId]['last_purchase_date'] === null || $date > $aggregated[$itemId]['last_purchase_date'])) {
                $aggregated[$itemId]['last_purchase_date'] = $date;
            }
        }

        $results = [];

        foreach ($aggregated as $itemId => $data) {
            $results[$itemId] = [
                'avg_cost' => $data['total_qty'] > 0 ? $data['total_cost'] / $data['total_qty'] : null,
                'purchases_count' => count($data['invoices']),
                'last_purchase_date' => $data['last_purchase_date'],
            ];
        }

        return $results;
    }
}
