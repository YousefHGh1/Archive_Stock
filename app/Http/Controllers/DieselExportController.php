<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\CalculatesFuelBalances;
use App\Http\Controllers\Concerns\ExportsCsv;
use App\Models\Diesel;
use App\Models\DieselExport;
use App\Models\Section;
use App\Models\SubSection;
use App\Models\TypesFuel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DieselExportController extends Controller
{
    use CalculatesFuelBalances, ExportsCsv;

    public function __construct()
    {
        $this->middleware('permission:dieselexport-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:dieselexport-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:dieselexport-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:dieselexport-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $currentYear = date('Y');

        $dieselexport = DieselExport::with('section', 'subSection')->whereYear('date', $currentYear)->paginate(10);
        return view('dieselexport.index', compact('dieselexport'));

    }

    public function create()
    {
        //
        $section    = Section::get(['id', 'name_section']);
        $subSection = SubSection::get(['id', 'name']);
        $typesfuel  = TypesFuel::get(['id', 'name']);

        return view('dieselexport.create', compact('section', 'subSection', 'typesfuel'));

    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'section_id'     => 'required',
            'sub_section_id' => 'required',
            'typesfuel_id'   => 'required',
            'voucher'        => 'required|numeric|unique:diesel_exports,voucher',
            'num_section'    => 'required',
            'num_note'       => 'required|numeric',
            'quantity'       => 'required|numeric',
            'date'           => 'required|date',
            'notes'          => 'nullable|string|max:5000',
        ], $this->voucherMessages());

        $totalDieselQuantity       = Diesel::sum("quantity");
        $totalDieselExportQuantity = DieselExport::sum("quantity");

        if (($totalDieselQuantity - $totalDieselExportQuantity) < $validatedData['quantity']) {
            return back()->with('warning', 'تحقق من كمية المحروقات المتبقي');
        }

        DieselExport::create($validatedData);

        $request->user()->recordActivity(
            'قام بإضافة قيد صادر سولار - سند رقم ' . $validatedData['voucher'] . ' (الكمية: ' . $validatedData['quantity'] . ')',
            [
                'module' => 'diesel_export',
                'voucher' => $validatedData['voucher'],
                'quantity' => $validatedData['quantity'],
                'date' => $validatedData['date'],
            ]
        );

        return redirect()->route('dieselexport.index')
            ->with('success', 'تمت اضافة بيانات صادر المحروقات بنجاح.');
    }

    public function show($id)
    {
        //
        $dieselexport = DieselExport::find($id);
        return view('dieselexport.show')->with('dieselexport', $dieselexport);
    }

    public function edit($id)
    {
        //
        $dieselexport = DieselExport::find($id);
        $section      = Section::get(['id', 'name_section']);
        $subSection   = SubSection::get(['id', 'name']);
        $typesfuel    = TypesFuel::get(['id', 'name']);

        return view('dieselexport.edit', compact('dieselexport', 'typesfuel', 'section', 'subSection'))->with('dieselexport', $dieselexport);
    }

    public function update(Request $request, $id)
    {
        //
        $dieselexport = DieselExport::findOrFail($id);

        $request->validate([
            'voucher' => 'required|numeric|unique:diesel_exports,voucher,' . $dieselexport->id,
        ], $this->voucherMessages());

        $dieselexport->num_section = $request->num_section;
        $dieselexport->num_note    = $request->num_note;
        $dieselexport->section_id  = $request->section_id;
        $dieselexport->voucher     = $request->voucher;

        // لادخال كمية الصادر بناء على كمية الوارد
        if ((Diesel::sum("quantity") > $request->quantity)) {

            $dieselexport->quantity = $request->quantity;
        } else {
            return back()->with('warning', 'تحقق من كمية المحروقات المتبقي');
        }
        //انتها شرط الكمية
        $dieselexport->date         = $request->date;
        $dieselexport->typesfuel_id = $request->typesfuel_id;

        $dieselexport->notes = $request->notes;
        $dieselexport->save();
        return redirect('dieselexport')->with('info', 'تمت تعديل بيانات صادر المحروقات بنجاح!');
    }

    public function destroy(Request $request, $id)
    {
        $dieselexport = DieselExport::find($id);

        if (! $dieselexport) {
            $message = 'لم يتم العثور على الإيصال، ربما تم حذفه مسبقاً.';

            return $request->expectsJson()
                ? response()->json(['message' => $message], 404)
                : redirect()->route('dieselexport.index')->with('error', $message);
        }

        $dieselexport->delete();

        $message = 'تم حذف إيصال الصادر رقم ' . $dieselexport->voucher . ' بنجاح.';

        // الحذف يتم عبر AJAX: نعيد JSON بدلاً من إعادة التوجيه
        // (إعادة التوجيه كانت تُتبع كطلب DELETE فتفشل، وتبقى رسالة النجاح في الجلسة)
        return $request->expectsJson()
            ? response()->json(['message' => $message])
            : redirect()->route('dieselexport.index')->with('success', $message);
    }

    private function voucherMessages(): array
    {
        return [
            'voucher.required' => 'رقم الإيصال مطلوب.',
            'voucher.numeric'  => 'رقم الإيصال يجب أن يكون رقماً.',
            'voucher.unique'   => 'رقم الإيصال مستخدم مسبقاً، لا يمكن تكرار رقم الإيصال.',
        ];
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

            if ($request->filled('section_id')) {
                $query->where('section_id', $request->section_id);
            }

            if ($request->filled('sub_section_id')) {
                $query->where('sub_section_id', $request->sub_section_id);
            }

            if ($request->filled('start_voucher') && $request->filled('end_voucher')) {
                $query->whereBetween('voucher', [$request->start_voucher, $request->end_voucher]);
            }

            if ($request->filled('num_section')) {
                $query->where('num_section', $request->num_section);
            }

            if ($request->filled('num_note')) {
                $query->where('num_note', $request->num_note);
            }

            if ($request->filled('typesfuel_id')) {
                $query->where('typesfuel_id', $request->typesfuel_id);
            }

            return $query;
        };

        $query = $applyFilters(DieselExport::with(['section', 'subSection', 'typesfuel']))
            ->orderByDesc('date')
            ->orderByDesc('id');

        // تصدير جميع النتائج المفلترة إلى ملف Excel (CSV)
        if ($request->input('export') === 'excel') {
            return $this->exportReportCsv('diesel-export-report-' . date('Y-m-d') . '.csv', [
                'نوع المحروقات', 'الدائرة', 'القسم الفرعي', 'رقم الإيصال', 'كمية الصادر', 'الجهة المستلمة', 'رقم الدفتر', 'تاريخ الصادر',
            ], $query->get()->map(function ($row) {
                return [
                    $row->typesfuel->name ?? '-',
                    $row->section->name_section ?? '-',
                    $row->subSection->name ?? '-',
                    $row->voucher,
                    $row->quantity,
                    $row->num_section,
                    $row->num_note,
                    $row->date,
                ];
            }));
        }

        $perPage = in_array((int) $request->input('per_page'), [10, 25, 50, 100]) ? (int) $request->input('per_page') : 10;

        $dieselExports = $query->paginate($perPage)->withQueryString();

        // Fetch previous years' data
        $previousYearsData = DieselExport::selectRaw('YEAR(date) as year, MONTH(date) as month, SUM(quantity) as total')
            ->whereYear('date', '<', $currentYear)
            ->groupBy('year', 'month')
            ->get()
            ->groupBy('year');

        // بيانات الأشهر للسنة الحالية بنفس فلاتر الاستعلام الرئيسي
        $dieselData = $applyFilters(DieselExport::selectRaw('MONTH(date) as month, SUM(quantity) as total'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Fetch sections and sub-sections (الأقسام الفرعية للدائرة المختارة فقط)
        $section    = Section::all();
        $subSection = $request->filled('section_id')
            ? SubSection::where('section_id', $request->section_id)->get()
            : SubSection::all();

        // Calculate previous balance for Diesel (before start_date if provided)
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
        $totalExported = $applyFilters(DieselExport::query())->sum('quantity');
        $totalEntries  = $dieselExports->total();

        // حساب المتبقي لكل نوع من أنواع المحروقات بشكل ديناميكي
        // يتم إضافة عمود لكل نوع موجود في جدول أنواع المحروقات
        $fuelBalances = $this->fuelRemainingByType($request);

        $typesfuel       = $fuelBalances['typesfuel'];
        $remainingByType = $fuelBalances['remainingByType'];
        $totalRemaining  = $fuelBalances['totalRemaining'];

        return view('dieselexport.report', compact(
            'dieselData', 'previousYearsData', 'section', 'subSection',
            'previousBalance', 'totalExported', 'totalEntries', 'dieselExports',
            'typesfuel', 'remainingByType', 'totalRemaining', 'perPage'
        ));
    }

    // public function searchvou(Request $request)
    // {
    //     $section    = Section::get(['id', 'name_section']);
    //     $subSection = SubSection::get(['id', 'name']);
    //     // قم بتحميل السند الأول السند الثاني من النموذج
    //     $startVoucher = $request->input('start_voucher');
    //     $endVoucher   = $request->input('end_voucher');

    //     // قم بتنفيذ الاستعلام والحصول على نتيجة البحث
    //     $dieselexport = DieselExport::whereBetween('voucher', [$startVoucher, $endVoucher])->get();
    //     // $dieselexport=DieselExport::select('quantity')->whereBetween('voucher', [$startVoucher, $endVoucher])->sum('quantity')->get();

    //     // قم بعرض نتيجة البحث
    //     return view('dieselexport.report', compact('dieselexport', 'section', 'subSection'));
    // }

    // public function searchdate(Request $request)
    // {
    //     $section    = Section::get(['id', 'name_section']);
    //     $subSection = SubSection::get(['id', 'name']);

    //     // قم بتحميل التاريخ الأول والتاريخ الثاني من النموذج
    //     $startDate = $request->input('start_date');
    //     $endDate   = $request->input('end_date');

    //     // قم بتنفيذ الاستعلام والحصول على نتيجة البحث
    //     $dieselexport = DieselExport::whereBetween('date', [$startDate, $endDate])->get();

    //     // قم بعرض نتيجة البحث
    //     return view('dieselexport.report', compact('dieselexport', 'section', 'subSection'));
    // }

    // public function searchname(Request $request)
    // {
    //     $section    = Section::get(['id', 'name_section']);
    //     $subSection = SubSection::get(['id', 'name']);

    //     $startDate     = $request->input('start_date');
    //     $endDate       = $request->input('end_date');
    //     $section_id    = $request->input('section_id');
    //     $subSection_id = $request->input('subSection_id'); // تحميل القيمة الجديدة (إذا تم تحديدها)
    //     $dieselexport  = DieselExport::whereBetween('date', [$startDate, $endDate])
    //         ->where('section_id', 'like', '%' . $section_id . '%');
    //     if ($subSection_id) { // إذا تم تحديد قيمة للعنصر البحثي الجديد
    //         $dieselexport->where('subSection_id', 'like', '%' . $subSection_id . '%');
    //     }
    //     $dieselexport = $dieselexport->get();

    //     return view('dieselexport.report', compact('dieselexport', 'section', 'subSection'));
    // }

    public function totalreport(Request $request)
    {
        $currentYear = date('Y');

        $dieselexport = DieselExport::select("*")
            ->whereRaw('YEAR(diesel_exports.date) = ?', [$currentYear])
            ->get();

        $section    = Section::get(['id', 'name_section']);
        $subSection = SubSection::get(['id', 'name']);

        return view('dieselexport.total_report', compact('dieselexport', 'section', 'subSection'));
    }

    public function searchtotal(Request $request)
    {
        $section    = Section::get(['id', 'name_section']);
        $subSection = SubSection::get(['id', 'name']);

        // قم بتحميل السند الأول السند الثاني من النموذج
        $startVoucher = $request->input('start_voucher');
        $endVoucher   = $request->input('end_voucher');

        // قم بتنفيذ الاستعلام والحصول على نتيجة البحث
        $dieselexport = DieselExport::whereBetween('voucher', [$startVoucher, $endVoucher])->get();
        // قم بعرض نتيجة البحث
        return view('dieselexport.total_report', compact('dieselexport', 'section', 'subSection'));
    }
}