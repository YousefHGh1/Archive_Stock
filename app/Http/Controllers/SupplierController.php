<?php
namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:suppliers-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:suppliers-create', ['only' => ['create']]);
        $this->middleware('permission:suppliers-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:suppliers-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        //
        $supplier = Supplier::get();
        return view('supplier.index', compact('supplier'));
    }

    public function create()
    {
        //
        $lastSupplier  = Supplier::latest()->value('num_supplier') ?? 0;
        $NewSupplierNo = $lastSupplier + 1;
        return view('supplier.create', compact('NewSupplierNo'));

    }

    public function store(Request $request)
    {
        //
        // Generate new supplier item number
        $lastSupplierNum = Supplier::max('num_supplier') ?? 0;
        $newSupplierNum  = $lastSupplierNum + 1;

        $request->validate([
            // 'num_supplier'  => 'required|numeric',
            'name_supplier' => 'required|string|regex:/^[\p{L}\s]+$/u|max:255',

        ]);
        $supplier                = new Supplier();
        $supplier->num_supplier  = $newSupplierNum;
        $supplier->name_supplier = $request->name_supplier;

        if ($supplier->save()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'id' => $supplier->id,
                    'name_supplier' => $supplier->name_supplier,
                    'num_supplier' => $supplier->num_supplier,
                ], 201);
            }

            return redirect('supplier')->with('success', 'تمت الاضافة بنجاح!');
        }
    }

    public function show(Supplier $supplier)
    {
        //
        // $supplier = Supplier::find($id);
        // return view('supplier.show')->with('supplier', $supplier);

        $num_supplier = $supplier->num_supplier;
        return response()->json(['message' => 'Success', 'data' => $num_supplier]);
    }

    public function edit($id)
    {
        //
        $supplier = Supplier::find($id);
        return view('supplier.edit', compact('supplier'))->with('supplier', $supplier);
    }

    public function update(Request $request, $id)
    {
        //
        $supplier = Supplier::find($id);

        if (!$supplier) {
            $message = 'المورد غير موجود';
            return $request->expectsJson()
                ? response()->json(['message' => $message], 404)
                : redirect('supplier')->with('error', $message);
        }

        $supplier->num_supplier  = $request->num_supplier;
        $supplier->name_supplier = $request->name_supplier;

        if ($supplier->save()) {
            $message = 'تمت التعديل بنجاح!';
            return $request->expectsJson()
                ? response()->json([
                    'id' => $supplier->id,
                    'name_supplier' => $supplier->name_supplier,
                    'num_supplier' => $supplier->num_supplier,
                    'message' => $message
                ])
                : redirect('supplier')->with('info', $message);
        }

        $message = 'فشل عملية التعديل';
        return $request->expectsJson()
            ? response()->json(['message' => $message], 500)
            : redirect('supplier')->with('error', $message);
    }

    public function destroy(Request $request, $id)
    {
        //
        $supplier = Supplier::find($id);

        if (!$supplier) {
            $message = 'المورد غير موجود';
            return $request->expectsJson()
                ? response()->json(['message' => $message], 404)
                : redirect('supplier')->with('error', $message);
        }

        $supplierName = $supplier->name_supplier;
        $supplierId = $supplier->id;

        if (Supplier::destroy($id)) {
            $message = 'تم حذف المورد "' . $supplierName . '" بنجاح';
            return $request->expectsJson()
                ? response()->json(['message' => $message])
                : redirect('supplier')->with('success', $message);
        }

        $message = 'فشل عملية الحذف';
        return $request->expectsJson()
            ? response()->json(['message' => $message], 500)
            : redirect('supplier')->with('error', $message);
    }
}