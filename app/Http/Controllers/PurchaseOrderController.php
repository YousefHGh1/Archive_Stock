<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Item;
use App\Models\Unit;
use App\Models\Category;
use App\Notifications\PurchaseOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:purchases-read', ['only' => ['index', 'show', 'search']]);
        $this->middleware('permission:purchases-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:purchases-update', ['only' => [
            'edit',
            'update',
            'signPurchases',
            'signFinance',
            'signRequester',
            'updateStatus',
        ]]);
        $this->middleware('permission:purchases-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of purchase orders.
     */
    public function index()
    {
        $purchaseOrders = PurchaseOrder::with('purchase', 'user')->orderBy('created_at', 'desc')->get();
        return view('purchase-order.index', compact('purchaseOrders'));
    }

    /**
     * Show the form for creating a new purchase order.
     */
    public function create($purchaseId)
    {
        $purchase = Purchase::findOrFail($purchaseId);
        $items = Item::all();
        $units = Unit::all();
        $categories = Category::all();

        // Generate serial number
        $lastOrder = PurchaseOrder::orderBy('id', 'desc')->first();
        $nextSerial = $lastOrder ? 'PO-' . str_pad($lastOrder->id + 1, 5, '0', STR_PAD_LEFT) : 'PO-00001';

        return view('purchase-order.create', compact('purchase', 'items', 'units', 'categories', 'nextSerial'));
    }

    /**
     * Store a newly created purchase order.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'purchase_id' => 'required|exists:purchases,id',
            'status' => 'required|in:pending,approved,rejected',
            'rejection_reason' => 'nullable|required_if:status,rejected',
            'description' => 'nullable',
            'notes' => 'nullable',
            'purchase_date' => 'nullable|date',
            'purchase_attachments.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
        ]);

        $data = [];
        if ($request->hasfile('purchase_attachments')) {
            foreach ($request->file('purchase_attachments') as $file) {
                $name = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/uploads/', $name);
                $data[] = $name;
            }
        }

        $lastOrder = PurchaseOrder::orderBy('id', 'desc')->first();
        $serialNumber = $lastOrder ? 'PO-' . str_pad($lastOrder->id + 1, 5, '0', STR_PAD_LEFT) : 'PO-00001';

        $purchaseOrder = new PurchaseOrder([
            'purchase_id' => $request->purchase_id,
            'serial_number' => $serialNumber,
            'status' => $request->get('status'),
            'rejection_reason' => $request->get('rejection_reason'),
            'description' => $request->get('description'),
            'notes' => $request->get('notes'),
            'purchase_date' => $request->get('purchase_date'),
            'purchase_attachments' => json_encode($data),
            'purchases_signature' => false,
            'finance_signature' => false,
            'requester_signature' => false,
            'user_id' => Auth::id(),
        ]);

        if ($purchaseOrder->save()) {
            // Save items
            if ($request->has('items')) {
                foreach ($request->items as $itemData) {
                    $purchaseOrder->items()->create([
                        'item_id' => $itemData['item_id'] == 'new' ? null : ($itemData['item_id'] ?? null),
                        'item_name' => $itemData['item_name'],
                        'unit_id' => $itemData['unit_id'] ?? null,
                        'unit_name' => $itemData['unit_name'] ?? null,
                        'category_id' => $itemData['category_id'] ?? null,
                        'category_name' => $itemData['category_name'] ?? null,
                        'quantity' => $itemData['quantity'],
                        'notes' => $itemData['notes'] ?? null,
                    ]);
                }
            }

            return redirect('purchase')->with('success', 'تم إنشاء أمر الشراء بنجاح!');
        } else {
            return redirect()->back()->with('warning', 'تحقق من صحة البيانات!');
        }
    }

    /**
     * Display the specified purchase order.
     */
    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with('purchase', 'user', 'items')->findOrFail($id);
        return view('purchase-order.show', compact('purchaseOrder'));
    }

    /**
     * Show the form for editing the specified purchase order.
     */
    public function edit($id)
    {
        $purchaseOrder = PurchaseOrder::with('items')->findOrFail($id);
        $items = Item::all();
        $units = Unit::all();
        $categories = Category::all();
        return view('purchase-order.edit', compact('purchaseOrder', 'items', 'units', 'categories'));
    }

    /**
     * Update the specified purchase order.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'status' => 'required|in:pending,approved,rejected',
            'rejection_reason' => 'nullable|required_if:status,rejected',
            'description' => 'nullable',
            'notes' => 'nullable',
            'purchase_date' => 'nullable|date',
            'purchase_attachments.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:0',
        ]);

        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->status = $request->get('status');
        $purchaseOrder->rejection_reason = $request->get('rejection_reason');
        $purchaseOrder->description = $request->get('description');
        $purchaseOrder->notes = $request->get('notes');
        $purchaseOrder->purchase_date = $request->get('purchase_date');

        if ($request->hasfile('purchase_attachments')) {
            $data = [];
            foreach ($request->file('purchase_attachments') as $file) {
                $name = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/uploads/', $name);
                $data[] = $name;
            }
            $purchaseOrder->purchase_attachments = json_encode($data);
        }

        if ($purchaseOrder->save()) {
            // Update items
            if ($request->has('items')) {
                // Delete old items
                $purchaseOrder->items()->delete();

                // Create new items
                foreach ($request->items as $itemData) {
                    $purchaseOrder->items()->create([
                        'item_id' => $itemData['item_id'] == 'new' ? null : ($itemData['item_id'] ?? null),
                        'item_name' => $itemData['item_name'],
                        'unit_id' => $itemData['unit_id'] ?? null,
                        'unit_name' => $itemData['unit_name'] ?? null,
                        'category_id' => $itemData['category_id'] ?? null,
                        'category_name' => $itemData['category_name'] ?? null,
                        'quantity' => $itemData['quantity'],
                        'notes' => $itemData['notes'] ?? null,
                    ]);
                }
            }

            return redirect('purchase-order')->with('info', 'تم تعديل أمر الشراء بنجاح!');
        } else {
            return redirect()->back()->with('warning', 'تحقق من صحة البيانات!');
        }
    }

    /**
     * Remove the specified purchase order.
     */
    public function destroy($id)
    {
        PurchaseOrder::destroy($id);
        return redirect('purchase-order')->with('danger', 'تم حذف أمر الشراء بنجاح!');
    }

    /**
     * Sign as purchases department
     */
    public function signPurchases($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->purchases_signature = true;
        $purchaseOrder->save();

        return redirect()->back()->with('success', 'تم توقيع قسم المشتريات بنجاح!');
    }

    /**
     * Sign as finance department
     */
    public function signFinance($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->finance_signature = true;
        $purchaseOrder->save();

        return redirect()->back()->with('success', 'تم توقيع قسم المالية بنجاح!');
    }

    /**
     * Sign as requester
     */
    public function signRequester($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $purchaseOrder->requester_signature = true;
        $purchaseOrder->save();

        return redirect()->back()->with('success', 'تم توقيع مقدم الطلب بنجاح!');
    }

    /**
     * Update status (approve/reject)
     */
    public function updateStatus(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'nullable|required_if:status,rejected',
            'description' => 'nullable',
            'notes' => 'nullable',
            'purchase_date' => 'nullable|date',
            'purchase_attachments.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx',
        ]);

        $purchaseOrder->status = $request->status;
        $purchaseOrder->rejection_reason = $request->get('rejection_reason');
        $purchaseOrder->description = $request->get('description');
        $purchaseOrder->notes = $request->get('notes');

        // If approved, save purchase date and attachments
        if ($request->status == 'approved') {
            $purchaseOrder->purchase_date = $request->get('purchase_date');

            // Handle file uploads
            if ($request->hasfile('purchase_attachments')) {
                $data = [];
                foreach ($request->file('purchase_attachments') as $file) {
                    $name = uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path() . '/uploads/', $name);
                    $data[] = $name;
                }
                $purchaseOrder->purchase_attachments = json_encode($data);
            }
        }

        $purchaseOrder->save();

        if ($request->status == 'approved') {
            return redirect()->back()->with('success', 'تمت الموافقة على أمر الشراء!');
        } else {
            return redirect()->back()->with('warning', 'تم رفض أمر الشراء!');
        }
    }

    /**
     * Search purchase orders
     */
    public function search(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $purchaseOrders = PurchaseOrder::with('purchase', 'user');

        if ($search) {
            $purchaseOrders = $purchaseOrders->where('serial_number', 'like', '%' . $search . '%')
                ->orWhereHas('purchase', function ($query) use ($search) {
                    $query->where('subject', 'like', '%' . $search . '%');
                });
        }

        if ($status) {
            $purchaseOrders = $purchaseOrders->where('status', $status);
        }

        $purchaseOrders = $purchaseOrders->orderBy('created_at', 'desc')->get();

        return view('purchase-order.index', compact('purchaseOrders'));
    }
}
