<?php
namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Category;
use App\Models\DeletedVoucherArchive;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Item;
use App\Models\SupplierItem;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
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

        $this->middleware('permission:invoices-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:invoices-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:invoices-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:invoices-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $invoices = DB::table('invoices')
            ->join('supplier_items', 'invoices.supplier_item_id', '=', 'supplier_items.id')
            ->join('currencies', 'invoices.currency_id', '=', 'currencies.id')
            ->join('invoice_products', 'invoices.id', '=', 'invoice_products.invoice_id')
            ->join('items', 'invoice_products.item_id', '=', 'items.id')
            ->whereNull('invoices.deleted_at')
            ->select(
                'invoices.id',
                'invoices.voucher_no',
                'invoices.voucher_date',
                'invoices.invoice_no',
                'invoices.notes',
                'supplier_items.supplier_item_name',
                'currencies.name',
                DB::raw('GROUP_CONCAT(items.item_name," (", invoice_products.quantity, ") &nbsp;") AS products')
            )
            ->groupBy(
                'invoices.id',
                'invoices.voucher_no',
                'invoices.voucher_date',
                'invoices.invoice_no',
                'invoices.notes',
                'supplier_items.supplier_item_name',
                'currencies.name'
            )
            ->get();

        $supplier_item = SupplierItem::all();
        $currency = Currency::all();
        $categories = Category::all();
        $units = Unit::all();

        return view('inventory.invoice.index', compact('invoices', 'supplier_item', 'currency', 'categories', 'units'));
    }

    public function create()
    {
        $lastVoucher  = DB::table('invoices')->max(DB::raw('CAST(voucher_no AS UNSIGNED)'));
        $newVoucherNo = $lastVoucher ? $lastVoucher + 1 : 1;

        $products      = Item::all();
        $supplier_item = SupplierItem::all();
        $currency      = Currency::all();
        $categories    = Category::all();
        $units         = Unit::all();
        return view('inventory.invoice.create', compact('products', 'supplier_item', 'currency', 'categories', 'units', 'newVoucherNo'));
    }

    public function store(Request $request)
    {
        $skipVoucherNo = (bool) $request->input('skip_voucher_no');

        // عند الضغط على زر "تخطي رقم السند" نسمح بحفظ فاتورة بدون أصناف (للمراقبة فقط)
        if ($skipVoucherNo) {
            $rules = [
                'voucher_date' => 'required|date',
                'invoice_no'   => 'nullable',
                'skip_reason'  => 'required|string|max:500',
                'notes'        => 'nullable|string|max:5000',
            ];

            $messages = [
                'voucher_date.required' => 'يرجى إدخال تاريخ السند.',
                'voucher_date.date'     => 'تاريخ السند غير صحيح.',
                'skip_reason.required'  => 'يرجى إدخال سبب تخطي رقم السند.',
                'skip_reason.string'    => 'سبب التخطي يجب أن يكون نصًا.',
                'skip_reason.max'       => 'سبب التخطي يجب ألا يتجاوز 500 حرف.',
            ];
        } else {
            $rules = [
                'voucher_date'     => 'required|date',
                'invoice_no'       => 'required',
                'supplier_item_id' => 'required',
                'currency_id'      => 'required',
                'product'          => 'required|array|min:1',
                'product.*'        => 'required|exists:items,id',
                'quantity'         => 'required|array|min:1',
                'quantity.*'       => 'required|numeric|min:0.1',
                'price'            => 'required|array|min:1',
                'price.*'          => 'required|numeric|min:0',
                'notes'            => 'nullable|string|max:5000',
            ];

            $messages = [
                'voucher_date.required'       => 'يرجى إدخال تاريخ السند.',
                'voucher_date.date'           => 'تاريخ السند غير صحيح.',
                'invoice_no.required'         => 'يرجى إدخال رقم الفاتورة.',
                'supplier_item_id.required'   => 'يرجى اختيار المورد.',
                'currency_id.required'        => 'يرجى اختيار معامل الصرف.',
                'product.required'            => 'يجب إضافة صنف واحد على الأقل.',
                'product.array'               => 'بيانات الأصناف غير صحيحة.',
                'product.min'                 => 'يجب إضافة صنف واحد على الأقل.',
                'product.*.required'          => 'يرجى اختيار الصنف.',
                'product.*.exists'            => 'الصنف المحدد غير موجود.',
                'quantity.required'           => 'يرجى إدخال الكمية.',
                'quantity.array'              => 'بيانات الكميات غير صحيحة.',
                'quantity.min'                => 'يجب إدخال كمية واحدة على الأقل.',
                'quantity.*.required'         => 'يرجى إدخال الكمية.',
                'quantity.*.numeric'          => 'الكمية يجب أن تكون رقمًا.',
                'quantity.*.min'              => 'الكمية يجب ألا تقل عن 0.1.',
                'price.required'              => 'يرجى إدخال السعر.',
                'price.array'                 => 'بيانات الأسعار غير صحيحة.',
                'price.min'                   => 'يجب إدخال سعر واحد على الأقل.',
                'price.*.required'            => 'يرجى إدخال السعر.',
                'price.*.numeric'             => 'السعر يجب أن يكون رقمًا.',
                'price.*.min'                 => 'السعر لا يمكن أن يكون أقل من صفر.',
            ];
        }

        $validatedData = $request->validate($rules, $messages ?? []);

        $lastVoucher  = DB::table('invoices')->max(DB::raw('CAST(voucher_no AS UNSIGNED)'));
        $newVoucherNo = $lastVoucher ? $lastVoucher + 1 : 1;

        $currency = null;
        if (! empty($validatedData['currency_id'])) {
            $currency = Currency::findOrFail($validatedData['currency_id']);
        }

        $invoice               = new Invoice();
        $invoice->voucher_no   = $newVoucherNo;
        $invoice->voucher_date = $validatedData['voucher_date'];

        // وضع قيم افتراضية عند تخطي رقم السند
        if ($skipVoucherNo) {
            $invoice->invoice_no       = 'SKIPPED-' . $newVoucherNo;
            $invoice->supplier_item_id = $validatedData['supplier_item_id'] ?? null;
            $invoice->currency_id      = $validatedData['currency_id'] ?? null;
        } else {
            $invoice->invoice_no       = $validatedData['invoice_no'];
            $invoice->supplier_item_id = $validatedData['supplier_item_id'];
            $invoice->currency_id      = $validatedData['currency_id'];
        }

        $invoice->currency_value_at_time = $currency ? $currency->value : 1;
        $invoice->skip_reason            = $validatedData['skip_reason'] ?? null;
        $invoice->notes                  = $validatedData['notes'] ?? null;
        $invoice->save();

        if ($skipVoucherNo) {
            $request->user()->recordActivity(
                'قام بإضافة فاتورة شراء (سند فارغ للمراقبة) - سند رقم ' . $invoice->voucher_no,
                [
                    'module'       => 'invoice',
                    'voucher_no'   => $invoice->voucher_no,
                    'invoice_no'   => $invoice->invoice_no,
                    'voucher_date' => $invoice->voucher_date,
                ]
            );

            if ($request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'تم حفظ فاتورة (سند فارغ للمراقبة) والانتقال للتالي.',
                    'redirect' => route('invoice.create'),
                ]);
            }
            return redirect()->route('invoice.create')->with('success', 'تم حفظ فاتورة (سند فارغ للمراقبة) والانتقال للتالي.');
        }

        // التحقق من وجود حقول المنتجات
        $products = $request->input('product', []);

        if (empty($products)) {
            return redirect()->back()->with('error', 'يجب إضافة منتج واحد على الأقل.');
        }

        for ($i = 0; $i < count($products); $i++) {
            if (! isset($products[$i]) || empty($products[$i])) {
                continue;
            }

            $product  = Item::findOrFail($products[$i]);
            $quantity = $request->input("quantity.$i", 0);
            $price    = $request->input("price.$i", 0);

            $invoiceProduct             = new InvoiceProduct();
            $invoiceProduct->invoice_id = $invoice->id;
            $invoiceProduct->item_id    = $product->id;

            // تحديث المخزون
            $invoiceProduct->quantity = $quantity;
            if ($invoiceProduct->quantity) {
                $item           = Item::findOrFail($invoiceProduct->item_id);
                $item->balance += $invoiceProduct->quantity;
                $item->update();
            }

            $invoiceProduct->price = $price;
            $invoiceProduct->save();
        }

        $request->user()->recordActivity(
            'قام بإضافة فاتورة شراء - سند رقم ' . $invoice->voucher_no,
            [
                'module'         => 'invoice',
                'voucher_no'     => $invoice->voucher_no,
                'invoice_no'     => $invoice->invoice_no,
                'voucher_date'   => $invoice->voucher_date,
                'products_count' => count($products),
            ]
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الفاتورة بنجاح.',
                'redirect' => route('invoice.index'),
            ]);
        }

        return redirect()->back()->with('success', 'تم حفظ الفاتورة بنجاح.');
    }

    public function show($id)
    {
        $invoice = Invoice::with(['supplierItem', 'currency', 'invoiceproduct.item'])->findOrFail($id);

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'invoice' => [
                    'id' => $invoice->id,
                    'voucher_no' => $invoice->voucher_no,
                    'voucher_date' => $invoice->voucher_date,
                    'invoice_no' => $invoice->invoice_no,
                    'supplier_item_id' => $invoice->supplier_item_id,
                    'currency_id' => $invoice->currency_id,
                    'notes' => $invoice->notes,
                    'supplier_item_name' => $invoice->supplierItem->supplier_item_name ?? '',
                    'currency_name' => $invoice->currency->name ?? '',
                    'products' => $invoice->invoiceproduct->map(function($product) {
                        return [
                            'item_id' => $product->item_id,
                            'item_name' => $product->item->item_name ?? '',
                            'item_num' => $product->item->item_num ?? '',
                            'quantity' => $product->quantity,
                            'price' => $product->price,
                        ];
                    })->toArray(),
                ]
            ]);
        }

        $invoiceProducts = InvoiceProduct::where('invoice_id', $id)->get();

        return view('inventory.invoice.show', compact('invoice', 'invoiceProducts'));
    }

    public function edit($id)
    {
        $invoice       = Invoice::findOrFail($id);
        $products      = Item::all();
        $supplier_item = SupplierItem::all();
        $currency      = Currency::all();

        return view('inventory.invoice.edit', compact('products', 'supplier_item', 'currency', 'invoice'));
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $validatedData = $request->validate([
            'voucher_no'       => 'required',
            'voucher_date'     => 'required|date',
            'invoice_no'       => 'required',
            'supplier_item_id' => 'required',
            'currency_id'      => 'required',
            'product'          => 'required|array|min:1',
            'product.*'        => 'required|exists:items,id',
            'quantity'         => 'required|array|min:1',
            'quantity.*'       => 'required|numeric|min:0.1',
            'price'            => 'required|array|min:1',
            'price.*'          => 'required|numeric|min:0',
            'notes'            => 'nullable|string|max:5000',
        ], [
            'voucher_date.required'     => 'يرجى إدخال تاريخ السند.',
            'voucher_date.date'         => 'تاريخ السند غير صحيح.',
            'invoice_no.required'       => 'يرجى إدخال رقم الفاتورة.',
            'supplier_item_id.required' => 'يرجى اختيار المورد.',
            'currency_id.required'      => 'يرجى اختيار معامل الصرف.',
            'product.required'          => 'يجب إضافة صنف واحد على الأقل.',
            'product.*.required'        => 'يرجى اختيار الصنف.',
            'product.*.exists'          => 'الصنف المحدد غير موجود.',
            'quantity.required'         => 'يرجى إدخال الكمية.',
            'quantity.*.required'       => 'يرجى إدخال الكمية.',
            'quantity.*.numeric'        => 'الكمية يجب أن تكون رقمًا.',
            'quantity.*.min'            => 'الكمية يجب ألا تقل عن 0.1.',
            'price.required'            => 'يرجى إدخال السعر.',
            'price.*.required'          => 'يرجى إدخال السعر.',
            'price.*.numeric'           => 'السعر يجب أن يكون رقمًا.',
            'price.*.min'               => 'السعر لا يمكن أن يكون أقل من صفر.',
        ]);

        DB::transaction(function () use ($validatedData, $id) {
            $invoice = Invoice::findOrFail($id);
            $currency = Currency::findOrFail($validatedData['currency_id']);

            $invoice->voucher_no = $validatedData['voucher_no'];
            $invoice->voucher_date = $validatedData['voucher_date'];
            $invoice->invoice_no = $validatedData['invoice_no'];
            $invoice->supplier_item_id = $validatedData['supplier_item_id'];
            $invoice->currency_id = $validatedData['currency_id'];
            $invoice->currency_value_at_time = $currency->value;
            $invoice->notes = $validatedData['notes'] ?? null;
            $invoice->save();

            foreach ($invoice->invoiceproduct as $existingProduct) {
                $item = Item::whereKey($existingProduct->item_id)->lockForUpdate()->firstOrFail();
                $item->balance -= $existingProduct->quantity;
                $item->save();
                $existingProduct->delete();
            }

            foreach ($validatedData['product'] as $index => $itemId) {
                $item = Item::whereKey($itemId)->lockForUpdate()->firstOrFail();
                $quantity = $validatedData['quantity'][$index];

                $item->balance += $quantity;
                $item->save();

                $invoice->invoiceproduct()->create([
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'price' => $validatedData['price'][$index],
                ]);
            }
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الفاتورة بنجاح.',
            ]);
        }

        return redirect()->back()->with('success', 'تم تحديث الفاتورة بنجاح.');
    }

    public function destroy($id, Request $request)
    {
        return $this->safeDelete($id, $request);
    }

    private function safeDelete($id, Request $request)
    {
        try {
            DB::beginTransaction();

            $invoice = Invoice::findOrFail($id);

            if ($invoice->deleted_at) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'هذه الفاتورة محذوفة بالفعل'
                    ], 400);
                }
                return redirect()->back()->with('error', 'هذه الفاتورة محذوفة بالفعل');
            }

            $archiveData = [
                'voucher_type'  => 'invoice',
                'voucher_no'    => $invoice->voucher_no,
                'voucher_date'  => $invoice->voucher_date,
                'invoice_no'    => $invoice->invoice_no,
                'original_id'   => $invoice->id,
                'original_data' => $invoice->toArray(),
                'deleted_by'    => Auth::id(),
                'delete_reason' => $request->input('reason', 'حذف يدوي'),
                'deleted_at'    => now(),
            ];

            DeletedVoucherArchive::create($archiveData);

            foreach ($invoice->invoiceproduct as $product) {
                $item           = Item::findOrFail($product->item_id);
                $item->balance -= $product->quantity;
                $item->update();
            }

            $invoice->status        = 'deleted';
            $invoice->deleted_by    = Auth::id();
            $invoice->delete_reason = $request->input('reason', 'حذف يدوي');
            $invoice->save();
            $invoice->delete();

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف الفاتورة بنجاح ونقلها إلى الأرشيف'
                ]);
            }

            return redirect()->back()->with('success', 'تم حذف الفاتورة بنجاح ونقلها إلى الأرشيف');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting invoice: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حذف الفاتورة: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف الفاتورة: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();

            $invoice = Invoice::withTrashed()->findOrFail($id);

            if (! $invoice->trashed()) {
                return redirect()->back()->with('error', 'هذه الفاتورة ليست محذوفة');
            }

            foreach ($invoice->invoiceproduct as $product) {
                $item           = Item::findOrFail($product->item_id);
                $item->balance += $product->quantity;
                $item->update();
            }

            $invoice->status        = 'active';
            $invoice->deleted_by    = null;
            $invoice->delete_reason = null;
            $invoice->restore();

            DB::commit();

            return redirect()->back()->with('success', 'تم استعادة الفاتورة بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error restoring invoice: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء استعادة الفاتورة: ' . $e->getMessage());
        }
    }

    public function searchdate(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        $invoices = DB::table('invoices')
            ->join('supplier_items', 'invoices.supplier_item_id', '=', 'supplier_items.id')
            ->join('invoice_products', 'invoices.id', '=', 'invoice_products.invoice_id')
            ->join('items', 'invoice_products.item_id', '=', 'items.id')
            ->whereNull('invoices.deleted_at')
            ->select(
                'invoices.id',
                'invoices.voucher_no',
                'invoices.voucher_date',
                'invoices.invoice_no',
                'supplier_items.supplier_item_name',
                DB::raw('GROUP_CONCAT(items.item_name," (", invoice_products.quantity, ") &nbsp;") AS products')
            )
            ->whereBetween('invoices.voucher_date', [$startDate, $endDate])
            ->groupBy('invoices.id', 'invoices.voucher_no', 'invoices.voucher_date', 'invoices.invoice_no', 'supplier_items.supplier_item_name')
            ->get();

        return view('inventory.invoice.index', compact('invoices'))->with('success', 'تمت عملية البحث !');
    }

    public function skippedVouchers()
    {
        $skippedVouchers = DB::table('invoices')
            ->leftJoin('invoice_products', 'invoices.id', '=', 'invoice_products.invoice_id')
            ->whereNull('invoices.deleted_at')
            ->whereNull('invoice_products.id')
            ->select(
                'invoices.id',
                'invoices.voucher_no',
                'invoices.voucher_date',
                'invoices.invoice_no',
                'invoices.supplier_item_id',
                'invoices.currency_id',
                'invoices.skip_reason'
            )
            ->orderBy('invoices.voucher_no', 'asc')
            ->get();

        return view('inventory.invoice.skipped-vouchers', compact('skippedVouchers'));
    }
}
