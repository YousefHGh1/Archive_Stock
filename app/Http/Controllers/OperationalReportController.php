<?php

namespace App\Http\Controllers;

use App\Exports\ReportTableExport;
use App\Models\Item;
use App\Models\Section;
use App\Models\SubSection;
use App\Models\TypesFuel;
use App\Services\Reports\OperationalReportCalculator;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class OperationalReportController extends Controller
{
    public function __construct(private OperationalReportCalculator $calculator)
    {
        $this->middleware('permission:report-read');
    }

    public function monthlyStock(Request $request)
    {
        $filters = $request->validate([
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            'start_month' => ['nullable', 'date_format:Y-m'],
            'end_month' => ['nullable', 'date_format:Y-m'],
            'export' => ['nullable', 'in:excel,pdf'],
        ]);

        $startLabel = $filters['start_month'] ?? today()->startOfYear()->format('Y-m');
        $endLabel = $filters['end_month'] ?? today()->format('Y-m');
        if ($startLabel > $endLabel) {
            return back()->withErrors(['end_month' => 'يجب أن يكون شهر النهاية مساويًا أو بعد شهر البداية.'])->withInput();
        }
        if (isset($filters['export']) && ! isset($filters['item_id'])) {
            return back()->withErrors(['item_id' => 'اختر صنفًا قبل تصدير التقرير.'])->withInput();
        }

        $startMonth = Carbon::createFromFormat('!Y-m', $startLabel);
        $endMonth = Carbon::createFromFormat('!Y-m', $endLabel);
        $startDate = $startMonth->toDateString();
        $endDate = $endMonth->copy()->endOfMonth()->toDateString();
        $item = isset($filters['item_id'])
            ? Item::with('unit:id,unit_name')->findOrFail($filters['item_id'])
            : null;
        $months = $this->monthKeys($startMonth, $endMonth);
        $rows = collect();

        if ($item) {
            $openingBalance = (float) $item->open_balance
                + $this->inventoryQuantityBefore('invoice_products', 'invoices', 'invoice_id', $item->id, $startDate)
                - $this->inventoryQuantityBefore('invoice_export_products', 'invoice_exports', 'invoice_export_id', $item->id, $startDate);
            $incoming = $this->monthlyInventoryMovements(
                'invoice_products',
                'invoices',
                'invoice_id',
                $item->id,
                $startDate,
                $endDate
            );
            $outgoing = $this->monthlyInventoryMovements(
                'invoice_export_products',
                'invoice_exports',
                'invoice_export_id',
                $item->id,
                $startDate,
                $endDate
            );
            $rows = $this->calculator->monthlyInventory($startMonth, $endMonth, $openingBalance, $incoming, $outgoing);
        }

        if (isset($filters['export']) && $item) {
            return $this->exportReport(
                $filters['export'],
                'الحركة الشهرية للمخزون',
                ['الشهر', 'الرصيد الافتتاحي', 'الوارد', 'الصادر', 'الرصيد الختامي'],
                $rows->map(fn ($row) => [
                    $row->period,
                    $row->opening_balance,
                    $row->incoming,
                    $row->outgoing,
                    $row->closing_balance,
                ])->all(),
                'monthly-stock-movement',
                $item->item_num.' - '.$item->item_name.' — '.$startLabel.' إلى '.$endLabel
            );
        }

        return view('inventory.report.monthly_stock', [
            'item' => $item,
            'items' => Item::orderBy('item_num')->get(['id', 'item_num', 'item_name']),
            'rows' => $rows,
            'months' => $months,
            'startMonth' => $startLabel,
            'endMonth' => $endLabel,
        ]);
    }

    public function topIssuedItems(Request $request)
    {
        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'sub_section_id' => ['nullable', 'integer', 'exists:sub_sections,id'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
            'export' => ['nullable', 'in:excel,pdf'],
        ]);
        $limit = (int) ($filters['limit'] ?? 20);
        $query = $this->stockOutgoingByItemQuery($filters)
            ->orderByDesc('total_quantity')
            ->orderBy('items.item_num')
            ->limit($limit);
        $items = $query->get();

        if (isset($filters['export'])) {
            return $this->exportReport(
                $filters['export'],
                'الأصناف الأكثر صرفًا',
                ['الترتيب', 'رقم الصنف', 'اسم الصنف', 'الوحدة', 'إجمالي الكمية المصروفة'],
                $items->values()->map(fn ($item, $index) => [
                    $index + 1,
                    $item->item_num,
                    $item->item_name,
                    $item->unit_name ?? '—',
                    (float) $item->total_quantity,
                ])->all(),
                'top-issued-items',
                $this->dateRangeLabel($filters).' — أعلى '.$limit.' صنف'
            );
        }

        return view('inventory.report.top_issued_items', [
            'items' => $items,
            'sections' => Section::orderBy('name_section')->get(['id', 'name_section']),
            'subSections' => SubSection::with('section:id,name_section')->orderBy('name')->get(['id', 'name', 'section_id']),
            'limit' => $limit,
        ]);
    }

    public function fuelAverage(Request $request)
    {
        $defaultStart = today()->startOfYear()->toDateString();
        $defaultEnd = today()->toDateString();
        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'typesfuel_id' => ['nullable', 'integer', 'exists:types_fuels,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'sub_section_id' => ['nullable', 'integer', 'exists:sub_sections,id'],
            'export' => ['nullable', 'in:excel,pdf'],
        ]);
        $endDate = $filters['end_date'] ?? $defaultEnd;
        $startDate = $filters['start_date'] ?? Carbon::parse($endDate)->startOfYear()->toDateString();
        if ($startDate > $endDate) {
            return back()->withErrors(['end_date' => 'يجب أن يكون تاريخ النهاية مساويًا أو بعد تاريخ البداية.'])->withInput();
        }
        $months = $this->monthKeys(
            Carbon::parse($startDate)->startOfMonth(),
            Carbon::parse($endDate)->startOfMonth()
        );

        $types = TypesFuel::query()
            ->when(isset($filters['typesfuel_id']), fn ($query) => $query->whereKey($filters['typesfuel_id']))
            ->orderBy('name')
            ->get(['id', 'name']);

        $movementTotals = DB::table('diesel_exports')
            ->select('typesfuel_id')
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as period")
            ->selectRaw('SUM(quantity) as quantity')
            ->whereBetween('date', [$startDate, $endDate])
            ->when(isset($filters['section_id']), fn ($query) => $query->where('section_id', $filters['section_id']))
            ->when(isset($filters['sub_section_id']), fn ($query) => $query->where('sub_section_id', $filters['sub_section_id']))
            ->when(isset($filters['typesfuel_id']), fn ($query) => $query->where('typesfuel_id', $filters['typesfuel_id']))
            ->groupBy('typesfuel_id', 'period')
            ->get();
        $rows = $this->calculator->monthlyFuel($types, $months, $movementTotals);

        if (isset($filters['export'])) {
            return $this->exportReport(
                $filters['export'],
                'متوسط استهلاك المحروقات',
                ['نوع المحروقات', 'الشهر', 'الصرف (لتر)', 'متوسط الصرف الشهري للفترة (لتر)'],
                $rows->map(fn ($row) => [
                    $row->type_name,
                    $row->period,
                    $row->quantity,
                    $row->period_average,
                ])->all(),
                'fuel-average-consumption',
                $startDate.' إلى '.$endDate.' — المتوسط يشمل الأشهر بلا صرف'
            );
        }

        return view('inventory.report.fuel_average', [
            'rows' => $rows,
            'types' => TypesFuel::orderBy('name')->get(['id', 'name']),
            'sections' => Section::orderBy('name_section')->get(['id', 'name_section']),
            'subSections' => SubSection::with('section:id,name_section')->orderBy('name')->get(['id', 'name', 'section_id']),
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    private function inventoryQuantityBefore(string $detailsTable, string $vouchersTable, string $foreignKey, int $itemId, string $beforeDate): float
    {
        return (float) DB::table($detailsTable)
            ->join($vouchersTable, $vouchersTable.'.id', '=', $detailsTable.'.'.$foreignKey)
            ->where($detailsTable.'.item_id', $itemId)
            ->where($vouchersTable.'.status', 'active')
            ->whereNull($vouchersTable.'.deleted_at')
            ->where($vouchersTable.'.voucher_date', '<', $beforeDate)
            ->sum($detailsTable.'.quantity');
    }

    private function monthlyInventoryMovements(
        string $detailsTable,
        string $vouchersTable,
        string $foreignKey,
        int $itemId,
        string $startDate,
        string $endDate
    ): array {
        return DB::table($detailsTable)
            ->join($vouchersTable, $vouchersTable.'.id', '=', $detailsTable.'.'.$foreignKey)
            ->where($detailsTable.'.item_id', $itemId)
            ->where($vouchersTable.'.status', 'active')
            ->whereNull($vouchersTable.'.deleted_at')
            ->whereBetween($vouchersTable.'.voucher_date', [$startDate, $endDate])
            ->selectRaw("DATE_FORMAT({$vouchersTable}.voucher_date, '%Y-%m') as period")
            ->selectRaw('SUM('.$detailsTable.'.quantity) as quantity')
            ->groupBy('period')
            ->get()
            ->mapWithKeys(fn ($row) => [$row->period => (float) $row->quantity])
            ->all();
    }

    private function stockOutgoingByItemQuery(array $filters): Builder
    {
        return DB::table('invoice_export_products')
            ->join('invoice_exports', 'invoice_exports.id', '=', 'invoice_export_products.invoice_export_id')
            ->join('items', 'items.id', '=', 'invoice_export_products.item_id')
            ->leftJoin('units', 'units.id', '=', 'items.unit_id')
            ->leftJoin('sub_sections', 'sub_sections.id', '=', 'invoice_exports.subSection_id')
            ->leftJoin('sections', 'sections.id', '=', 'sub_sections.section_id')
            ->where('invoice_exports.status', 'active')
            ->whereNull('invoice_exports.deleted_at')
            ->when(isset($filters['start_date']), fn ($query) => $query->whereDate('invoice_exports.voucher_date', '>=', $filters['start_date']))
            ->when(isset($filters['end_date']), fn ($query) => $query->whereDate('invoice_exports.voucher_date', '<=', $filters['end_date']))
            ->when(isset($filters['section_id']), fn ($query) => $query->where('sections.id', $filters['section_id']))
            ->when(isset($filters['sub_section_id']), fn ($query) => $query->where('sub_sections.id', $filters['sub_section_id']))
            ->select('items.id', 'items.item_num', 'items.item_name', 'units.unit_name')
            ->selectRaw('SUM(invoice_export_products.quantity) as total_quantity')
            ->groupBy('items.id', 'items.item_num', 'items.item_name', 'units.unit_name');
    }

    private function monthKeys(Carbon $startMonth, Carbon $endMonth): array
    {
        return collect(CarbonPeriod::create(
            $startMonth->copy()->startOfMonth(),
            '1 month',
            $endMonth->copy()->startOfMonth()
        ))->map(fn (Carbon $month) => $month->format('Y-m'))->all();
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
