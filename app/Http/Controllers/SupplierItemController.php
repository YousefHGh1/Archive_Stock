<?php
namespace App\Http\Controllers;

use App\Models\SupplierItem;
use Illuminate\Http\Request;

class SupplierItemController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:supplier_items-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:supplier_items-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:supplier_items-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:supplier_items-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        // $supplier_item= SupplierItem::simplepaginate(5);
        $supplier_item = SupplierItem::all();
        return view('inventory.supplier_item.index', compact('supplier_item'));
    }

    public function create()
    {
        $lastSupplier  = SupplierItem::latest()->value('supplier_item_num') ?? 0;
        $NewSupplierNo = $lastSupplier + 1;
        return view('inventory.supplier_item.create', compact('NewSupplierNo'));
    }
    public function store(Request $request)
    {
        // Validate request data
        $validatedData = $request->validate([
            'supplier_item_name' => ['required', 'string', 'regex:/^[\p{L}\s]+$/u', 'max:255'],
            'address'            => ['required', 'string', 'regex:/^[\p{L}\s]+$/u', 'max:255'],
            'phone'              => ['required', 'regex:/^[0-9]+$/'],
        ]);

        // Generate new supplier item number
        $lastSupplierNum = SupplierItem::max('supplier_item_num') ?? 0;
        $newSupplierNum  = $lastSupplierNum + 1;

        // Add supplier item number to validated data
        $validatedData['supplier_item_num'] = $newSupplierNum;

        // Create supplier item
        $supplierItem = SupplierItem::create($validatedData);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'id' => $supplierItem->id,
                'supplier_item_name' => $supplierItem->supplier_item_name,
                'supplier_item_num' => $supplierItem->supplier_item_num,
            ], 201);
        }

        return redirect()->route('supplier_item.index')
            ->with('success', 'تم حفظ المورد بنجاح.');
    }

    public function edit($id)
    {
        $supplier_item = SupplierItem::find($id);
        return view('inventory.supplier_item.edit', compact('supplier_item'));
    }

    public function show($id)
    {
        $supplier_item = SupplierItem::find($id);
        // $supplier_item = SupplierItem::all();
        return view('inventory.supplier_item.show', compact('supplier_item'))->with('supplier_item', $supplier_item);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_item_num'  => 'required',
            'supplier_item_name' => 'required',
            'address'            => 'required',
            'phone'              => 'required',
        ]);

        $supplier_item                     = SupplierItem::find($id);
        $supplier_item->supplier_item_num  = $request->supplier_item_num;
        $supplier_item->supplier_item_name = $request->supplier_item_name;
        $supplier_item->address            = $request->address;
        $supplier_item->phone              = $request->phone;
        $supplier_item->save();
        return redirect('inventory/supplier_item')->with('success', 'تمت التعديل بنجاح!');
    }

    public function destroy($id)
    {
        SupplierItem::destroy($id);
        return redirect('inventory/supplier_item')->with('danger', ' تم الحذف بنجاح *');
    }

    public function report()
    {
        $supplier_item = SupplierItem::simplepaginate(5);
        return view('inventory.supplier_item.report', compact('supplier_item'));
    }

    public function search(Request $request)
    {
        $supplier_item_name = $request->input('supplier_item_name');
        $supplier_item      = SupplierItem::where('name', 'like', '%' . $supplier_item_name . '%')->get();
        return view('inventory.supplier_item.report', compact('supplier_item'));
    }
}
