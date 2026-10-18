<?php

namespace App\Http\Controllers;

use App\Exports\ReportTableExport;
use App\Http\Controllers\Concerns\CalculatesFuelBalances;
use App\Models\Category;
use App\Models\Item;
use App\Models\Section;
use App\Models\Supplier;
use App\Models\SupplierItem;
use App\Models\TypesFuel;
use App\Services\Reports\PurchaseCostCalculator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class CostReportController extends Controller
{
    use CalculatesFuelBalances;

    public function __construct(private PurchaseCostCalculator $costCalculator)
    {
        $this->middleware('permission:report-read');
    }

    /**
     * تقرير تكلفة المخزون: رصيد كل صنف مقيَّم بمتوسط كلفة المرجّح لمشترياته الفعّالة.
     */
    public function inventoryCost(Request $request)
    {
        $filters = $request->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            'export' => ['nullable', 'in:excel,pdf'],
        ]);

        $items = Item::with(['category:id,category_name', 'unit:id,unit_name'])
            ->when(isset($filters['category_id']), fn ($query) => $query->where('category_id', $filters['category_id']))
            ->when(isset($filters['item_id']), fn ($query) => $query->where('id', $filters['item_id']))
            ->orderBy('item_name')
            ->get(['id', 'item_num', 'item_name', 'category_id', 'unit_id', 'balance']);

        $costs = $this->costCalculator->averageCosts($items->pluck('id')->all(), today()->toDateString());

        $rows = $items->map(function (Item $item) use ($costs) {
            $cost = $costs[$item->id] ?? null;
            $avgCost = $cost['avg_cost'] ?? null;

            return [
                'item' => $item,
                'avg_cost' => $avgCost,
                'total_value' => $avgCost === null ? null : round((float) $item->balance * $avgCost, 2),
                'purchases_count' => $cost['purchases_count'] ?? 0,
                'last_purchase_date' => $cost['last_purchase_date'] ?? null,
            ];
        });

        if (isset($filters['export'])) {
            return $this->exportReport(
                $filters['export'],
                'تكلفة المخزون — متوسط الكلفة المرجح',
                $this->inventoryCostHeadings(),
                $rows->map(fn ($row) => [
                    $row['item']->item_num,
                    $row['item']->item_name,
                    optional($row['item']->category)->category_name ?? '—',
                    (float) $row['item']->balance,
                    optional($row['item']->unit)->unit_name ?? '—',
                    $row['avg_cost'] ?? '—',
                    $row['total_value'] ?? '—',
                    $row['purchases_count'],
                    $row['last_purchase_date'] ?? '—',
                ])->all(),
                'inventory-cost',
                $this->inventoryCostSubtitle($filters)
            );
        }

        return view('inventory.report.inventory_cost', [
            'rows' => $rows,
            'categories' => Category::orderBy('category_name')->get(['id', 'category_name']),
            'items' => Item::orderBy('item_name')->get(['id', 'item_num', 'item_name']),
            'totalValue' => $rows->sum('total_value'),
            'missingCostCount' => $rows->filter(fn ($row) => $row['avg_cost'] === null)->count(),
        ]);
    }

    /**
     * تقرير مقارنة أسعار الصنف نفسه بين الموردين خلال فترة.
     * يُظهر جميع الموردين حتى إن لم تكن لهم مشتريات مطابقة (قيم صفرية).
     */
    public function supplierComparison(Request $request)
    {
        $filters = $request->validate([
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:supplier_items,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'export' => ['nullable', 'in:excel,pdf'],
        ]);

        $rows = $this->supplierComparisonQuery($filters)->get();

        if (isset($filters['export'])) {
            return $this->exportReport(
                $filters['export'],
                'مقارنة أسعار المشتريات بين الموردين',
                $this->supplierComparisonHeadings(),
                $rows->map(fn ($row) => [
                    $row->item_num ?? '—',
                    $row->item_name ?? '—',
                    $row->supplier_item_name,
                    (float) $row->total_quantity,
                    $row->unit_name ?? '—',
                    $row->total_quantity > 0 ? round((float) $row->total_value / (float) $row->total_quantity, 2) : '—',
                    round((float) $row->total_value, 2),
                    (int) $row->invoices_count,
                    $row->first_purchase ?? '—',
                    $row->last_purchase ?? '—',
                ])->all(),
                'supplier-comparison',
                $this->supplierComparisonSubtitle($filters)
            );
        }

        return view('inventory.report.supplier_comparison', [
            'rows' => $rows,
            'items' => Item::orderBy('item_name')->get(['id', 'item_num', 'item_name']),
            'suppliers' => SupplierItem::orderBy('supplier_item_name')->get(['id', 'supplier_item_name']),
        ]);
    }

    /**
     * استعلام مقارنة الموردين: جميع الموردين (LEFT JOIN) مع تجميع حسب الصنف والمورد.
     */
    private function supplierComparisonQuery(array $filters)
    {
        return DB::table('supplier_items')
            ->leftJoin('invoices', function ($join) use ($filters) {
                $join->on('invoices.supplier_item_id', '=', 'supplier_items.id')
                    ->where('invoices.status', 'active')
                    ->whereNull('invoices.deleted_at')
                    ->when(isset($filters['start_date']), fn ($query) => $query->whereDate('invoices.voucher_date', '>=', $filters['start_date']))
                    ->when(isset($filters['end_date']), fn ($query) => $query->whereDate('invoices.voucher_date', '<=', $filters['end_date']));
            })
            ->leftJoin('invoice_products', function ($join) use ($filters) {
                $join->on('invoice_products.invoice_id', '=', 'invoices.id')
                    ->when(isset($filters['item_id']), fn ($query) => $query->where('invoice_products.item_id', $filters['item_id']));
            })
            ->leftJoin('items', function ($join) use ($filters) {
                if (isset($filters['item_id'])) {
                    $join->on('items.id', '=', DB::raw((int) $filters['item_id']));
                } else {
                    $join->on('items.id', '=', 'invoice_products.item_id');
                }
            })
            ->leftJoin('units', 'units.id', '=', 'items.unit_id')
            ->when(isset($filters['supplier_id']), fn ($query) => $query->where('supplier_items.id', $filters['supplier_id']))
            ->groupBy(
                'supplier_items.id',
                'supplier_items.supplier_item_name',
                'items.id',
                'items.item_num',
                'items.item_name',
                'units.unit_name'
            )
            ->select(
                'supplier_items.supplier_item_name',
                'items.item_num',
                'items.item_name',
                'units.unit_name',
                DB::raw('COALESCE(SUM(invoice_products.quantity), 0) as total_quantity'),
                DB::raw('COALESCE(SUM(invoice_products.quantity * invoice_products.price * COALESCE(invoices.currency_value_at_time, 1)), 0) as total_value'),
                DB::raw('COUNT(DISTINCT CASE WHEN invoice_products.invoice_id IS NOT NULL THEN invoices.id END) as invoices_count'),
                DB::raw('MIN(CASE WHEN invoice_products.invoice_id IS NOT NULL THEN invoices.voucher_date END) as first_purchase'),
                DB::raw('MAX(CASE WHEN invoice_products.invoice_id IS NOT NULL THEN invoices.voucher_date END) as last_purchase')
            )
            ->orderByRaw('items.item_name IS NULL')
            ->orderBy('items.item_name')
            ->orderByDesc('total_value')
            ->orderBy('supplier_items.supplier_item_name');
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

    private function inventoryCostHeadings(): array
    {
        return ['رقم الصنف', 'اسم الصنف', 'الفئة', 'الرصيد الحالي', 'الوحدة', 'متوسط كلفة الوحدة', 'قيمة الرصيد', 'عدد فواتير الشراء', 'آخر شراء'];
    }

    private function supplierComparisonHeadings(): array
    {
        return ['رقم الصنف', 'الصنف', 'المورد', 'إجمالي الكمية', 'الوحدة', 'متوسط سعر الوحدة', 'إجمالي القيمة', 'عدد الفواتير', 'أول شراء', 'آخر شراء'];
    }

    private function inventoryCostSubtitle(array $filters): string
    {
        $labels = ['كما في '.today()->toDateString()];

        if (isset($filters['category_id'])) {
            $labels[] = 'الفئة: '.Category::findOrFail($filters['category_id'])->category_name;
        }

        if (isset($filters['item_id'])) {
            $labels[] = 'الصنف: '.Item::findOrFail($filters['item_id'])->item_name;
        }

        return implode(' — ', $labels);
    }

    private function supplierComparisonSubtitle(array $filters): string
    {
        $labels = [$this->dateRangeLabel($filters)];

        if (isset($filters['item_id'])) {
            $labels[] = 'الصنف: '.Item::findOrFail($filters['item_id'])->item_name;
        }

        if (isset($filters['supplier_id'])) {
            $labels[] = 'المورد: '.SupplierItem::findOrFail($filters['supplier_id'])->supplier_item_name;
        }

        return implode(' — ', $labels);
    }

    private function dateRangeLabel(array $filters): string
    {
        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            return 'من '.$filters['start_date'].' إلى '.$filters['end_date'];
        }

        if (isset($filters['start_date'])) {
            return 'من '.$filters['start_date'];
        }

        if (isset($filters['end_date'])) {
            return 'حتى '.$filters['end_date'];
        }

        return 'جميع التواريخ';
    }

    /**
     * تقرير تكلفة المحروقات: متوسط سعر الشراء لكل نوع وقيمة الوارد بالفترة وقيمة الرصيد الحالي.
     * التقييم يعتمد متوسط الكلفة المرجح للتوريدات التي سُجّل لها سعر فقط.
     */
    public function fuelCost(Request $request)
    {
        $filters = $request->validate([
            'typesfuel_id' => ['nullable', 'integer', 'exists:types_fuels,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'export' => ['nullable', 'in:excel,pdf'],
        ]);

        $types = TypesFuel::when(isset($filters['typesfuel_id']), fn ($query) => $query->where('id', $filters['typesfuel_id']))
            ->orderBy('id')
            ->get();

        $periodStats = DB::table('diesels')
            ->when(isset($filters['typesfuel_id']), fn ($query) => $query->where('typesfuel_id', $filters['typesfuel_id']))
            ->when(isset($filters['start_date']), fn ($query) => $query->whereDate('date', '>=', $filters['start_date']))
            ->when(isset($filters['end_date']), fn ($query) => $query->whereDate('date', '<=', $filters['end_date']))
            ->select('typesfuel_id')
            ->selectRaw('COALESCE(SUM(quantity), 0) as total_qty')
            ->selectRaw('COALESCE(SUM(CASE WHEN price IS NOT NULL THEN quantity END), 0) as priced_qty')
            ->selectRaw('COALESCE(SUM(CASE WHEN price IS NOT NULL THEN quantity * price ELSE 0 END), 0) as total_value')
            ->selectRaw('SUM(CASE WHEN price IS NULL THEN 1 ELSE 0 END) as unpriced_rows')
            ->groupBy('typesfuel_id')
            ->get()
            ->keyBy('typesfuel_id');

        $averagePrices = $this->fuelAveragePrices();
        $balances = $this->fuelRemainingByType(new Request)['remainingByType'];

        $rows = $types->map(function (TypesFuel $type) use ($periodStats, $averagePrices, $balances) {
            $period = $periodStats[$type->id] ?? null;
            $averagePrice = $averagePrices[$type->id] ?? null;
            $balance = (float) ($balances[$type->id] ?? 0);

            return [
                'type' => $type,
                'incoming_qty' => (float) ($period->total_qty ?? 0),
                'priced_qty' => (float) ($period->priced_qty ?? 0),
                'incoming_value' => round((float) ($period->total_value ?? 0), 2),
                'period_avg' => $period && $period->priced_qty > 0 ? round((float) $period->total_value / (float) $period->priced_qty, 2) : null,
                'all_time_avg' => $averagePrice === null ? null : round($averagePrice, 2),
                'unpriced_rows' => (int) ($period->unpriced_rows ?? 0),
                'balance' => $balance,
                'balance_value' => $averagePrice === null ? null : round($balance * $averagePrice, 2),
            ];
        });

        if (isset($filters['export'])) {
            return $this->exportReport(
                $filters['export'],
                'تكلفة المحروقات',
                $this->fuelCostHeadings(),
                $rows->map(fn ($row) => [
                    $row['type']->name,
                    $row['incoming_qty'],
                    $row['priced_qty'],
                    $row['incoming_value'],
                    $row['period_avg'] ?? '—',
                    $row['all_time_avg'] ?? '—',
                    $row['balance'],
                    $row['balance_value'] ?? '—',
                    $row['unpriced_rows'],
                ])->all(),
                'fuel-cost',
                $this->fuelCostSubtitle($filters)
            );
        }

        return view('inventory.report.fuel_cost', [
            'rows' => $rows,
            'types' => TypesFuel::orderBy('id')->get(['id', 'name']),
        ]);
    }

    /**
     * متوسط سعر الشراء المرجح لكل نوع محروقات للتوريدات المسعرة فقط (حتى اليوم).
     *
     * @return array<int, float|null>
     */
    private function fuelAveragePrices(): array
    {
        $stats = DB::table('diesels')
            ->whereNotNull('price')
            ->select('typesfuel_id')
            ->selectRaw('SUM(quantity) as total_qty')
            ->selectRaw('SUM(quantity * price) as total_value')
            ->groupBy('typesfuel_id')
            ->get();

        $prices = [];

        foreach ($stats as $stat) {
            $prices[(int) $stat->typesfuel_id] = $stat->total_qty > 0 ? (float) $stat->total_value / (float) $stat->total_qty : null;
        }

        return $prices;
    }

    /**
     * تقرير مقارنة أسعار المحروقات حسب المورد والنوع خلال فترة.
     * يُظهر جميع الموردين حتى بلا توريدات مطابقة (قيم صفرية).
     */
    public function fuelPriceComparison(Request $request)
    {
        $filters = $request->validate([
            'typesfuel_id' => ['nullable', 'integer', 'exists:types_fuels,id'],
            'supplier_id' => ['nullable', 'integer', 'exists:suppliers,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'export' => ['nullable', 'in:excel,pdf'],
        ]);

        $rows = DB::table('suppliers')
            ->leftJoin('diesels', function ($join) use ($filters) {
                $join->on('diesels.supplier_id', '=', 'suppliers.id')
                    ->when(isset($filters['typesfuel_id']), fn ($query) => $query->where('diesels.typesfuel_id', $filters['typesfuel_id']))
                    ->when(isset($filters['start_date']), fn ($query) => $query->whereDate('diesels.date', '>=', $filters['start_date']))
                    ->when(isset($filters['end_date']), fn ($query) => $query->whereDate('diesels.date', '<=', $filters['end_date']));
            })
            ->leftJoin('types_fuels', 'types_fuels.id', '=', 'diesels.typesfuel_id')
            ->when(isset($filters['supplier_id']), fn ($query) => $query->where('suppliers.id', $filters['supplier_id']))
            ->groupBy('suppliers.id', 'suppliers.name_supplier', 'types_fuels.id', 'types_fuels.name')
            ->select('suppliers.name_supplier', 'types_fuels.name as fuel_name')
            ->selectRaw('COALESCE(SUM(diesels.quantity), 0) as total_qty')
            ->selectRaw('COALESCE(SUM(CASE WHEN diesels.price IS NOT NULL THEN diesels.quantity END), 0) as priced_qty')
            ->selectRaw('COALESCE(SUM(CASE WHEN diesels.price IS NOT NULL THEN diesels.quantity * diesels.price ELSE 0 END), 0) as total_value')
            ->selectRaw('SUM(CASE WHEN diesels.price IS NULL THEN 1 ELSE 0 END) as unpriced_rows')
            ->selectRaw('MIN(diesels.date) as first_supply')
            ->selectRaw('MAX(diesels.date) as last_supply')
            ->orderByRaw('types_fuels.name IS NULL')
            ->orderBy('types_fuels.name')
            ->orderByDesc('total_value')
            ->orderBy('suppliers.name_supplier')
            ->get();

        if (isset($filters['export'])) {
            return $this->exportReport(
                $filters['export'],
                'مقارنة أسعار المحروقات بين الموردين',
                $this->fuelPriceComparisonHeadings(),
                $rows->map(fn ($row) => [
                    $row->fuel_name ?? '—',
                    $row->name_supplier,
                    (float) $row->total_qty,
                    (float) $row->priced_qty,
                    $row->priced_qty > 0 ? round((float) $row->total_value / (float) $row->priced_qty, 2) : '—',
                    round((float) $row->total_value, 2),
                    (int) $row->unpriced_rows,
                    $row->first_supply ?? '—',
                    $row->last_supply ?? '—',
                ])->all(),
                'fuel-price-comparison',
                $this->fuelCostSubtitle($filters)
            );
        }

        return view('inventory.report.fuel_price_comparison', [
            'rows' => $rows,
            'types' => TypesFuel::orderBy('id')->get(['id', 'name']),
            'suppliers' => Supplier::orderBy('name_supplier')->get(['id', 'name_supplier']),
        ]);
    }

    /**
     * توزيع صرف المحروقات على الأقسام (قيمة تقديرية بتوسط سعر الشراء الحالي).
     */
    public function fuelSectionCost(Request $request)
    {
        $filters = $request->validate([
            'typesfuel_id' => ['nullable', 'integer', 'exists:types_fuels,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'export' => ['nullable', 'in:excel,pdf'],
        ]);

        $issued = DB::table('diesel_exports')
            ->join('sections', 'sections.id', '=', 'diesel_exports.section_id')
            ->join('types_fuels', 'types_fuels.id', '=', 'diesel_exports.typesfuel_id')
            ->when(isset($filters['typesfuel_id']), fn ($query) => $query->where('diesel_exports.typesfuel_id', $filters['typesfuel_id']))
            ->when(isset($filters['section_id']), fn ($query) => $query->where('sections.id', $filters['section_id']))
            ->when(isset($filters['start_date']), fn ($query) => $query->whereDate('diesel_exports.date', '>=', $filters['start_date']))
            ->when(isset($filters['end_date']), fn ($query) => $query->whereDate('diesel_exports.date', '<=', $filters['end_date']))
            ->groupBy('sections.id', 'sections.name_section', 'types_fuels.id', 'types_fuels.name')
            ->select('sections.name_section', 'types_fuels.id as typesfuel_id', 'types_fuels.name as fuel_name')
            ->selectRaw('SUM(diesel_exports.quantity) as total_qty')
            ->orderBy('sections.name_section')
            ->orderBy('types_fuels.name')
            ->get();

        $averagePrices = $this->fuelAveragePrices();

        $rows = $issued->map(function ($row) use ($averagePrices) {
            $averagePrice = $averagePrices[$row->typesfuel_id] ?? null;

            return [
                'row' => $row,
                'avg_price' => $averagePrice === null ? null : round($averagePrice, 2),
                'estimated_value' => $averagePrice === null ? null : round((float) $row->total_qty * $averagePrice, 2),
            ];
        });

        if (isset($filters['export'])) {
            return $this->exportReport(
                $filters['export'],
                'توزيع صرف المحروقات على الأقسام (تقديري)',
                $this->fuelSectionCostHeadings(),
                $rows->map(fn ($entry) => [
                    $entry['row']->name_section,
                    $entry['row']->fuel_name,
                    (float) $entry['row']->total_qty,
                    $entry['avg_price'] ?? '—',
                    $entry['estimated_value'] ?? '—',
                ])->all(),
                'fuel-section-cost',
                $this->fuelCostSubtitle($filters)
            );
        }

        return view('inventory.report.fuel_section_cost', [
            'rows' => $rows,
            'types' => TypesFuel::orderBy('id')->get(['id', 'name']),
            'sections' => Section::orderBy('name_section')->get(['id', 'name_section']),
        ]);
    }

    private function fuelCostHeadings(): array
    {
        return ['نوع المحروقات', 'الوارد بالفترة (لتر)', 'الكمية المسعرة (لتر)', 'قيمة الوارد', 'متوسط سعر الفترة', 'متوسط سعر الشراء الكلي', 'الرصيد الحالي (لتر)', 'قيمة الرصيد (تقديرية)', 'توريدات بلا سعر'];
    }

    private function fuelPriceComparisonHeadings(): array
    {
        return ['نوع المحروقات', 'المورد', 'إجمالي الكمية (لتر)', 'الكمية المسعرة (لتر)', 'متوسط سعر الوحدة', 'إجمالي القيمة', 'توريدات بلا سعر', 'أول توريد', 'آخر توريد'];
    }

    private function fuelSectionCostHeadings(): array
    {
        return ['الدائرة', 'نوع المحروقات', 'الكمية المصروفة (لتر)', 'متوسط سعر الشراء', 'القيمة التقديرية'];
    }

    private function fuelCostSubtitle(array $filters): string
    {
        $labels = [$this->dateRangeLabel($filters)];

        if (isset($filters['typesfuel_id'])) {
            $labels[] = 'النوع: '.TypesFuel::findOrFail($filters['typesfuel_id'])->name;
        }

        if (isset($filters['supplier_id'])) {
            $labels[] = 'المورد: '.Supplier::findOrFail($filters['supplier_id'])->name_supplier;
        }

        if (isset($filters['section_id'])) {
            $labels[] = 'الدائرة: '.Section::findOrFail($filters['section_id'])->name_section;
        }

        return implode(' — ', $labels);
    }
}
