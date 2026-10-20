<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\CalculatesFuelBalances;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HeadController extends Controller
{
    use CalculatesFuelBalances;

    public function nots()
    {
        $stats = $this->dashboardStats();

        return view('dashboard', [
            'dashboardStats'  => $stats,
            'dashboardCharts' => $this->dashboardCharts($stats),
            'fuelSummary'     => $this->fuelSummary(),
        ]);
    }

    // الصفحة الرئيسية "/" تعرض نفس لوحة التحكم
    public function nots1()
    {
        return $this->nots();
    }

    // public function archive()
    // {
    //     //
    //     $archiveNot = archiveNot::all();
    //     return view('archive.table', compact('archiveNot'));
    // }
    // public function legal()
    // {
    //     //
    //     $legalNot = legalNot::all();
    //     return view('legal.table', compact('legalNot'));
    // }
    // public function computer()
    // {
    //     //
    //     $computerNot = computerNot::all();
    //     return view('computer.table', compact('computerNot'));
    // }
    // public function jibaya()
    // {
    //     //
    //     $jibayaNot = jibayaNot::all();
    //     return view('jibaya.table', compact('jibayaNot'));
    // }
    // public function censorship()
    // {
    //     //
    //     $censorshipNot = censorshipNot::all();
    //     return view('censorship.table', compact('censorshipNot'));
    // }

    private function dashboardStats()
    {
        $counts = [
            'archiveIncoming'    => DB::table('archives')->count(),
            'archiveOutgoing'    => DB::table('archive_exports')->count(),
            'computerIncoming'   => DB::table('computers')->count(),
            'computerOutgoing'   => DB::table('computer_exports')->count(),
            'censorshipIncoming' => DB::table('censorships')->count(),
            'censorshipOutgoing' => DB::table('censorship_exports')->count(),
            'legalIncoming'      => DB::table('legals')->count(),
            'legalOutgoing'      => DB::table('legalexports')->count(),
            'jibayaIncoming'     => DB::table('jibayas')->count(),
            'jibayaOutgoing'     => DB::table('jibayaexports')->count(),
            'inventoryItems'     => DB::table('items')->count(),
            'inventoryIncoming'  => DB::table('invoices')->count(),
            'inventoryOutgoing'  => DB::table('invoice_exports')->count(),
            'custodies'          => DB::table('custodies')->count(),
            'fuelIncoming'       => DB::table('diesels')->count(),
            'fuelOutgoing'       => DB::table('diesel_exports')->count(),
            'archiveMessages'    => DB::table('archive_nots')->count(),
            'legalMessages'      => DB::table('legal_nots')->count(),
            'computerMessages'   => DB::table('computer_nots')->count(),
            'censorshipMessages' => DB::table('censorship_nots')->count(),
            'jibayaMessages'     => DB::table('jibaya_nots')->count(),
        ];

        $counts['internalMessages'] = $counts['archiveMessages'] + $counts['legalMessages']
             + $counts['computerMessages'] + $counts['censorshipMessages'] + $counts['jibayaMessages'];

        $counts['totalArchiveTransactions'] = $counts['archiveIncoming'] + $counts['archiveOutgoing']
             + $counts['computerIncoming'] + $counts['computerOutgoing']
             + $counts['censorshipIncoming'] + $counts['censorshipOutgoing']
             + $counts['legalIncoming'] + $counts['legalOutgoing']
             + $counts['jibayaIncoming'] + $counts['jibayaOutgoing'];

        $counts['totalInventoryTransactions'] = $counts['inventoryIncoming'] + $counts['inventoryOutgoing'];

        return $counts;
    }

    private function dashboardCharts(array $stats)
    {
        return [
            'monthLabels'         => ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو', 'يوليو', 'أغسطس', 'سبتمبر', 'أكتوبر', 'نوفمبر', 'ديسمبر'],
            'archiveDistribution' => [
                'labels'   => ['الأرشيف', 'الحاسوب', 'الرقابة', 'الشؤون القانونية', 'الجباية'],
                'incoming' => [
                    $stats['archiveIncoming'],
                    $stats['computerIncoming'],
                    $stats['censorshipIncoming'],
                    $stats['legalIncoming'],
                    $stats['jibayaIncoming'],
                ],
                'outgoing' => [
                    $stats['archiveOutgoing'],
                    $stats['computerOutgoing'],
                    $stats['censorshipOutgoing'],
                    $stats['legalOutgoing'],
                    $stats['jibayaOutgoing'],
                ],
            ],
            'monthlyArchive'      => [
                'incoming' => $this->monthlyCounts([
                    ['archives', 'date'],
                    ['computers', 'date'],
                    ['censorships', 'date'],
                    ['legals', 'date'],
                    ['jibayas', 'date'],
                ]),
                'outgoing' => $this->monthlyCounts([
                    ['archive_exports', 'date'],
                    ['computer_exports', 'date'],
                    ['censorship_exports', 'date'],
                    ['legalexports', 'date'],
                    ['jibayaexports', 'date'],
                ]),
            ],
            'inventoryFlow'       => [
                'incoming' => $this->monthlyCounts([['invoices', 'voucher_date']]),
                'outgoing' => $this->monthlyCounts([['invoice_exports', 'voucher_date']]),
            ],
            'fuelFlow'            => [
                'incoming' => $this->monthlyCounts([['diesels', 'date']], 'quantity'),
                'outgoing' => $this->monthlyCounts([['diesel_exports', 'date']], 'quantity'),
            ],
            'messages'            => [
                'labels' => ['الأرشيف', 'القانونية', 'الحاسوب', 'الرقابة', 'الجباية'],
                'series' => [
                    $stats['archiveMessages'],
                    $stats['legalMessages'],
                    $stats['computerMessages'],
                    $stats['censorshipMessages'],
                    $stats['jibayaMessages'],
                ],
            ],
            'overallMonthly'      => [
                'incoming' => $this->monthlyCounts([
                    ['archives', 'date'],
                    ['computers', 'date'],
                    ['censorships', 'date'],
                    ['legals', 'date'],
                    ['jibayas', 'date'],
                    ['invoices', 'voucher_date'],
                    ['diesels', 'date'],
                ]),
                'outgoing' => $this->monthlyCounts([
                    ['archive_exports', 'date'],
                    ['computer_exports', 'date'],
                    ['censorship_exports', 'date'],
                    ['legalexports', 'date'],
                    ['jibayaexports', 'date'],
                    ['invoice_exports', 'voucher_date'],
                    ['diesel_exports', 'date'],
                ]),
            ],
            'departmentTotals'    => [
                'labels' => ['الأرشيف', 'الحاسوب', 'الرقابة', 'القانونية', 'الجباية', 'المخازن', 'المحروقات'],
                'values' => [
                    $stats['archiveIncoming'] + $stats['archiveOutgoing'],
                    $stats['computerIncoming'] + $stats['computerOutgoing'],
                    $stats['censorshipIncoming'] + $stats['censorshipOutgoing'],
                    $stats['legalIncoming'] + $stats['legalOutgoing'],
                    $stats['jibayaIncoming'] + $stats['jibayaOutgoing'],
                    $stats['inventoryIncoming'] + $stats['inventoryOutgoing'],
                    $stats['fuelIncoming'] + $stats['fuelOutgoing'],
                ],
            ],
        ];
    }

    /**
     * ملخص المحروقات: الإجماليات، المتبقي لكل نوع، وآخر الحركات
     */
    private function fuelSummary()
    {
        $balances = $this->fuelRemainingByType(new Request());

        $latestIn = DB::table('diesels')
            ->leftJoin('types_fuels', 'types_fuels.id', '=', 'diesels.typesfuel_id')
            ->leftJoin('suppliers', 'suppliers.id', '=', 'diesels.supplier_id')
            ->select('diesels.id', 'diesels.quantity', 'diesels.date', 'types_fuels.name as fuel', 'suppliers.name_supplier as party')
            ->selectRaw("'in' as direction")
            ->orderByDesc('diesels.date')->orderByDesc('diesels.id')
            ->limit(6)->get();

        $latestOut = DB::table('diesel_exports')
            ->leftJoin('types_fuels', 'types_fuels.id', '=', 'diesel_exports.typesfuel_id')
            ->leftJoin('sections', 'sections.id', '=', 'diesel_exports.section_id')
            ->select('diesel_exports.id', 'diesel_exports.quantity', 'diesel_exports.date', 'types_fuels.name as fuel', 'sections.name_section as party')
            ->selectRaw("'out' as direction")
            ->orderByDesc('diesel_exports.date')->orderByDesc('diesel_exports.id')
            ->limit(6)->get();

        return [
            'totalIn'         => (float) DB::table('diesels')->sum('quantity'),
            'totalOut'        => (float) DB::table('diesel_exports')->sum('quantity'),
            'yearIn'          => (float) DB::table('diesels')->whereYear('date', now()->year)->sum('quantity'),
            'yearOut'         => (float) DB::table('diesel_exports')->whereYear('date', now()->year)->sum('quantity'),
            'typesfuel'       => $balances['typesfuel'],
            'remainingByType' => $balances['remainingByType'],
            'totalRemaining'  => $balances['totalRemaining'],
            'latest'          => $latestIn->concat($latestOut)
                ->sortByDesc(fn($row) => $row->date . sprintf('%010d', $row->id))
                ->take(6)
                ->values(),
        ];
    }

    /**
     * عدد السجلات (أو مجموع عمود) لكل شهر في السنة الحالية،
     * وإن لم توجد بيانات فيها نعرض آخر سنة تحتوي على بيانات
     */
    private function monthlyCounts(array $tables, ?string $sumColumn = null)
    {
        $months      = array_fill(1, 12, 0);
        $currentYear = now()->year;

        $this->fillMonthlyCounts($months, $tables, $currentYear, $sumColumn);

        if (array_sum($months) == 0) {
            $latestYear = $this->latestYearFor($tables);

            if ($latestYear && $latestYear !== $currentYear) {
                $months = array_fill(1, 12, 0);
                $this->fillMonthlyCounts($months, $tables, $latestYear, $sumColumn);
            }
        }

        return array_values($months);
    }

    private function fillMonthlyCounts(array &$months, array $tables, int $year, ?string $sumColumn = null)
    {
        $aggregate = $sumColumn ? "SUM($sumColumn)" : 'COUNT(*)';

        foreach ($tables as [$table, $dateColumn]) {
            $rows = DB::table($table)
                ->selectRaw("MONTH($dateColumn) as month, $aggregate as total")
                ->whereYear($dateColumn, $year)
                ->groupBy('month')
                ->pluck('total', 'month');

            foreach ($rows as $month => $total) {
                $months[(int) $month] += (float) $total;
            }
        }
    }

    private function latestYearFor(array $tables)
    {
        $latestYear = null;

        foreach ($tables as [$table, $dateColumn]) {
            $tableLatestYear = DB::table($table)->selectRaw("MAX(YEAR($dateColumn)) as year")->value('year');

            if ($tableLatestYear) {
                $latestYear = max((int) $tableLatestYear, (int) $latestYear);
            }
        }

        return $latestYear;
    }
}
