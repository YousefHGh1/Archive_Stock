<?php

namespace App\Http\Controllers;

use App\Exports\ReportTableExport;
use App\Models\Item;
use App\Models\Section;
use App\Models\SubSection;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExportReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:report-read', ['only' => ['index', 'byItem', 'bySection', 'invoiceValues', 'sectionTotals']]);
    }

    /**
     * صفحة فهرس تقارير فواتير الصرف مع روابط مباشرة للأقسام
     */
    public function index()
    {
        $sections = Section::get(['id', 'name_section']);
        $subSections = SubSection::with('section')->get();
        $items = Item::get(['id', 'item_num', 'item_name']);

        return view('inventory.report.export_index', compact('sections', 'subSections', 'items'));
    }

    /**
     * التقرير الأول: المصروفات حسب الصنف
     *
     * عرض جميع عمليات الصرف لصنف معيّن مع تفاصيل كل عملية
     * (التاريخ، الكمية، القسم المستفيد، رقم الفاتورة) والقيمة.
     */
    public function byItem(Request $request)
    {
        $rows = $this->baseExportQuery();

        if ($request->filled('item_id')) {
            $rows->where('invoice_export_products.item_id', $request->item_id);
        }

        $this->applySectionFilters($rows, $request);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $rows->whereBetween('invoice_exports.voucher_date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $rows->where('invoice_exports.voucher_date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $rows->where('invoice_exports.voucher_date', '<=', $request->end_date);
        }

        $rows = $rows->orderBy('invoice_exports.voucher_date')->get();

        $this->applyPointInTimeValues($rows);

        $items = Item::get(['id', 'item_num', 'item_name']);
        $sections = Section::get(['id', 'name_section']);
        $subSections = SubSection::with('section')->get();

        $grouped = $rows->groupBy('item_id');

        return view('inventory.report.export_by_item', compact('rows', 'items', 'sections', 'subSections', 'grouped'));
    }

    /**
     * التقرير الثاني: المصروفات حسب القسم
     *
     * عرض ما تم صرفه لقسم خلال فترة محّدة: الأصناف والكميات والتواريخ والقيم.
     * يدعم فلترة القسم الرئيسي أو الفرعي.
     */
    public function bySection(Request $request)
    {
        $filters = $request->validate([
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'sub_section_id' => ['nullable', 'integer', 'exists:sub_sections,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'export' => ['nullable', 'in:excel,pdf'],
        ]);
        $rows = $this->baseExportQuery();

        $this->applySectionFilters($rows, $request);
        if (isset($filters['item_id'])) {
            $rows->where('invoice_export_products.item_id', $filters['item_id']);
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $rows->whereBetween('invoice_exports.voucher_date', [$filters['start_date'], $filters['end_date']]);
        } elseif (isset($filters['start_date'])) {
            $rows->where('invoice_exports.voucher_date', '>=', $filters['start_date']);
        } elseif (isset($filters['end_date'])) {
            $rows->where('invoice_exports.voucher_date', '<=', $filters['end_date']);
        }

        $rows = $rows->orderBy('invoice_exports.voucher_date')->orderBy('items.item_name')->get();

        $this->applyPointInTimeValues($rows);

        // Keep quantities separate for each department, item, and unit.
        $itemSummary = $rows->groupBy(function ($row) {
            return $row->section_id.'|'.$row->sub_section_id.'|'.$row->item_id.'|'.$row->unit_id;
        })->map(function ($group) {
            return [
                'item_id' => $group->first()->item_id,
                'section_name' => $group->first()->section_name,
                'sub_section_name' => $group->first()->sub_section_name,
                'item_num' => $group->first()->item_num,
                'item_name' => $group->first()->item_name,
                'unit_name' => $group->first()->unit_name,
                'total_qty' => $group->sum('quantity'),
                'total_val' => $group->sum('value'),
            ];
        })->values();

        $totalVal = $rows->sum('value');

        if (isset($filters['export'])) {
            $detailRows = $rows->map(fn ($row) => [
                $row->item_num,
                $row->item_name,
                $row->section_name,
                $row->sub_section_name,
                $row->invoice_no,
                $row->voucher_no,
                $row->voucher_date,
                (float) $row->quantity,
                $row->unit_name ?? '—',
                (float) $row->avg_cost,
                (float) $row->value,
            ])->all();

            return $this->exportReport(
                $filters['export'],
                'المصروفات حسب القسم',
                ['رقم الصنف', 'الصنف', 'الدائرة', 'القسم الفرعي', 'رقم الفاتورة', 'رقم السند', 'التاريخ', 'الكمية', 'الوحدة', 'متوسط الكلفة', 'القيمة'],
                $detailRows,
                'stock-outgoing-by-section',
                ($filters['start_date'] ?? 'بداية غير محددة').' إلى '.($filters['end_date'] ?? 'نهاية غير محددة')
            );
        }

        $sections = Section::get(['id', 'name_section']);
        $subSections = SubSection::with('section')->get();

        return view('inventory.report.export_by_section', compact('rows', 'itemSummary', 'totalVal', 'sections', 'subSections', 'request'));
    }

    /**
     * التقرير الثالث: قيمة كل فاتورة لكل قسم
     *
     * تفصيل كل فاتورة صرف مرتبطة بقسم معيّن مع بيان القيمة الإجمالية لكل فاتورة.
     */
    public function invoiceValues(Request $request)
    {
        $rows = $this->baseExportQuery();

        $this->applySectionFilters($rows, $request);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $rows->whereBetween('invoice_exports.voucher_date', [$request->start_date, $request->end_date]);
        }

        $rows = $rows->orderBy('invoice_exports.voucher_date')->get();

        $this->applyPointInTimeValues($rows);

        // تجميع البنود لكل فاتورة
        $invoices = $rows->groupBy('invoice_id')->map(function ($group) {
            $first = $group->first();

            return [
                'invoice_id' => $first->invoice_id,
                'voucher_no' => $first->voucher_no,
                'invoice_no' => $first->invoice_no,
                'voucher_date' => $first->voucher_date,
                'section_name' => $first->section_name,
                'sub_section_name' => $first->sub_section_name,
                'total_qty' => $group->sum('quantity'),
                'total_value' => $group->sum('value'),
                'lines' => $group->values(),
            ];
        })->values();

        $grandTotalQty = $rows->sum('quantity');
        $grandTotalVal = $rows->sum('value');

        $sections = Section::get(['id', 'name_section']);
        $subSections = SubSection::with('section')->get();

        return view('inventory.report.export_invoice_values', compact('invoices', 'grandTotalQty', 'grandTotalVal', 'sections', 'subSections', 'request'));
    }

    /**
     * التقرير الرابع: إجمالي فواتير القسم
     *
     * احتساب المجموع الكلي لقيم جميع فواتير الصرف لكل قسم (كليًا أو ضمن فترة).
     */
    public function sectionTotals(Request $request)
    {
        $rows = $this->baseExportQuery();

        $this->applySectionFilters($rows, $request);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $rows->whereBetween('invoice_exports.voucher_date', [$request->start_date, $request->end_date]);
        }

        $rows = $rows->get();

        $this->applyPointInTimeValues($rows);

        $totals = $rows->groupBy('section_name')->map(function ($group, $sectionName) {
            return [
                'section_name' => $sectionName ?: 'بدون قسم',
                'total_qty' => $group->sum('quantity'),
                'total_value' => $group->sum('value'),
                'count' => $group->pluck('invoice_id')->unique()->count(),
            ];
        })->sortByDesc('total_value');

        $grandTotalQty = $rows->sum('quantity');
        $grandTotalVal = $rows->sum('value');

        $sections = Section::get(['id', 'name_section']);
        $subSections = SubSection::with('section')->get();

        return view('inventory.report.export_section_totals', compact('totals', 'grandTotalQty', 'grandTotalVal', 'sections', 'subSections', 'request'));
    }

    /**
     * استعلام قاعدة الحركات الأساسي: يوفّر بنود الصرف مع بيانات الصنف والقسم.
     */
    private function baseExportQuery()
    {
        return DB::table('invoice_export_products')
            ->join('invoice_exports', 'invoice_exports.id', '=', 'invoice_export_products.invoice_export_id')
            ->join('items', 'items.id', '=', 'invoice_export_products.item_id')
            ->leftJoin('units', 'units.id', '=', 'items.unit_id')
            ->leftJoin('sub_sections', 'sub_sections.id', '=', 'invoice_exports.subSection_id')
            ->leftJoin('sections', 'sections.id', '=', 'sub_sections.section_id')
            ->where('invoice_exports.status', 'active')
            ->whereNull('invoice_exports.deleted_at')
            ->select(
                'invoice_exports.id as invoice_id',
                'invoice_exports.voucher_no',
                'invoice_exports.invoice_no',
                'invoice_exports.voucher_date',
                'invoice_export_products.item_id',
                'items.item_num',
                'items.item_name',
                'items.unit_id',
                'units.unit_name',
                'invoice_export_products.quantity',
                DB::raw('COALESCE(sub_sections.name, "—") as sub_section_name'),
                DB::raw('COALESCE(sections.name_section, "—") as section_name'),
                DB::raw('COALESCE(sub_sections.id, 0) as sub_section_id'),
                DB::raw('COALESCE(sections.id, 0) as section_id')
            );
    }

    /**
     * تطبيق فلاتر القسم (الرئيسي أو الفرعي) على الاستعلام.
     */
    private function applySectionFilters($query, Request $request)
    {
        if ($request->filled('section_id')) {
            $query->where('sections.id', $request->section_id);
        }

        if ($request->filled('sub_section_id')) {
            $query->where('sub_sections.id', $request->sub_section_id);
        }
    }

    /**
     * تقييم قيمة كل بند صرف بالكلفة الفورية (نقطة-الزمن):
     * المتوسط المرجّح لمشتريات الصنف حتى تاريخ الصرف نفسه.
     */
    private function applyPointInTimeValues(Collection $rows)
    {
        if ($rows->isEmpty()) {
            return;
        }

        $maxDate = $rows->max('voucher_date');
        $itemIds = $rows->pluck('item_id')->unique()->values()->all();

        $series = $this->buildCostSeries($itemIds, $maxDate);

        foreach ($rows as $row) {
            $itemSeries = $series[$row->item_id] ?? [];
            $row->avg_cost = $this->averageCostAt($itemSeries, $row->voucher_date);
            $row->value = round((float) $row->quantity * $row->avg_cost, 2);
        }
    }

    /**
     * بناء سلاسل مشتريات (متوسط كلفة مرجح تراكمي) لكل صنف حتى تاريخ محدّد.
     *
     * @return array [item_id => [ ['date', 'cost', 'qty'], ... ] ]
     */
    private function buildCostSeries(array $itemIds, string $maxDate)
    {
        if (empty($itemIds)) {
            return [];
        }

        $purchases = DB::table('invoice_products')
            ->join('invoices', 'invoices.id', '=', 'invoice_products.invoice_id')
            ->select(
                'invoice_products.item_id',
                'invoice_products.quantity',
                'invoice_products.price',
                'invoices.currency_value_at_time',
                'invoices.voucher_date'
            )
            ->whereIn('invoice_products.item_id', $itemIds)
            ->where('invoices.status', 'active')
            ->whereNull('invoices.deleted_at')
            ->where('invoices.voucher_date', '<=', $maxDate)
            ->orderBy('invoices.voucher_date')
            ->orderBy('invoices.id')
            ->get();

        $series = [];

        foreach ($purchases as $p) {
            $series[$p->item_id][] = [
                'date' => $p->voucher_date,
                'cost' => (float) $p->price,
                'currency_value' => (float) ($p->currency_value_at_time ?? 1),
                'qty' => (float) $p->quantity,
            ];
        }

        return $series;
    }

    /**
     * حساب متوسط الكلفة المرجح حتى تاريخ معين من سلسلة مشتريات مصنّفة بالأقدم.
     */
    private function averageCostAt(array $series, string $date)
    {
        $totalCost = 0.0;
        $totalQty = 0.0;

        foreach ($series as $p) {
            if ($p['date'] > $date) {
                break;
            }

            $totalCost += $p['cost'] * $p['currency_value'] * $p['qty'];
            $totalQty += $p['qty'];
        }

        if ($totalQty <= 0) {
            return 0.0;
        }

        return $totalCost / $totalQty;
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
