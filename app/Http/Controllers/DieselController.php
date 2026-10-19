<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\CalculatesFuelBalances;
use App\Http\Controllers\Concerns\ExportsCsv;
use App\Models\Diesel;
use App\Models\DieselExport;
use App\Models\Supplier;
use App\Models\TypesFuel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DieselController extends Controller
{
    use CalculatesFuelBalances, ExportsCsv;

    public function __construct()
    {
        $this->middleware('permission:diesels-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:diesels-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:diesels-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:diesels-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $currentYear = date('Y');

        $diesel = Diesel::with('supplier', 'typesfuel')->whereYear('date', $currentYear)->paginate(10);
        return view('diesel.index', compact('diesel'));
    }

    public function create()
    {
        $supplier  = Supplier::get(['id', 'name_supplier']);
        $typesfuel = TypesFuel::get(['id', 'name']);
        return view('diesel.create', compact('supplier', 'typesfuel'));
    }

    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'supplier_id'  => 'required',
            'typesfuel_id' => 'required',
            'voucher'      => 'required|string|unique:diesels',
            'type'         => 'required',
            'invoice_num'  => 'required|numeric',
            'quantity'     => 'required|numeric',
            'price'        => 'nullable|numeric|min:0',
            'date'         => 'required|date',
            'notes'        => 'nullable|string|max:5000',
        ]);

        Diesel::create($validatedData);

        $request->user()->recordActivity(
            'قام بإضافة قيد وارد سولار - سند رقم ' . $validatedData['voucher'] . ' (الكمية: ' . $validatedData['quantity'] . ')',
            [
                'module' => 'diesel',
                'voucher' => $validatedData['voucher'],
                'quantity' => $validatedData['quantity'],
                'date' => $validatedData['date'],
            ]
        );

        return redirect()->route('diesel.index')
            ->with('success', 'تمت اضافة بيانات وارد المحروقات بنجاح.');

    }

    public function show($id)
    {
        //
        $diesel   = Diesel::with(['typesfuel', 'supplier'])->find($id);
        $supplier = Supplier::get(['id', 'name_supplier']);

        return view('diesel.show', compact('diesel', 'supplier'))->with('diesel', $diesel);
    }

    public function edit($id)
    {
        //
        $diesel    = Diesel::find($id);
        $supplier  = Supplier::get(['id', 'name_supplier']);
        $typesfuel = TypesFuel::get(['id', 'name']);
        return view('diesel.edit', compact('diesel', 'supplier', 'typesfuel'));
    }

    public function update(Request $request, $id)
    {
        //
        $diesel = Diesel::find($id);

        $validatedData = $request->validate([
            'supplier_id'  => 'required',
            'typesfuel_id' => 'required',
            'voucher'      => 'required|string',
            'type'         => 'required',
            'invoice_num'  => 'required|numeric',
            'quantity'     => 'required|numeric',
            'price'        => 'nullable|numeric|min:0',
            'date'         => 'required|date',
            'notes'        => 'nullable|string|max:5000',
        ]);

        $diesel->update($validatedData);

        return redirect()->route('diesel.index')
            ->with('success', 'تمت تعديل بيانات وارد المحروقات بنجاح.');

    }

    public function destroy($id)
    {
        Diesel::destroy($id);
        return redirect()->route('diesel.index')
            ->with('success', 'تمت حذف بيانات وارد المحروقات بنجاح.');

    }

    public function report(Request $request)
    {
        $currentYear  = date('Y');
        $previousYear = $currentYear - 1;

        $allPreviousYears = range(1900, $previousYear);

        // تطبيق نفس الفلاتر على جميع الاستعلامات (الجدول، المجاميع، الأشهر)
        $applyFilters = function ($query) use ($request, $currentYear) {
            $query->whereYear('date', $currentYear);

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            }

            if ($request->filled('start_voucher') && $request->filled('end_voucher')) {
                $query->whereBetween('voucher', [$request->start_voucher, $request->end_voucher]);
            }

            if ($request->filled('typesfuel_id')) {
                $query->where('typesfuel_id', $request->typesfuel_id);
            }

            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            if ($request->filled('invoice_num')) {
                $query->where('invoice_num', 'like', '%' . $request->invoice_num . '%');
            }

            return $query;
        };

        $query = $applyFilters(Diesel::with(['typesfuel', 'supplier']))
            ->orderByDesc('date')
            ->orderByDesc('id');

        // تصدير جميع النتائج المفلترة إلى ملف Excel (CSV)
        if ($request->input('export') === 'excel') {
            return $this->exportReportCsv('diesel-report-' . date('Y-m-d') . '.csv', [
                'نوع المحروقات', 'اسم المورد', 'كمية التوريد', 'جهة التوريد', 'سند التوريد', 'رقم الفاتورة', 'تاريخ التوريد', 'ملاحظات',
            ], $query->get()->map(function ($entry) {
                return [
                    $entry->typesfuel->name ?? '-',
                    $entry->supplier->name_supplier ?? '-',
                    $entry->quantity,
                    $entry->type,
                    $entry->voucher,
                    $entry->invoice_num,
                    $entry->date,
                    $entry->notes,
                ];
            }));
        }

        $perPage = in_array((int) $request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;

        $dieselEntries = $query->paginate($perPage)->withQueryString();

        // Fetch previous years' data dynamically
        $previousYearsData = Diesel::selectRaw('YEAR(date) as year, MONTH(date) as month, SUM(quantity) as total')
            ->whereYear('date', '<', $currentYear)
            ->groupBy('year', 'month')
            ->get()
            ->groupBy('year');

        // بيانات الأشهر للسنة الحالية بنفس فلاتر الاستعلام الرئيسي
        $dieselData = $applyFilters(Diesel::selectRaw('MONTH(date) as month, SUM(quantity) as total'))
            ->groupBy('month')
            ->pluck('total', 'month');

        $suppliers = Supplier::all();

        // Calculate previous balance (before start_date if provided)
        $dieselPreviousQuery = Diesel::query();
        $dieselExportPreviousQuery = DieselExport::query();

        if ($request->filled('start_date')) {
            $dieselPreviousQuery->where('date', '<', $request->start_date);
            $dieselExportPreviousQuery->where('date', '<', $request->start_date);
        } else {
            // If no start_date, calculate from all previous years
            $dieselPreviousQuery->whereIn(DB::raw('YEAR(date)'), $allPreviousYears);
            $dieselExportPreviousQuery->whereIn(DB::raw('YEAR(date)'), $allPreviousYears);
        }

        $previousBalance = $dieselPreviousQuery->sum('quantity') -
        $dieselExportPreviousQuery->sum('quantity');

        // المجاميع تحسب على كامل النتائج المفلترة وليس على الصفحة الحالية فقط
        $totalReceived = $applyFilters(Diesel::query())->sum('quantity');
        $totalEntries  = $dieselEntries->total();

        // حساب المتبقي لكل نوع من أنواع المحروقات بشكل ديناميكي
        // يتم إضافة عمود لكل نوع موجود في جدول أنواع المحروقات
        $fuelBalances = $this->fuelRemainingByType($request);

        $typesfuel       = $fuelBalances['typesfuel'];
        $remainingByType = $fuelBalances['remainingByType'];
        $totalRemaining  = $fuelBalances['totalRemaining'];

        return view('diesel.report', compact(
            'dieselData', 'previousYearsData', 'suppliers', 'previousBalance',
            'totalReceived', 'totalEntries', 'dieselEntries',
            'typesfuel', 'remainingByType', 'totalRemaining', 'perPage'
        ));
    }

    // public function searchdate(Request $request)
    // {
    //     $supplier = Supplier::all();

    //     // Validate the input dates to ensure they are not empty and are valid date format
    //     $validated = $request->validate([
    //         'start_date' => 'required|date',
    //         'end_date'   => 'required|date|after_or_equal:start_date',
    //     ]);

    //     // Fetch the dates
    //     $startDate = $request->input('start_date');
    //     $endDate   = $request->input('end_date');

    //     // Perform the search
    //     $dieselEntries = Diesel::with(['typesfuel', 'supplier'])->whereBetween('date', [$startDate, $endDate])->get();

    //     // Get additional required data for the report
    //     $currentYear  = date('Y');
    //     $previousYear = $currentYear - 1;

    //     $dieselData = Diesel::selectRaw('MONTH(date) as month, SUM(quantity) as total')
    //         ->whereYear('date', $currentYear)
    //         ->groupBy('month')
    //         ->pluck('total', 'month');

    //     $totalReceived = Diesel::whereYear('date', $currentYear)->sum('quantity');
    //     $totalEntries  = Diesel::whereYear('date', $currentYear)->count();

    //     $previousBalance = Diesel::whereYear('date', $previousYear)->sum('quantity') -
    //     DieselExport::whereYear('date', $previousYear)->sum('quantity');

    //     $remainingDiesel = Diesel::where('typesfuel_id', 3)->sum('quantity') -
    //     DieselExport::where('typesfuel_id', 3)->sum('quantity');

    //     $remainingGasoline = Diesel::where('typesfuel_id', 2)->sum('quantity') -
    //     DieselExport::where('typesfuel_id', 2)->sum('quantity');

    //     // Check if any entries were found
    //     if ($dieselEntries->isEmpty()) {
    //         return back()->with('error', 'لم يتم العثور على نتائج للفترة المحددة.');
    //     }

    //     // Pass all required data to the view
    //     return view('diesel.report', compact(
    //         'dieselEntries', 'dieselData', 'totalReceived', 'totalEntries', 'previousBalance',
    //         'remainingDiesel', 'remainingGasoline', 'supplier'
    //     ));
    // }

    // public function searchvou(Request $request)
    // {
    //     $supplier = Supplier::all();

    //     // Validate the input voucher range
    //     $validated = $request->validate([
    //         'start_voucher' => 'required|string',
    //         'end_voucher'   => 'required|string',
    //     ]);

    //     // Fetch the voucher range inputs
    //     $startVoucher = $request->input('start_voucher');
    //     $endVoucher   = $request->input('end_voucher');

    //     // Perform the search for diesel entries based on the voucher range
    //     $dieselEntries = Diesel::with(['typesfuel', 'supplier'])->whereBetween('voucher', [$startVoucher, $endVoucher])->get();

    //     // If no diesel entries are found, return an error message
    //     if ($dieselEntries->isEmpty()) {
    //         return back()->with('error', 'لم يتم العثور على نتائج للسندات المحددة.');
    //     }

    //     // Additional data for the report (optional, adjust as needed)
    //     $currentYear  = date('Y');
    //     $previousYear = $currentYear - 1;

    //     $dieselData = Diesel::selectRaw('MONTH(date) as month, SUM(quantity) as total')
    //         ->whereYear('date', $currentYear)
    //         ->groupBy('month')
    //         ->pluck('total', 'month');

    //     $totalReceived = Diesel::whereYear('date', $currentYear)->sum('quantity');
    //     $totalEntries  = Diesel::whereYear('date', $currentYear)->count();

    //     $previousBalance = Diesel::whereYear('date', $previousYear)->sum('quantity') -
    //     DieselExport::whereYear('date', $previousYear)->sum('quantity');

    //     $remainingDiesel = Diesel::where('typesfuel_id', 3)->sum('quantity') -
    //     DieselExport::where('typesfuel_id', 3)->sum('quantity');

    //     $remainingGasoline = Diesel::where('typesfuel_id', 2)->sum('quantity') -
    //     DieselExport::where('typesfuel_id', 2)->sum('quantity');

    //     // Check if any entries were found
    //     if ($dieselEntries->isEmpty()) {
    //         return back()->with('error', 'لم يتم العثور على نتائج للسندين المحددين.');
    //     }

    //     // Return the view with all relevant data
    //     return view('diesel.report', compact(
    //         'dieselEntries', 'supplier', 'dieselData', 'totalReceived', 'totalEntries',
    //         'previousBalance', 'remainingDiesel', 'remainingGasoline'
    //     ));
    // }

    public function transactions(Request $request, $id)
    {
        // Get the specific fuel type
        $fuelType = TypesFuel::find($id);

        if (!$fuelType) {
            return redirect()->back()->with('error', 'نوع المحروقات غير موجود');
        }

        // Get all diesel entries (imports) for this fuel type
        $importsQuery = Diesel::with(['supplier', 'typesfuel'])
            ->where('typesfuel_id', $id);

        // Get all diesel exports for this fuel type
        $exportsQuery = DieselExport::with(['section', 'subSection', 'typesfuel'])
            ->where('typesfuel_id', $id);

        // Apply filters if the request is POST
        if ($request->isMethod('post')) {
            // Date range filter
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $importsQuery->whereBetween('date', [$request->start_date, $request->end_date]);
                $exportsQuery->whereBetween('date', [$request->start_date, $request->end_date]);
            }

            // Voucher range filter
            if ($request->filled('start_voucher') && $request->filled('end_voucher')) {
                $importsQuery->whereBetween('voucher', [$request->start_voucher, $request->end_voucher]);
                $exportsQuery->whereBetween('voucher', [$request->start_voucher, $request->end_voucher]);
            }

            // Invoice number range filter
            if ($request->filled('start_invoice') && $request->filled('end_invoice')) {
                $importsQuery->whereBetween('invoice_num', [$request->start_invoice, $request->end_invoice]);
                $exportsQuery->whereBetween('invoice_num', [$request->start_invoice, $request->end_invoice]);
            }

            // Type filter (import/export)
            if ($request->filled('transaction_type')) {
                if ($request->transaction_type == 'import') {
                    $exportsQuery->whereRaw('1 = 0'); // Exclude exports
                } elseif ($request->transaction_type == 'export') {
                    $importsQuery->whereRaw('1 = 0'); // Exclude imports
                }
            }
        }

        $imports = $importsQuery->orderBy('date', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'import',
                    'date' => $item->date,
                    'voucher' => $item->voucher,
                    'invoice_num' => $item->invoice_num,
                    'quantity' => $item->quantity,
                    'supplier' => $item->supplier->name_supplier ?? 'N/A',
                    'fuel_type' => $item->typesfuel->name ?? 'N/A',
                    'delivery_type' => $item->type,
                    'section' => '-',
                    'sub_section' => '-',
                    'num_section' => '-',
                ];
            });

        $exports = $exportsQuery->orderBy('date', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'type' => 'export',
                    'date' => $item->date,
                    'voucher' => $item->voucher,
                    'invoice_num' => $item->invoice_num ?? '-',
                    'quantity' => $item->quantity,
                    'supplier' => '-',
                    'fuel_type' => $item->typesfuel->name ?? 'N/A',
                    'delivery_type' => '-',
                    'section' => $item->section->name_section ?? 'N/A',
                    'sub_section' => $item->subSection->name_sub_section ?? 'N/A',
                    'num_section' => $item->num_section ?? '-',
                ];
            });

        // Merge and sort by date
        $allTransactions = $imports->concat($exports)->sortBy('date')->values();

        // Calculate a running balance after each transaction
        $runningBalance = 0;
        $allTransactions = $allTransactions->map(function ($transaction) use (&$runningBalance) {
            if ($transaction['type'] == 'import') {
                $runningBalance += $transaction['quantity'];
            } else {
                $runningBalance -= $transaction['quantity'];
            }

            $transaction['balance'] = $runningBalance;

            return $transaction;
        })->values();

        // Calculate totals
        $totalImports = $imports->sum('quantity');
        $totalExports = $exports->sum('quantity');
        $balance = $totalImports - $totalExports;

        return view('diesel.transactions', compact(
            'allTransactions',
            'fuelType',
            'totalImports',
            'totalExports',
            'balance'
        ));
    }

}