<?php

namespace App\Http\Controllers;

use App\Exports\ReportTableExport;
use App\Models\Item;
use App\Models\SupplierItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:report-read', ['only' => ['index', 'byItem', 'bySupplier', 'invoiceValues', 'supplierTotals']]);
    }

    /**
     * صفحة فهرس تقارير فواتير الشراء
     */
    public function index()
    {
        $suppliers = SupplierItem::get(['id', 'supplier_item_name']);
        $items = Item::get(['id', 'item_num', 'item_name']);

        return view('inventory.report.purchase_index', compact('suppliers', 'items'));
    }

    /**
     * التقرير الأول: المشتريات حسب الصنف
     *
     * جميع عمليات شراء صنف معيّن بين تاريخين مع تفاصيل كل عملية
     * (التاريخ، الكمية، السعر، المورد، رقم الفاتورة) وإجمالي سعر كل صنف.
     */
    public function byItem(Request $request)
    {
        $filters = $this->validateFilters($request, true);
        $rows = $this->basePurchaseQuery();

        if (isset($filters['item_id'])) {
            $rows->where('invoice_products.item_id', $filters['item_id']);
        }

        $this->applySupplierFilters($rows, $filters);
        $this->applyDateRange($rows, $filters);

        $rows = $rows->orderBy('invoices.voucher_date')->get();
        if (isset($filters['export'])) {
            return $this->exportReport(
                $filters['export'],
                'المشتريات حسب الصنف',
                ['رقم الصنف', 'الصنف', 'المورد', 'رقم الفاتورة', 'رقم السند', 'التاريخ', 'الكمية', 'سعر الوحدة بعد التحويل', 'الإجمالي بعد التحويل'],
                $rows->map(fn ($row) => [
                    $row->item_num,
                    $row->item_name,
                    $row->supplier_name,
                    $row->invoice_no,
                    $row->voucher_no,
                    $row->voucher_date,
                    (float) $row->quantity,
                    (float) $row->price * (float) ($row->currency_value_at_time ?? 1),
                    (float) $row->total_price,
                ])->all(),
                'purchases-by-item',
                $this->dateRangeLabel($filters)
            );
        }

        $items = Item::get(['id', 'item_num', 'item_name']);
        $suppliers = SupplierItem::get(['id', 'supplier_item_name']);

        return view('inventory.report.purchase_by_item', compact('rows', 'items', 'suppliers'));
    }

    /**
     * التقرير الثاني: المشتريات حسب المورد
     *
     * كل ما تم شراؤه من مورد معيّن بين تاريخين: الأصناف والكميات والتواريخ والإجماليات.
     */
    public function bySupplier(Request $request)
    {
        $filters = $this->validateFilters($request, false);
        $rows = $this->basePurchaseQuery();

        $this->applySupplierFilters($rows, $filters);
        $this->applyDateRange($rows, $filters);

        $rows = $rows->orderBy('invoices.voucher_date')->orderBy('items.item_name')->get();

        // ملخص مجمّع حسب الصنف مع إجمالي السعر
        $itemSummary = $rows->groupBy('item_id')->map(function ($group) {
            return [
                'item_id' => $group->first()->item_id,
                'item_num' => $group->first()->item_num,
                'item_name' => $group->first()->item_name,
                'total_qty' => $group->sum('quantity'),
                'total_val' => $group->sum('total_price'),
            ];
        })->values();

        $totalQty = $rows->sum('quantity');
        $totalVal = $rows->sum('total_price');

        if (isset($filters['export'])) {
            return $this->exportReport(
                $filters['export'],
                'المشتريات حسب المورد',
                ['رقم الصنف', 'الصنف', 'المورد', 'رقم الفاتورة', 'رقم السند', 'التاريخ', 'الكمية', 'سعر الوحدة بعد التحويل', 'الإجمالي بعد التحويل'],
                $rows->map(fn ($row) => [
                    $row->item_num,
                    $row->item_name,
                    $row->supplier_name,
                    $row->invoice_no,
                    $row->voucher_no,
                    $row->voucher_date,
                    (float) $row->quantity,
                    (float) $row->price * (float) ($row->currency_value_at_time ?? 1),
                    (float) $row->total_price,
                ])->all(),
                'purchases-by-supplier',
                $this->dateRangeLabel($filters)
            );
        }

        $suppliers = SupplierItem::get(['id', 'supplier_item_name']);
        $items = Item::get(['id', 'item_num', 'item_name']);

        return view('inventory.report.purchase_by_supplier', compact('rows', 'itemSummary', 'totalQty', 'totalVal', 'suppliers', 'items'));
    }

    /**
     * التقرير الثالث: قيمة كل فاتورة لكل مورد
     *
     * تفصيل كل فاتورة شراء مرتبطة بمورد معيّن مع بيان القيمة الإجمالية لكل فاتورة.
     */
    public function invoiceValues(Request $request)
    {
        $filters = $this->validateFilters($request, false);
        $rows = $this->basePurchaseQuery();

        $this->applySupplierFilters($rows, $filters);
        $this->applyDateRange($rows, $filters);

        $rows = $rows->orderBy('invoices.voucher_date')->get();

        // تجميع البنود لكل فاتورة
        $invoices = $rows->groupBy('invoice_id')->map(function ($group) {
            $first = $group->first();

            return [
                'invoice_id' => $first->invoice_id,
                'voucher_no' => $first->voucher_no,
                'invoice_no' => $first->invoice_no,
                'voucher_date' => $first->voucher_date,
                'supplier_name' => $first->supplier_name,
                'total_qty' => $group->sum('quantity'),
                'total_value' => $group->sum('total_price'),
                'lines' => $group->values(),
            ];
        })->values();

        $grandTotalQty = $rows->sum('quantity');
        $grandTotalVal = $rows->sum('total_price');

        if (isset($filters['export'])) {
            return $this->exportReport(
                $filters['export'],
                'قيمة فواتير الشراء',
                ['رقم الفاتورة', 'رقم السند', 'التاريخ', 'المورد', 'إجمالي الكمية', 'إجمالي القيمة بعد التحويل'],
                $invoices->map(fn ($invoice) => [
                    $invoice['invoice_no'],
                    $invoice['voucher_no'],
                    $invoice['voucher_date'],
                    $invoice['supplier_name'],
                    (float) $invoice['total_qty'],
                    (float) $invoice['total_value'],
                ])->all(),
                'purchase-invoice-values',
                $this->dateRangeLabel($filters)
            );
        }

        $suppliers = SupplierItem::get(['id', 'supplier_item_name']);
        $items = Item::get(['id', 'item_num', 'item_name']);

        return view('inventory.report.purchase_invoice_values', compact('invoices', 'grandTotalQty', 'grandTotalVal', 'suppliers', 'items'));
    }

    /**
     * التقرير الرابع: إجمالي فواتير المورد
     *
     * المجموع الكلي لقيم جميع فواتير الشراء لكل مورد (كليًا أو بين تاريخين).
     */
    public function supplierTotals(Request $request)
    {
        $filters = $this->validateFilters($request, false);
        $rows = $this->basePurchaseQuery();

        $this->applySupplierFilters($rows, $filters);
        $this->applyDateRange($rows, $filters);

        $rows = $rows->get();

        $totals = $rows->groupBy('supplier_name')->map(function ($group, $supplierName) {
            return [
                'supplier_name' => $supplierName ?: 'بدون مورد',
                'total_qty' => $group->sum('quantity'),
                'total_value' => $group->sum('total_price'),
                'count' => $group->pluck('invoice_id')->unique()->count(),
            ];
        })->sortByDesc('total_value');

        $grandTotalQty = $rows->sum('quantity');
        $grandTotalVal = $rows->sum('total_price');

        if (isset($filters['export'])) {
            return $this->exportReport(
                $filters['export'],
                'إجمالي مشتريات الموردين',
                ['المورد', 'عدد الفواتير', 'إجمالي الكمية', 'إجمالي القيمة بعد التحويل'],
                $totals->map(fn ($total) => [
                    $total['supplier_name'],
                    $total['count'],
                    (float) $total['total_qty'],
                    (float) $total['total_value'],
                ])->values()->all(),
                'purchase-supplier-totals',
                $this->dateRangeLabel($filters)
            );
        }

        $suppliers = SupplierItem::get(['id', 'supplier_item_name']);
        $items = Item::get(['id', 'item_num', 'item_name']);

        return view('inventory.report.purchase_supplier_totals', compact('totals', 'grandTotalQty', 'grandTotalVal', 'suppliers', 'items'));
    }

    /**
     * استعلام قاعدة حركات الشراء: بنود الفواتير مع بيانات الصنف والمورد والإجمالي.
     */
    private function basePurchaseQuery()
    {
        return DB::table('invoice_products')
            ->join('invoices', 'invoices.id', '=', 'invoice_products.invoice_id')
            ->join('items', 'items.id', '=', 'invoice_products.item_id')
            ->leftJoin('supplier_items', 'supplier_items.id', '=', 'invoices.supplier_item_id')
            ->where('invoices.status', 'active')
            ->whereNull('invoices.deleted_at')
            ->select(
                'invoices.id as invoice_id',
                'invoices.voucher_no',
                'invoices.invoice_no',
                'invoices.voucher_date',
                'invoice_products.item_id',
                'items.item_num',
                'items.item_name',
                'invoice_products.quantity',
                'invoice_products.price',
                'invoices.currency_value_at_time',
                DB::raw('(invoice_products.quantity * invoice_products.price * COALESCE(invoices.currency_value_at_time, 1)) as total_price'),
                DB::raw('COALESCE(supplier_items.supplier_item_name, "—") as supplier_name'),
                DB::raw('COALESCE(supplier_items.id, 0) as supplier_id')
            );
    }

    /**
     * تطبيق فلتر المورد على الاستعلام.
     */
    private function applySupplierFilters($query, array $filters)
    {
        if (isset($filters['supplier_id'])) {
            $query->where('supplier_items.id', $filters['supplier_id']);
        }
    }

    /**
     * تطبيق نطاق التاريخين على الاستعلام.
     */
    private function applyDateRange($query, array $filters)
    {
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->whereBetween('invoices.voucher_date', [$filters['start_date'], $filters['end_date']]);
        } elseif (isset($filters['start_date'])) {
            $query->where('invoices.voucher_date', '>=', $filters['start_date']);
        } elseif (isset($filters['end_date'])) {
            $query->where('invoices.voucher_date', '<=', $filters['end_date']);
        }
    }

    private function validateFilters(Request $request, bool $allowItem): array
    {
        $rules = [
            'supplier_id' => ['nullable', 'integer', 'exists:supplier_items,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'export' => ['nullable', 'in:excel,pdf'],
        ];
        $rules['item_id'] = $allowItem
            ? ['nullable', 'integer', 'exists:items,id']
            : ['prohibited'];

        return $request->validate($rules);
    }

    private function dateRangeLabel(array $filters): string
    {
        return ($filters['start_date'] ?? 'بداية غير محددة').' إلى '.($filters['end_date'] ?? 'نهاية غير محددة');
    }

    private function exportReport(string $format, string $title, array $headings, array $rows, string $filename, ?string $subtitle = null)
    {
        if ($format === 'excel') {
            return Excel::download(new ReportTableExport($title, $subtitle, $headings, $rows), $filename.'.xlsx');
        }

        return Pdf::loadView('reports.pdf.table', compact('title', 'subtitle', 'headings', 'rows'))
            ->setPaper('a4', 'landscape')
            ->setOptions(['defaultFont' => 'DejaVu Sans'])
            ->download($filename.'.pdf');
    }
}
