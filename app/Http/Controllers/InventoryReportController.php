<?php

namespace App\Http\Controllers;

use App\Exports\ReportTableExport;
use App\Models\Category;
use App\Models\Diesel;
use App\Models\DieselExport;
use App\Models\Item;
use App\Models\Section;
use App\Models\SubSection;
use App\Models\TypesFuel;
use App\Services\Reports\HistoricalBalanceCalculator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class InventoryReportController extends Controller
{
    public function __construct(private HistoricalBalanceCalculator $balanceCalculator)
    {
        $this->middleware('permission:report-read');
    }

    public function unifiedIndex()
    {
        return view('inventory.report.index');
    }

    public function lowStock(Request $request)
    {
        $filters = $request->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'export' => ['nullable', 'in:excel,pdf'],
        ]);

        $itemsQuery = Item::with(['category:id,category_name', 'unit:id,unit_name'])
            ->whereColumn('balance', '<=', 'low_limit')
            ->when(isset($filters['category_id']), function ($query) use ($filters) {
                $query->where('category_id', $filters['category_id']);
            })
            ->orderBy('balance')
            ->orderBy('item_name');

        $items = $itemsQuery->get([
            'id',
            'item_num',
            'item_name',
            'category_id',
            'unit_id',
            'balance',
            'low_limit',
        ]);

        $rows = $items->map(function (Item $item) {
            return [
                $item->item_num,
                $item->item_name,
                optional($item->category)->category_name ?? '—',
                optional($item->unit)->unit_name ?? '—',
                (float) $item->balance,
                (float) $item->low_limit,
                (float) $item->balance <= 0 ? 'نافد' : 'منخفض',
            ];
        });

        if (isset($filters['export'])) {
            $subtitle = isset($filters['category_id'])
                ? 'الفئة: '.Category::findOrFail($filters['category_id'])->category_name
                : 'جميع الفئات';

            return $this->exportReport(
                $filters['export'],
                'الأصناف تحت الحد الأدنى',
                $this->lowStockHeadings(),
                $rows->all(),
                'low-stock-items',
                $subtitle
            );
        }

        return view('inventory.report.low_stock', [
            'items' => $items,
            'categories' => Category::orderBy('category_name')->get(['id', 'category_name']),
        ]);
    }

    public function historicalStock(Request $request)
    {
        $filters = $request->validate([
            'as_of' => ['nullable', 'date'],
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            'export' => ['nullable', 'in:excel,pdf'],
        ]);

        $asOf = $filters['as_of'] ?? today()->toDateString();
        $incoming = $this->inventoryMovementTotals('invoice_products', 'invoices', 'invoice_id', 'voucher_date', $asOf);
        $outgoing = $this->inventoryMovementTotals('invoice_export_products', 'invoice_exports', 'invoice_export_id', 'voucher_date', $asOf);

        $itemsQuery = Item::query()
            ->leftJoinSub($incoming, 'incoming_totals', function ($join) {
                $join->on('incoming_totals.item_id', '=', 'items.id');
            })
            ->leftJoinSub($outgoing, 'outgoing_totals', function ($join) {
                $join->on('outgoing_totals.item_id', '=', 'items.id');
            })
            ->leftJoin('categories', 'categories.id', '=', 'items.category_id')
            ->leftJoin('units', 'units.id', '=', 'items.unit_id')
            ->select(
                'items.id',
                'items.item_num',
                'items.item_name',
                'items.open_balance',
                'categories.category_name',
                'units.unit_name',
                DB::raw('COALESCE(incoming_totals.quantity, 0) as total_incoming'),
                DB::raw('COALESCE(outgoing_totals.quantity, 0) as total_outgoing')
            )
            ->when(isset($filters['item_id']), function ($query) use ($filters) {
                $query->where('items.id', $filters['item_id']);
            })
            ->orderBy('items.item_num');

        $items = $itemsQuery->get()->map(function ($item) {
            $item->historical_balance = (float) $item->open_balance
                + (float) $item->total_incoming
                - (float) $item->total_outgoing;

            return $item;
        });

        $selectedItem = isset($filters['item_id'])
            ? Item::findOrFail($filters['item_id'])
            : null;
        $transactions = $selectedItem
            ? $this->inventoryTransactions($selectedItem->id, $asOf)
            : collect();

        if (isset($filters['export'])) {
            if ($selectedItem) {
                $summary = $items->first();
                $headings = ['التاريخ', 'نوع الحركة', 'رقم السند', 'رقم الفاتورة', 'المورد/القسم', 'الوارد', 'الصادر', 'الرصيد التراكمي'];
                $rows = [[
                    '',
                    'الرصيد الافتتاحي',
                    '',
                    '',
                    $selectedItem->item_name,
                    '',
                    '',
                    (float) $summary->open_balance,
                ]];

                foreach ($transactions as $transaction) {
                    $rows[] = [
                        $transaction->movement_date,
                        $transaction->movement_type,
                        $transaction->voucher_no,
                        $transaction->invoice_no,
                        $transaction->party,
                        $transaction->movement_type === 'وارد' ? (float) $transaction->quantity : '',
                        $transaction->movement_type === 'صادر' ? (float) $transaction->quantity : '',
                        (float) $transaction->running_balance,
                    ];
                }

                $rows[] = ['', 'الرصيد الختامي', '', '', '', (float) $summary->total_incoming, (float) $summary->total_outgoing, (float) $summary->historical_balance];
            } else {
                $headings = $this->historicalStockHeadings();
                $rows = $items->map(function ($item) {
                    return [
                        $item->item_num,
                        $item->item_name,
                        $item->category_name ?? '—',
                        $item->unit_name ?? '—',
                        (float) $item->open_balance,
                        (float) $item->total_incoming,
                        (float) $item->total_outgoing,
                        (float) $item->historical_balance,
                    ];
                })->all();
            }

            return $this->exportReport(
                $filters['export'],
                'الرصيد التاريخي للمخازن',
                $headings,
                $rows,
                'inventory-balance-'.$asOf,
                'كما في '.$asOf.($selectedItem ? ' — الصنف: '.$selectedItem->item_name : ' — جميع الأصناف')
            );
        }

        return view('inventory.report.historical_stock', [
            'items' => $items,
            'itemOptions' => Item::orderBy('item_num')->get(['id', 'item_num', 'item_name']),
            'asOf' => $asOf,
            'selectedItem' => $selectedItem,
            'transactions' => $transactions,
        ]);
    }

    public function historicalFuel(Request $request)
    {
        $filters = $request->validate([
            'as_of' => ['nullable', 'date'],
            'typesfuel_id' => ['nullable', 'integer', 'exists:types_fuels,id'],
            'export' => ['nullable', 'in:excel,pdf'],
        ]);

        $asOf = $filters['as_of'] ?? today()->toDateString();
        $incoming = Diesel::query()
            ->select('typesfuel_id', DB::raw('SUM(quantity) as quantity'))
            ->whereDate('date', '<=', $asOf)
            ->groupBy('typesfuel_id');
        $outgoing = DieselExport::query()
            ->select('typesfuel_id', DB::raw('SUM(quantity) as quantity'))
            ->whereDate('date', '<=', $asOf)
            ->groupBy('typesfuel_id');

        $typesQuery = TypesFuel::query()
            ->leftJoinSub($incoming, 'incoming_totals', function ($join) {
                $join->on('incoming_totals.typesfuel_id', '=', 'types_fuels.id');
            })
            ->leftJoinSub($outgoing, 'outgoing_totals', function ($join) {
                $join->on('outgoing_totals.typesfuel_id', '=', 'types_fuels.id');
            })
            ->select(
                'types_fuels.id',
                'types_fuels.name',
                DB::raw('COALESCE(incoming_totals.quantity, 0) as total_incoming'),
                DB::raw('COALESCE(outgoing_totals.quantity, 0) as total_outgoing')
            )
            ->when(isset($filters['typesfuel_id']), function ($query) use ($filters) {
                $query->where('types_fuels.id', $filters['typesfuel_id']);
            })
            ->orderBy('types_fuels.name');

        $types = $typesQuery->get()->map(function ($type) {
            $type->historical_balance = (float) $type->total_incoming - (float) $type->total_outgoing;

            return $type;
        });

        $transactions = isset($filters['typesfuel_id'])
            ? $this->fuelTransactions($filters['typesfuel_id'], $asOf)
            : collect();

        if (isset($filters['export'])) {
            if (isset($filters['typesfuel_id'])) {
                $summary = $types->first();
                $headings = ['التاريخ', 'الحركة', 'رقم السند', 'المورد/القسم المستلم', 'الكمية (لتر)', 'الرصيد التراكمي (لتر)'];
                $rows = [['', 'الرصيد الافتتاحي', '', $summary->name, '', 0]];

                foreach ($transactions as $transaction) {
                    $rows[] = [
                        $transaction->movement_date,
                        $transaction->movement_type,
                        $transaction->voucher,
                        $transaction->party,
                        (float) $transaction->quantity,
                        (float) $transaction->running_balance,
                    ];
                }

                $rows[] = ['', 'إجمالي الوارد', '', '', (float) $summary->total_incoming, ''];
                $rows[] = ['', 'إجمالي الصادر', '', '', (float) $summary->total_outgoing, ''];
                $rows[] = ['', 'الرصيد الختامي', '', '', '', (float) $summary->historical_balance];
            } else {
                $headings = $this->historicalFuelHeadings();
                $rows = $types->map(function ($type) {
                    return [
                        $type->name,
                        (float) $type->total_incoming,
                        (float) $type->total_outgoing,
                        (float) $type->historical_balance,
                    ];
                })->all();
            }

            return $this->exportReport(
                $filters['export'],
                'الرصيد التاريخي للمحروقات',
                $headings,
                $rows,
                'fuel-balance-'.$asOf,
                'كما في '.$asOf.(isset($filters['typesfuel_id'])
                    ? ' — النوع: '.TypesFuel::findOrFail($filters['typesfuel_id'])->name
                    : ' — جميع الأنواع')
            );
        }

        return view('inventory.report.historical_fuel', [
            'types' => $types,
            'allTypes' => TypesFuel::orderBy('name')->get(['id', 'name']),
            'asOf' => $asOf,
            'selectedType' => $filters['typesfuel_id'] ?? null,
            'transactions' => $transactions,
        ]);
    }

    public function fuelOutgoing(Request $request)
    {
        $filters = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'typesfuel_id' => ['nullable', 'integer', 'exists:types_fuels,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'sub_section_id' => ['nullable', 'integer', 'exists:sub_sections,id'],
            'export' => ['nullable', 'in:excel,pdf'],
        ]);

        $query = DieselExport::with(['typesfuel:id,name', 'section:id,name_section', 'subSection:id,name'])
            ->when(isset($filters['start_date']), function ($query) use ($filters) {
                $query->whereDate('date', '>=', $filters['start_date']);
            })
            ->when(isset($filters['end_date']), function ($query) use ($filters) {
                $query->whereDate('date', '<=', $filters['end_date']);
            })
            ->when(isset($filters['typesfuel_id']), function ($query) use ($filters) {
                $query->where('typesfuel_id', $filters['typesfuel_id']);
            })
            ->when(isset($filters['section_id']), function ($query) use ($filters) {
                $query->where('section_id', $filters['section_id']);
            })
            ->when(isset($filters['sub_section_id']), function ($query) use ($filters) {
                $query->where('sub_section_id', $filters['sub_section_id']);
            })
            ->orderBy('date')
            ->orderBy('id');

        $monthlyTotals = (clone $query)
            ->reorder()
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as period, SUM(quantity) as quantity")
            ->groupBy('period')
            ->orderBy('period')
            ->get();
        $annualTotals = (clone $query)
            ->reorder()
            ->selectRaw('YEAR(date) as period, SUM(quantity) as quantity')
            ->groupBy('period')
            ->orderBy('period')
            ->get();
        $totalOutgoing = (clone $query)->sum('quantity');
        $entryCount = (clone $query)->count();

        if (isset($filters['export'])) {
            $entries = (clone $query)->get();
            $rows = $entries->map(function (DieselExport $entry) {
                return [
                    optional($entry->typesfuel)->name ?? '—',
                    optional($entry->section)->name_section ?? '—',
                    optional($entry->subSection)->name ?? '—',
                    $entry->voucher,
                    $entry->date,
                    (float) $entry->quantity,
                    $entry->num_section,
                    $entry->num_note,
                ];
            });
            $exportRows = [];
            foreach ($annualTotals as $total) {
                $exportRows[] = ['الإجمالي السنوي '.$total->period, '', '', '', '', $total->quantity, '', ''];
            }
            foreach ($monthlyTotals as $total) {
                $exportRows[] = ['الإجمالي الشهري '.$total->period, '', '', '', '', $total->quantity, '', ''];
            }
            $exportRows[] = ['إجمالي الصرف في النتائج', '', '', '', '', $entries->sum('quantity'), '', ''];
            $exportRows = array_merge($exportRows, $rows->all());

            return $this->exportReport(
                $filters['export'],
                'تقرير الصرف الشامل للمحروقات',
                $this->fuelOutgoingHeadings(),
                $exportRows,
                'fuel-outgoing-report',
                $this->fuelOutgoingSubtitle($filters)
            );
        }

        $entries = $query->paginate(50)->withQueryString();

        return view('inventory.report.fuel_outgoing', [
            'entries' => $entries,
            'monthlyTotals' => $monthlyTotals,
            'annualTotals' => $annualTotals,
            'totalOutgoing' => $totalOutgoing,
            'entryCount' => $entryCount,
            'sections' => Section::orderBy('name_section')->get(['id', 'name_section']),
            'subSections' => SubSection::orderBy('name')->get(['id', 'name', 'section_id']),
            'types' => TypesFuel::orderBy('name')->get(['id', 'name']),
        ]);
    }

    private function inventoryMovementTotals(string $detailsTable, string $vouchersTable, string $foreignKey, string $dateColumn, string $asOf): Builder
    {
        return DB::table($detailsTable)
            ->join($vouchersTable, $vouchersTable.'.id', '=', $detailsTable.'.'.$foreignKey)
            ->select($detailsTable.'.item_id', DB::raw('SUM('.$detailsTable.'.quantity) as quantity'))
            ->where($vouchersTable.'.status', 'active')
            ->whereNull($vouchersTable.'.deleted_at')
            ->whereDate($vouchersTable.'.'.$dateColumn, '<=', $asOf)
            ->groupBy($detailsTable.'.item_id');
    }

    private function inventoryTransactions(int $itemId, string $asOf): Collection
    {
        $incoming = DB::table('invoices')
            ->join('invoice_products', 'invoices.id', '=', 'invoice_products.invoice_id')
            ->leftJoin('supplier_items', 'supplier_items.id', '=', 'invoices.supplier_item_id')
            ->where('invoice_products.item_id', $itemId)
            ->where('invoices.status', 'active')
            ->whereNull('invoices.deleted_at')
            ->whereDate('invoices.voucher_date', '<=', $asOf)
            ->select(
                'invoices.voucher_date as movement_date',
                'invoices.voucher_no',
                'invoices.invoice_no',
                'invoice_products.quantity',
                DB::raw("'وارد' as movement_type"),
                DB::raw("COALESCE(supplier_items.supplier_item_name, '—') as party"),
                DB::raw('0 as movement_order'),
                'invoices.id as voucher_id',
                'invoice_products.id as line_id'
            );

        $outgoing = DB::table('invoice_exports')
            ->join('invoice_export_products', 'invoice_exports.id', '=', 'invoice_export_products.invoice_export_id')
            ->leftJoin('sub_sections', 'sub_sections.id', '=', 'invoice_exports.subSection_id')
            ->where('invoice_export_products.item_id', $itemId)
            ->where('invoice_exports.status', 'active')
            ->whereNull('invoice_exports.deleted_at')
            ->whereDate('invoice_exports.voucher_date', '<=', $asOf)
            ->select(
                'invoice_exports.voucher_date as movement_date',
                'invoice_exports.voucher_no',
                'invoice_exports.invoice_no',
                'invoice_export_products.quantity',
                DB::raw("'صادر' as movement_type"),
                DB::raw("COALESCE(sub_sections.name, '—') as party"),
                DB::raw('1 as movement_order'),
                'invoice_exports.id as voucher_id',
                'invoice_export_products.id as line_id'
            );

        $transactions = DB::query()
            ->fromSub($incoming->unionAll($outgoing), 'movements')
            ->orderBy('movement_date')
            ->orderBy('movement_order')
            ->orderBy('voucher_id')
            ->orderBy('line_id')
            ->get();

        $balance = (float) Item::findOrFail($itemId)->open_balance;

        return $this->balanceCalculator->inventory($transactions, $balance);
    }

    private function fuelTransactions(int $typeId, string $asOf): Collection
    {
        $incoming = DB::table('diesels')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'diesels.supplier_id')
            ->where('diesels.typesfuel_id', $typeId)
            ->whereDate('diesels.date', '<=', $asOf)
            ->select(
                'diesels.date as movement_date',
                'diesels.voucher',
                'diesels.quantity',
                DB::raw("'وارد' as movement_type"),
                DB::raw("COALESCE(suppliers.name_supplier, '—') as party"),
                DB::raw('0 as movement_order'),
                'diesels.id as movement_id'
            );

        $outgoing = DB::table('diesel_exports')
            ->leftJoin('sections', 'sections.id', '=', 'diesel_exports.section_id')
            ->leftJoin('sub_sections', 'sub_sections.id', '=', 'diesel_exports.sub_section_id')
            ->where('diesel_exports.typesfuel_id', $typeId)
            ->whereDate('diesel_exports.date', '<=', $asOf)
            ->select(
                'diesel_exports.date as movement_date',
                'diesel_exports.voucher',
                'diesel_exports.quantity',
                DB::raw("'صادر' as movement_type"),
                DB::raw("COALESCE(sub_sections.name, sections.name_section, '—') as party"),
                DB::raw('1 as movement_order'),
                'diesel_exports.id as movement_id'
            );

        $transactions = DB::query()
            ->fromSub($incoming->unionAll($outgoing), 'movements')
            ->orderBy('movement_date')
            ->orderBy('movement_order')
            ->orderBy('movement_id')
            ->get();

        return $this->balanceCalculator->fuel($transactions);
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

    private function fuelOutgoingSubtitle(array $filters): string
    {
        $labels = [$this->dateRangeLabel($filters)];

        if (isset($filters['typesfuel_id'])) {
            $labels[] = 'النوع: '.TypesFuel::findOrFail($filters['typesfuel_id'])->name;
        }

        if (isset($filters['section_id'])) {
            $labels[] = 'الدائرة: '.Section::findOrFail($filters['section_id'])->name_section;
        }

        if (isset($filters['sub_section_id'])) {
            $labels[] = 'القسم الفرعي: '.SubSection::findOrFail($filters['sub_section_id'])->name;
        }

        return implode(' — ', $labels);
    }

    private function lowStockHeadings(): array
    {
        return ['رقم الصنف', 'اسم الصنف', 'الفئة', 'الوحدة', 'الرصيد الحالي', 'الحد الأدنى', 'الحالة'];
    }

    private function historicalStockHeadings(): array
    {
        return ['رقم الصنف', 'اسم الصنف', 'الفئة', 'الوحدة', 'الرصيد الافتتاحي', 'الوارد حتى التاريخ', 'الصادر حتى التاريخ', 'الرصيد التاريخي'];
    }

    private function historicalFuelHeadings(): array
    {
        return ['نوع المحروقات', 'الوارد حتى التاريخ (لتر)', 'الصادر حتى التاريخ (لتر)', 'الرصيد التاريخي (لتر)'];
    }

    private function fuelOutgoingHeadings(): array
    {
        return ['نوع المحروقات', 'الدائرة', 'القسم الفرعي', 'رقم السند', 'التاريخ', 'الكمية (لتر)', 'الجهة المستلمة', 'رقم الدفتر'];
    }
}
