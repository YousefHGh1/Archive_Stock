<?php
namespace App\Http\Controllers;

use App\Models\DeletedVoucherArchive;
use App\Models\InvoiceExport;
use App\Models\InvoiceExport_product;
use App\Models\Category;
use App\Models\Item;
use App\Models\Section;
use App\Models\SubSection;
use App\Models\Unit;
use App\Models\User;
use App\Models\Beneficiary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceExportController extends Controller
{

    public function __construct()
    {
        // السماح للسوبر يوزر بالتجاوز من التحقق من الصلاحيات
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->hasRole('Super-User')) {
                return $next($request);
            }
            return $next($request);
        });

        $this->middleware('permission:invoice_exports-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:invoice_exports-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:invoice_exports-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:invoice_exports-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $invoice_exports = DB::table('invoice_exports')
            ->join('sub_sections', 'invoice_exports.subSection_id', '=', 'sub_sections.id')
            ->join('invoice_export_products', 'invoice_exports.id', '=', 'invoice_export_products.invoice_export_id')
            ->join('items', 'invoice_export_products.item_id', '=', 'items.id')
            ->whereNull('invoice_exports.deleted_at')
            ->select('invoice_exports.id', 'invoice_exports.voucher_no', 'invoice_exports.voucher_date', 'invoice_exports.invoice_no', 'invoice_exports.beneficiary', 'invoice_exports.notes', 'sub_sections.name', DB::raw('GROUP_CONCAT(items.item_name," (", invoice_export_products.quantity, ") &nbsp;") AS products'))
            ->groupBy('invoice_exports.id', 'invoice_exports.voucher_no', 'invoice_exports.voucher_date', 'invoice_exports.invoice_no', 'invoice_exports.beneficiary', 'invoice_exports.notes', 'sub_sections.name')
            ->get();

        $subSection = SubSection::all();
        $categories = Category::all();
        $units = Unit::all();

        // المستلمون من جدول beneficiaries
        $beneficiaries = Beneficiary::where('is_active', true)->orderBy('name')->pluck('name', 'id');

        return view('inventory.invoice_export.index', compact('invoice_exports', 'subSection', 'beneficiaries', 'categories', 'units'));
    }

    public function create()
    {
        $lastVoucher  = DB::table('invoice_exports')->max(DB::raw('CAST(voucher_no AS UNSIGNED)'));
        $newVoucherNo = $lastVoucher ? $lastVoucher + 1 : 1;

        // Fetch only products with balance > 0
        $products   = Item::where('balance', '>', 0)->get();
        $subSection = SubSection::all();
        $sections   = Section::all();
        $categories = Category::all();
        $units      = Unit::all();
        $user       = User::all();

        // المستلمون من جدول beneficiaries
        $beneficiaries = Beneficiary::where('is_active', true)->orderBy('name')->pluck('name', 'id');

        return view('inventory.invoice_export.create', compact(
            'beneficiaries',
            'products',
            'subSection',
            'sections',
            'categories',
            'units',
            'user',
            'newVoucherNo'
        ));
    }

    public function store(Request $request)
    {
        $skipVoucherNo = (bool) $request->input('skip_voucher_no');

        // عند الضغط على زر "تخطي رقم السند" نسمح بحفظ فاتورة بدون أصناف (للمراقبة فقط)
        if ($skipVoucherNo) {
            $rules = [
                'voucher_date' => 'required|date',
                'skip_reason'  => 'required|string|max:500',
                'notes'        => 'nullable|string|max:5000',
            ];
        } else {
            $rules = [
                'voucher_date'  => 'required|date',
                'invoice_no'    => 'required',
                'subSection_id' => 'required',
                'beneficiary'   => 'required',
                'notes'         => 'nullable|string|max:5000',
                'product'       => 'required|array|min:1',
                'product.*'     => 'required|exists:items,id',
                'quantity'      => 'required|array|min:1',
                'quantity.*'    => 'required|numeric|min:0.1',
            ];

            $messages = [
                'voucher_date.required'    => 'يرجى إدخال تاريخ السند.',
                'voucher_date.date'        => 'تاريخ السند غير صحيح.',
                'invoice_no.required'      => 'يرجى إدخال رقم الفاتورة.',
                'subSection_id.required'   => 'يرجى اختيار القسم الفرعي.',
                'beneficiary.required'     => 'يرجى إدخال الجهة المستفيدة.',
                'product.required'         => 'يجب إضافة صنف واحد على الأقل.',
                'product.*.required'       => 'يرجى اختيار الصنف.',
                'product.*.exists'         => 'الصنف المحدد غير موجود.',
                'quantity.required'        => 'يرجى إدخال الكمية.',
                'quantity.*.required'      => 'يرجى إدخال الكمية.',
                'quantity.*.numeric'       => 'الكمية يجب أن تكون رقمًا.',
                'quantity.*.min'           => 'الكمية يجب ألا تقل عن 0.1.',
            ];
        }

        $validatedData = $request->validate($rules, $messages ?? []);

        // الحصول على آخر رقم فاتورة وزيادته (يشمل المحذوفة لمنع إعادة الاستخدام)
        $lastVoucher  = DB::table('invoice_exports')->max(DB::raw('CAST(voucher_no AS UNSIGNED)'));
        $newVoucherNo = $lastVoucher ? $lastVoucher + 1 : 1;

        // إنشاء فاتورة جديدة
        $invoiceExport                = new InvoiceExport();
        $invoiceExport->voucher_no    = $newVoucherNo;
        $invoiceExport->voucher_date  = $validatedData['voucher_date'];

        // وضع قيم افتراضية عند تخطي رقم السند
        if ($skipVoucherNo) {
            $invoiceExport->invoice_no = 'SKIPPED-' . $newVoucherNo;
            $invoiceExport->subSection_id = $validatedData['subSection_id'] ?? null;
            $invoiceExport->beneficiary = $validatedData['beneficiary'] ?? null;
        } else {
            $invoiceExport->invoice_no = $validatedData['invoice_no'];
            $invoiceExport->subSection_id = $validatedData['subSection_id'];
            $invoiceExport->beneficiary = $validatedData['beneficiary'];
        }

        $invoiceExport->skip_reason = $validatedData['skip_reason'] ?? null;
        $invoiceExport->notes = $validatedData['notes'] ?? null;
        $invoiceExport->save();

        if ($skipVoucherNo) {
            $request->user()->recordActivity(
                'قام بإضافة فاتورة صرف (سند فارغ للمراقبة) - سند رقم ' . $invoiceExport->voucher_no,
                [
                    'module'       => 'invoice_export',
                    'voucher_no'   => $invoiceExport->voucher_no,
                    'invoice_no'   => $invoiceExport->invoice_no,
                    'voucher_date' => $invoiceExport->voucher_date,
                ]
            );

            return redirect()->route('invoice_export.create')->with('success', 'تم حفظ فاتورة (سند فارغ للمراقبة) والانتقال للتالي.');
        }

        // حفظ الأصناف المضافة إلى الفاتورة
        for ($i = 0; $i < count($validatedData['product']); $i++) {
            $product  = Item::findOrFail($validatedData['product'][$i]);
            $quantity = $validatedData['quantity'][$i];

            $invoiceExport_product                    = new InvoiceExport_product();
            $invoiceExport_product->invoice_export_id = $invoiceExport->id;
            $invoiceExport_product->item_id           = $product->id;

            if ($invoiceExport_product->quantity = $quantity) {
                // Find the item
                $item = Item::findOrFail($invoiceExport_product->item_id);

                // Update the item's balance
                if ($invoiceExport_product->quantity > $item->balance) {
                    return redirect('inventory/invoice_export/create')->with('warning', 'الكمية المطلوبة غير متوفرة ' . ' للصنف  :' . $item->item_name . ' : الرصيد الحالي هو ' . $item->balance);
                }
                $item->balance -= $invoiceExport_product->quantity;

                // Save the item
                $item->update();
            }

            $invoiceExport_product->save();
        }

        $request->user()->recordActivity(
            'قام بإضافة فاتورة صرف - سند رقم ' . $invoiceExport->voucher_no,
            [
                'module'         => 'invoice_export',
                'voucher_no'     => $invoiceExport->voucher_no,
                'invoice_no'     => $invoiceExport->invoice_no,
                'voucher_date'   => $invoiceExport->voucher_date,
                'beneficiary'    => $invoiceExport->beneficiary,
                'products_count' => count($validatedData['product']),
            ]
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الفاتورة بنجاح.',
                'redirect' => route('invoice_export.index'),
            ]);
        }

        // رسالة نجاح العملية
        return redirect()->back()->with('success', 'تم حفظ الفاتورة بنجاح.');
    }

    public function show($id)
    {
        $invoiceExport = InvoiceExport::with(['subSection', 'InvoiceExport_product.item'])->findOrFail($id);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'invoice_export' => [
                    'id' => $invoiceExport->id,
                    'voucher_no' => $invoiceExport->voucher_no,
                    'voucher_date' => $invoiceExport->voucher_date,
                    'invoice_no' => $invoiceExport->invoice_no,
                    'subSection_id' => $invoiceExport->subSection_id,
                    'beneficiary' => $invoiceExport->beneficiary,
                    'notes' => $invoiceExport->notes,
                    'subSection_name' => $invoiceExport->subSection->name ?? '',
                    'products' => $invoiceExport->InvoiceExport_product->map(function($product) {
                        return [
                            'item_id' => $product->item_id,
                            'item_name' => $product->item->item_name ?? '',
                            'item_num' => $product->item->item_num ?? '',
                            'quantity' => $product->quantity,
                        ];
                    })->toArray(),
                ]
            ]);
        }

        $invoiceExport_products = invoiceExport_product::where('invoice_export_id', $id)->get();

        return view('inventory.invoice_export.show', compact('invoiceExport', 'invoiceExport_products'));
    }

    public function edit($id)
    {
        //
        $invoiceExport = InvoiceExport::findOrFail($id);
        $products      = Item::all();
        $subSection    = SubSection::all();
        return view('inventory.invoice_export.edit', compact('products', 'subSection', 'invoiceExport'));
    }

    public function update(Request $request, $id)
    {
        // التحقق من صحة البيانات المدخلة
        $validatedData = $request->validate([
            'voucher_no'    => 'required|unique:invoice_exports,voucher_no,' . $id,
            'voucher_date'  => 'required|date',
            'invoice_no'    => 'required',
            'subSection_id' => 'required',
            'notes'         => 'nullable|string|max:5000',
            'product'       => 'required|array|min:1',
            'product.*'     => 'required|exists:items,id',
            'quantity'      => 'required|array|min:1',
            'quantity.*'    => 'required|numeric|min:0.1',
            // 'price.*' => 'required|numeric|min:0',
        ], [
            'voucher_date.required'   => 'يرجى إدخال تاريخ السند.',
            'invoice_no.required'     => 'يرجى إدخال رقم الفاتورة.',
            'product.required'        => 'يجب إضافة صنف واحد على الأقل.',
            'product.*.required'      => 'يرجى اختيار الصنف.',
            'quantity.required'       => 'يرجى إدخال الكمية.',
            'quantity.*.required'     => 'يرجى إدخال الكمية.',
            'quantity.*.numeric'      => 'الكمية يجب أن تكون رقمًا.',
            'quantity.*.min'          => 'الكمية يجب ألا تقل عن 0.1.',
        ]);

        DB::transaction(function () use ($validatedData, $id) {
            $invoiceExport = InvoiceExport::findOrFail($id);
            $invoiceExport->voucher_no = $validatedData['voucher_no'];
            $invoiceExport->voucher_date = $validatedData['voucher_date'];
            $invoiceExport->invoice_no = $validatedData['invoice_no'];
            $invoiceExport->subSection_id = $validatedData['subSection_id'];
            $invoiceExport->notes = $validatedData['notes'] ?? null;
            $invoiceExport->save();

            foreach ($invoiceExport->InvoiceExport_product as $existingProduct) {
                $item = Item::whereKey($existingProduct->item_id)->lockForUpdate()->firstOrFail();
                $item->balance += $existingProduct->quantity;
                $item->save();
            }

            foreach ($invoiceExport->InvoiceExport_product as $existingProduct) {
                $existingProduct->delete();
            }

            foreach ($validatedData['product'] as $index => $itemId) {
                $item = Item::whereKey($itemId)->lockForUpdate()->firstOrFail();
                $quantity = $validatedData['quantity'][$index];

                if ($quantity > $item->balance) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'quantity.' . $index => 'الكمية المطلوبة غير متوفرة للصنف ' . $item->item_name . '، الرصيد الحالي هو ' . $item->balance . '.',
                    ]);
                }

                $item->balance -= $quantity;
                $item->save();

                $invoiceExport->InvoiceExport_product()->create([
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                ]);
            }
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الفاتورة بنجاح.',
            ]);
        }

        // رسالة نجاح العملية
        return redirect()->back()->with('success', 'تم تحديث الفاتورة بنجاح.');
    }

    public function destroy($id, Request $request)
    {
        return $this->safeDelete($id, $request);
    }

    /**
     * Safe delete method with audit trail
     */
    private function safeDelete($id, Request $request)
    {
        try {
            DB::beginTransaction();

            // العثور على الفاتورة المطلوبة
            $invoiceExport = InvoiceExport::findOrFail($id);

            // التحقق من أن الفاتورة ليست محذوفة بالفعل
            if ($invoiceExport->deleted_at) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'هذا السند محذوف بالفعل'
                    ], 400);
                }
                return redirect()->back()->with('error', 'هذا السند محذوف بالفعل');
            }

            // نسخ البيانات الأصلية للأرشيف
            $archiveData = [
                'voucher_type'  => 'invoice_export',
                'voucher_no'    => $invoiceExport->voucher_no,
                'voucher_date'  => $invoiceExport->voucher_date,
                'invoice_no'    => $invoiceExport->invoice_no,
                'original_id'   => $invoiceExport->id,
                'original_data' => $invoiceExport->toArray(),
                'deleted_by'    => Auth::id(),
                'delete_reason' => $request->input('reason', 'حذف يدوي'),
                'deleted_at'    => now(),
            ];

            // إنشاء سجل الأرشيف
            DeletedVoucherArchive::create($archiveData);

            // استرجاع الأصناف المرتبطة بالفاتورة (إلغاء تأثير الفاتورة على المخزون)
            foreach ($invoiceExport->InvoiceExport_product as $product) {
                $item           = Item::findOrFail($product->item_id);
                $item->balance += $product->quantity;
                $item->update();
            }

            // تحديث حالة الفاتورة وتمييزها كمحذوفة
            $invoiceExport->status        = 'deleted';
            $invoiceExport->deleted_by    = Auth::id();
            $invoiceExport->delete_reason = $request->input('reason', 'حذف يدوي');
            $invoiceExport->save();
            $invoiceExport->delete();

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف السند بنجاح ونقله إلى الأرشيف'
                ]);
            }

            return redirect()->back()->with('success', 'تم حذف السند بنجاح ونقله إلى الأرشيف');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting invoice export: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حذف السند: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف السند: ' . $e->getMessage());
        }
    }

    /**
     * Restore deleted invoice export
     */
    public function restore($id)
    {
        try {
            DB::beginTransaction();

            $invoiceExport = InvoiceExport::withTrashed()->findOrFail($id);

            if (! $invoiceExport->trashed()) {
                return redirect()->back()->with('error', 'هذا السند ليس محذوفاً');
            }

            // استعادة تأثير الفاتورة على المخزون
            foreach ($invoiceExport->InvoiceExport_product as $product) {
                $item           = Item::findOrFail($product->item_id);
                $item->balance -= $product->quantity;
                $item->update();
            }

            // استعادة الفاتورة
            $invoiceExport->status        = 'active';
            $invoiceExport->deleted_by    = null;
            $invoiceExport->delete_reason = null;
            $invoiceExport->restore();

            DB::commit();

            return redirect()->back()->with('success', 'تم استعادة السند بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error restoring invoice export: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء استعادة السند: ' . $e->getMessage());
        }
    }

    public function searchdate(Request $request)
    {
        // استخراج تاريخ البدء وتاريخ الانتهاء من النموذج
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $invoice_exports = DB::table('invoice_exports')
            ->join('sub_sections', 'invoice_exports.subSection_id', '=', 'sub_sections.id')
            ->join('invoice_export_products', 'invoice_exports.id', '=', 'invoice_export_products.invoice_export_id')
            ->join('items', 'invoice_export_products.item_id', '=', 'items.id')
            ->whereNull('invoice_exports.deleted_at') // Exclude soft deleted records
            ->select('invoice_exports.id', 'invoice_exports.voucher_no', 'invoice_exports.voucher_date', 'invoice_exports.invoice_no', 'sub_sections.name', DB::raw('GROUP_CONCAT(items.item_name," (", invoice_export_products.quantity, ") &nbsp;") AS products'))
            ->whereBetween('invoice_exports.voucher_date', [$startDate, $endDate])
            ->groupBy('invoice_exports.id', 'invoice_exports.voucher_no', 'invoice_exports.voucher_date', 'invoice_exports.invoice_no', 'sub_sections.name')
            ->get();
        // عرض نتائج البحث
        return view('inventory.invoice_export.index', compact('invoice_exports'))->with('success', 'تمت عملية البحث !');
    }

    public function skippedVouchers()
    {
        $skippedVouchers = DB::table('invoice_exports')
            ->leftJoin('invoice_export_products', 'invoice_exports.id', '=', 'invoice_export_products.invoice_export_id')
            ->whereNull('invoice_exports.deleted_at')
            ->whereNull('invoice_export_products.id')
            ->select(
                'invoice_exports.id',
                'invoice_exports.voucher_no',
                'invoice_exports.voucher_date',
                'invoice_exports.invoice_no',
                'invoice_exports.subSection_id',
                'invoice_exports.beneficiary',
                'invoice_exports.skip_reason'
            )
            ->orderBy('invoice_exports.voucher_no', 'asc')
            ->get();

        return view('inventory.invoice_export.skipped-vouchers', compact('skippedVouchers'));
    }

}
