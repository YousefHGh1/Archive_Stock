<?php
namespace App\Http\Controllers;

use App\Exports\PurchaseOrdersExport;
use App\Exports\PurchasesExport;
use App\Models\Purchase;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequestStatusLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:purchases-read', ['only' => ['index', 'dashboard', 'exportPurchasesExcel', 'exportPurchasesPDF', 'exportPurchaseOrdersExcel', 'exportPurchaseOrdersPDF', 'printPurchase', 'printPurchaseOrder']]);
    }

    /**
     * Display the purchase reports index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $totalPurchases     = Purchase::count();
        $pendingPurchases   = Purchase::where('status', 'pending')->count();
        $approvedPurchases  = Purchase::whereIn('status', ['purchase_order', 'tender_committee', 'rent_repair', 'approved_stock'])->count();
        $rejectedPurchases  = Purchase::where('status', 'rejected')->count();
        $withdrawnPurchases = Purchase::where('withdrawn', true)->count();

        $statusBreakdown = Purchase::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return view('purchase-report.index', compact(
            'totalPurchases',
            'pendingPurchases',
            'approvedPurchases',
            'rejectedPurchases',
            'withdrawnPurchases',
            'statusBreakdown'
        ));
    }

    /**
     * Display the dashboard with charts and statistics.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Monthly statistics for the last 12 months
        $monthlyStats = Purchase::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('count(*) as total'),
            DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected'),
            DB::raw('SUM(CASE WHEN status IN ("purchase_order", "tender_committee", "rent_repair", "approved_stock") THEN 1 ELSE 0 END) as approved')
        )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Status distribution
        $statusDistribution = Purchase::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Top requesters
        $topRequesters = Purchase::select('requester_name', DB::raw('count(*) as count'))
            ->whereNotNull('requester_name')
            ->groupBy('requester_name')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Average processing time (from creation to final status)
        $avgProcessingTime = PurchaseRequestStatusLog::select(
            DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours')
        )
            ->where('to_status', 'in', ['rejected', 'approved_stock'])
            ->first();

        // Recent activity
        $recentActivity = PurchaseRequestStatusLog::with('purchase')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        // SLA compliance
        $slaThresholds = config('purchase.sla_thresholds');
        $slaCompliance = [];
        foreach ($slaThresholds as $status => $thresholdDays) {
            $cutoffDate = now()->subDays($thresholdDays);
            $total      = Purchase::where('status', $status)->count();
            $overdue    = Purchase::where('status', $status)
                ->where('updated_at', '<', $cutoffDate)
                ->count();
            $slaCompliance[$status] = [
                'total'           => $total,
                'overdue'         => $overdue,
                'compliance_rate' => $total > 0 ? round((($total - $overdue) / $total) * 100, 2) : 100,
            ];
        }

        return view('purchase-report.dashboard', compact(
            'monthlyStats',
            'statusDistribution',
            'topRequesters',
            'avgProcessingTime',
            'recentActivity',
            'slaCompliance'
        ));
    }

    /**
     * Get purchases by status (AJAX).
     *
     * @param  string  $status
     * @return \Illuminate\Http\JsonResponse
     */
    public function byStatus($status)
    {
        $purchases = Purchase::with('user', 'section')
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($purchases);
    }

    /**
     * Get monthly report data (AJAX).
     *
     * @param  int  $year
     * @param  int  $month
     * @return \Illuminate\Http\JsonResponse
     */
    public function monthlyReport($year, $month)
    {
        $purchases = Purchase::with('user', 'section')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total'     => $purchases->count(),
            'pending'   => $purchases->where('status', 'pending')->count(),
            'approved'  => $purchases->whereIn('status', ['purchase_order', 'tender_committee', 'rent_repair', 'approved_stock'])->count(),
            'rejected'  => $purchases->where('status', 'rejected')->count(),
            'withdrawn' => $purchases->where('withdrawn', true)->count(),
        ];

        return response()->json([
            'purchases' => $purchases,
            'stats'     => $stats,
        ]);
    }

    /**
     * Export purchases to Excel.
     */
    public function exportPurchasesExcel()
    {
        $purchases = Purchase::with('section')->get();
        return Excel::download(new PurchasesExport($purchases), 'purchases-list.xlsx');
    }

    /**
     * Export purchases to PDF.
     */
    public function exportPurchasesPDF()
    {
        $purchases = Purchase::with('section')->orderBy('created_at', 'desc')->get();
        $pdf       = Pdf::loadView('purchase-report.pdf.purchases-list', compact('purchases'));
        return $pdf->download('purchases-list.pdf');
    }

    /**
     * Print single purchase.
     */
    public function printPurchase($id)
    {
        $purchase = Purchase::with('section')->findOrFail($id);
        $pdf      = Pdf::loadView('purchase-report.pdf.purchase', compact('purchase'));
        return $pdf->download('purchase-' . $purchase->serial_number . '.pdf');
    }

    /**
     * Export purchase orders to Excel.
     */
    public function exportPurchaseOrdersExcel()
    {
        $purchaseOrders = PurchaseOrder::with('purchase')->get();
        return Excel::download(new PurchaseOrdersExport($purchaseOrders), 'purchase-orders-list.xlsx');
    }

    /**
     * Export purchase orders to PDF.
     */
    public function exportPurchaseOrdersPDF()
    {
        $purchaseOrders = PurchaseOrder::with('purchase')->orderBy('created_at', 'desc')->get();
        $pdf            = Pdf::loadView('purchase-report.pdf.purchase-orders-list', compact('purchaseOrders'));
        return $pdf->download('purchase-orders-list.pdf');
    }

    /**
     * Print single purchase order.
     */
    public function printPurchaseOrder($id)
    {
        $purchaseOrder = PurchaseOrder::with('purchase', 'items')->findOrFail($id);
        $pdf           = Pdf::loadView('purchase-report.pdf.purchase-order', compact('purchaseOrder'));
        return $pdf->download('purchase-order-' . $purchaseOrder->serial_number . '.pdf');
    }
}
