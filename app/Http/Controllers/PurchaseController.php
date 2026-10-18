<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseRequestStatusLog;
use App\Models\User;
use App\Models\Section;
use App\Notifications\CreatepurchaseNot;
use App\Notifications\StockEntryNotification;
use App\Events\PurchaseRequestStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:purchases-read', ['only' => ['index', 'show', 'search', 'councilDecision']]);
        $this->middleware('permission:purchases-create', ['only' => ['create', 'store', 'reopen']]);
        $this->middleware('permission:purchases-update', ['only' => [
            'edit',
            'update',
            'signPresident',
            'unsignPresident',
            'signRequester',
            'signFinance',
            'unsignFinance',
            'updateStatus',
            'updateStockEntry',
            'updateRentRepair',
            'storeCouncilDecision',
            'withdraw',
        ]]);
        $this->middleware('permission:purchases-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $purchases = Purchase::with('user', 'section')->orderBy('created_at', 'desc')->get();
        return view('purchase.index', compact('purchases'));
    }

    public function create()
    {
        $sections = Section::all();
        $users = User::all();
        $lastPurchase = Purchase::orderBy('id', 'desc')->first();
        $nextSerial = $lastPurchase ? 'PUR-' . str_pad($lastPurchase->id + 1, 5, '0', STR_PAD_LEFT) : 'PUR-00001';
        return view('purchase.create', compact('sections', 'users', 'nextSerial'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required',
            'description' => 'nullable',
            'requester_name' => 'nullable',
            'notes' => 'nullable',
            'section_id' => 'required',
            'files.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,DWG,DXF,DWF',
        ]);

        $data = [];
        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $file) {
                $name = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/uploads/', $name);
                $data[] = $name;
            }
        }

        $lastPurchase = Purchase::orderBy('id', 'desc')->first();
        $serialNumber = $lastPurchase ? 'PUR-' . str_pad($lastPurchase->id + 1, 5, '0', STR_PAD_LEFT) : 'PUR-00001';

        $purchase = new Purchase([
            'serial_number' => $serialNumber,
            'subject' => $request->get('subject'),
            'description' => $request->get('description'),
            'notes' => $request->get('notes'),
            'requester_name' => $request->get('requester_name'),
            'section_id' => $request->get('section_id'),
            'user_id' => Auth::id(),
            'files' => json_encode($data),
            'status' => 'pending',
            'president_signature' => false,
            'requester_signature' => false,
        ]);

        if ($purchase->save()) {
            return redirect('purchase')->with('success', 'تمت إضافة طلب الشراء بنجاح!');
        } else {
            return redirect('purchase/create')->with('warning', 'تحقق من صحة البيانات!');
        }
    }

    public function show($id)
    {
        $purchase = Purchase::with('user', 'section')->findOrFail($id);
        return view('purchase.show', compact('purchase'));
    }

    public function edit($id)
    {
        $purchase = Purchase::findOrFail($id);
        $sections = Section::all();
        $users = User::all();
        return view('purchase.edit', compact('purchase', 'sections', 'users'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'subject' => 'required',
            'description' => 'nullable',
            'requester_name' => 'nullable',
            'notes' => 'nullable',
            'section_id' => 'required',
            'files.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx|max:2048',
        ]);

        if ($request->hasfile('files')) {
            $data = [];
            foreach ($request->file('files') as $file) {
                $name = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/uploads/', $name);
                $data[] = $name;
            }
        }

        $purchase = Purchase::findOrFail($id);
        $purchase->subject = $request->get('subject');
        $purchase->description = $request->get('description');
        $purchase->notes = $request->get('notes');
        $purchase->requester_name = $request->get('requester_name');
        $purchase->section_id = $request->get('section_id');

        if (isset($data)) {
            $purchase->files = json_encode($data);
        }

        if ($purchase->save()) {
            return redirect('purchase')->with('info', 'تم التعديل بنجاح!');
        } else {
            return redirect('purchase')->with('warning', 'تحقق من صحة البيانات!');
        }
    }

    public function destroy($id)
    {
        Purchase::destroy($id);
        return redirect('purchase')->with('danger', 'تم الحذف بنجاح!');
    }

    // توقيع الرئيس
    public function signPresident($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->president_signature = true;
        $purchase->save();

        return redirect()->back()->with('success', 'تم توقيع الرئيس بنجاح!');
    }

    // إلغاء توقيع الرئيس
    public function unsignPresident($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->president_signature = false;
        $purchase->status = 'rejected';
        $purchase->save();

        return redirect()->back()->with('warning', 'تم إلغاء التوقيع، حالة الطلب أصبحت مرفوض!');
    }

    // توقيع مقدم الطلب
    public function signRequester($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->requester_signature = true;
        $purchase->save();

        return redirect()->back()->with('success', 'تم توقيع مقدم الطلب بنجاح!');
    }

    // تحديث الحالة
    public function updateStatus(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);

        $request->validate([
            'status' => 'required|in:purchase_order,tender_committee,rent_repair,rejected',
            'description' => 'nullable',
            'notes' => 'nullable',
            'files.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,DWG,DXF,DWF',
        ]);

        $fromStatus = $purchase->status;
        $toStatus = $request->status;

        $purchase->status = $toStatus;
        $purchase->description = $request->get('description');
        $purchase->notes = $request->get('notes');

        if ($request->hasfile('files')) {
            $data = [];
            foreach ($request->file('files') as $file) {
                $name = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/uploads/', $name);
                $data[] = $name;
            }
            $purchase->files = json_encode($data);
        }

        // إذا لم يوقع الرئيس => مرفوض
        if (!$purchase->president_signature && $toStatus != 'rejected') {
            return redirect()->back()->with('warning', 'لا يمكن تغيير الحالة بدون توقيع الرئيس!');
        }

        $purchase->save();

        // Log the status change
        PurchaseRequestStatusLog::create([
            'purchase_id' => $purchase->id,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'changed_by' => Auth::id(),
            'note' => $request->get('notes'),
        ]);

        // Fire the event
        event(new PurchaseRequestStatusChanged(
            $purchase,
            $fromStatus,
            $toStatus,
            Auth::id(),
            $request->get('notes')
        ));

        // Redirect with appropriate message
        $statusLabels = config('purchase.status_labels');
        $toLabel = $statusLabels[$toStatus] ?? $toStatus;

        // Handle specific status redirects
        if ($toStatus == 'tender_committee') {
            return redirect()->route('purchase.council', $purchase->id)->with('info', 'يرجى إضافة مرفقات قرار المجلس البلدي');
        }

        if ($toStatus == 'rent_repair') {
            $purchase->finance_transferred = true;
            $purchase->save();
            return redirect()->back()->with('success', 'تم تحويل الطلب إلى قسم المالية!');
        }

        return redirect()->back()->with('success', 'تم تغيير الحالة إلى: ' . $toLabel);
    }

    // صفحة قرار لجنة العطاءات (إضافة مرفقات قرار المجلس البلدي)
    public function councilDecision($id)
    {
        $purchase = Purchase::findOrFail($id);
        return view('purchase.council', compact('purchase'));
    }

    // حفظ قرار لجنة العطاءات
    public function storeCouncilDecision(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);

        $this->validate($request, [
            'council_decision_status' => 'required|in:rejected,approved_stock',
            'council_decision_files.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,DWG,DXF,DWF',
        ]);

        $data = [];
        if ($request->hasfile('council_decision_files')) {
            foreach ($request->file('council_decision_files') as $file) {
                $name = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/uploads/', $name);
                $data[] = $name;
            }
        }

        $purchase->council_decision_status = $request->council_decision_status;
        $purchase->council_decision_files = json_encode($data);
        $purchase->status = $request->council_decision_status == 'approved_stock' ? 'approved_stock' : 'rejected';
        $purchase->save();

        if ($request->council_decision_status == 'approved_stock') {
            return redirect('purchase')->with('success', 'تمت الموافقة على الشراء وإدخاله للمخازن!');
        } else {
            return redirect('purchase')->with('warning', 'تم رفض الطلب من قبل لجنة العطاءات!');
        }
    }

    // توقيع قسم المالية (للمسار 3: استئجار + إصلاح)
    public function signFinance($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->finance_signature = true;
        $purchase->save();

        return redirect()->back()->with('success', 'تم توقيع قسم المالية بنجاح!');
    }

    // إلغاء توقيع قسم المالية
    public function unsignFinance($id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->finance_signature = false;
        $purchase->save();

        return redirect()->back()->with('warning', 'تم إلغاء توقيع قسم المالية!');
    }

    // تحديث إدخال المخازن (مسار 2: لجنة العطاءات)
    public function updateStockEntry(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);

        $request->validate([
            'stock_entry_status' => 'required|in:completed,rejected',
            'invoice_attachments.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,DWG,DXF,DWF',
            'notes' => 'nullable',
        ]);

        $purchase->stock_entry_status = $request->stock_entry_status;

        // Handle file uploads
        if ($request->hasfile('invoice_attachments')) {
            $data = [];
            foreach ($request->file('invoice_attachments') as $file) {
                $name = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/uploads/', $name);
                $data[] = $name;
            }
            $purchase->invoice_attachments = json_encode($data);
        }

        $purchase->save();

        // If completed, send notification to stock department
        if ($request->stock_entry_status == 'completed') {
            $stockUsers = User::role('stock')->get();
            $invoiceFiles = $purchase->invoice_attachments ? json_decode($purchase->invoice_attachments, true) : [];
            $invoiceInfo = !empty($invoiceFiles) ? 'فاتورة رقم: ' . implode(', ', $invoiceFiles) : '';
            $message = 'تمت الموافقة على إدخال طلب رقم: ' . $purchase->serial_number . ' للمخازن. ' . $invoiceInfo . ' يرجى إدخال الأصناف فوراً.';
            
            if ($stockUsers->count() > 0) {
                Notification::send($stockUsers, new StockEntryNotification(
                    $purchase->id,
                    $purchase->serial_number,
                    $message
                ));
            }
            return redirect()->back()->with('success', 'تم إدخال الطلب للمخازن وإرسال إشعار لقسم المخازن!');
        }

        return redirect()->back()->with('warning', 'تم رفض إدخال الطلب للمخازن!');
    }

    // تحديث استئجار + إصلاح (مسار 3)
    public function updateRentRepair(Request $request, $id)
    {
        $purchase = Purchase::findOrFail($id);

        $request->validate([
            'rent_repair_status' => 'required|in:approved,rejected',
            'rent_repair_rejection_reason' => 'nullable|required_if:rent_repair_status,rejected',
            'rent_repair_attachments.*' => 'nullable|mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,DWG,DXF,DWF',
            'notes' => 'nullable',
        ]);

        $purchase->rent_repair_status = $request->rent_repair_status;
        $purchase->rent_repair_rejection_reason = $request->rent_repair_rejection_reason;

        // Handle file uploads
        if ($request->hasfile('rent_repair_attachments')) {
            $data = [];
            foreach ($request->file('rent_repair_attachments') as $file) {
                $name = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/uploads/', $name);
                $data[] = $name;
            }
            $purchase->rent_repair_attachments = json_encode($data);
        }

        $purchase->save();

        if ($request->rent_repair_status == 'approved') {
            return redirect()->back()->with('success', 'تمت الموافقة على عقد الإيجار/فاتورة التصليح!');
        } else {
            return redirect()->back()->with('warning', 'تم رفض عقد الإيجار/فاتورة التصليح!');
        }
    }

    // البحث
    public function search(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $purchases = Purchase::with('user', 'section');

        if ($search) {
            $purchases = $purchases->where('serial_number', 'like', '%' . $search . '%')
                ->orWhere('subject', 'like', '%' . $search . '%');
        }

        if ($status) {
            $purchases = $purchases->where('status', $status);
        }

        $purchases = $purchases->orderBy('created_at', 'desc')->get();

        return view('purchase.index', compact('purchases'));
    }

    // سحب طلب الشراء
    public function withdraw($id)
    {
        $purchase = Purchase::findOrFail($id);

        // Check policy
        $this->authorize('withdraw', $purchase);

        $fromStatus = $purchase->status;
        $purchase->status = 'withdrawn';
        $purchase->withdrawn = true;
        $purchase->save();

        // Log the status change
        PurchaseRequestStatusLog::create([
            'purchase_id' => $purchase->id,
            'from_status' => $fromStatus,
            'to_status' => 'withdrawn',
            'changed_by' => Auth::id(),
            'note' => 'تم سحب الطلب من قبل صاحب الطلب',
        ]);

        // Fire the event
        event(new PurchaseRequestStatusChanged(
            $purchase,
            $fromStatus,
            'withdrawn',
            Auth::id(),
            'تم سحب الطلب من قبل صاحب الطلب'
        ));

        return redirect()->back()->with('success', 'تم سحب طلب الشراء بنجاح!');
    }

    // إعادة فتح طلب الشراء المرفوض
    public function reopen(Request $request, $id)
    {
        $originalPurchase = Purchase::findOrFail($id);

        // Check policy
        $this->authorize('reopen', $originalPurchase);

        $request->validate([
            'subject' => 'required',
            'description' => 'nullable',
            'requester_name' => 'nullable',
            'notes' => 'nullable',
            'section_id' => 'required',
        ]);

        // Generate new serial number
        $lastPurchase = Purchase::orderBy('id', 'desc')->first();
        $lastSerial = $lastPurchase ? intval($lastPurchase->serial_number) : 0;
        $serialNumber = str_pad($lastSerial + 1, 4, '0', STR_PAD_LEFT);

        // Handle file uploads
        $data = [];
        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $file) {
                $name = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path() . '/uploads/', $name);
                $data[] = $name;
            }
        } else {
            // Copy old files if no new files uploaded
            $data = $originalPurchase->files ?? [];
        }

        // Create new purchase request
        $newPurchase = new Purchase([
            'serial_number' => $serialNumber,
            'subject' => $request->get('subject'),
            'description' => $request->get('description'),
            'notes' => $request->get('notes'),
            'requester_name' => $request->get('requester_name'),
            'section_id' => $request->get('section_id'),
            'user_id' => Auth::id(),
            'files' => json_encode($data),
            'status' => 'pending',
            'president_signature' => false,
            'requester_signature' => false,
            'reopened_from_id' => $originalPurchase->id,
            'revision_number' => $originalPurchase->revision_number + 1,
        ]);

        $newPurchase->save();

        // Log the status change for original
        PurchaseRequestStatusLog::create([
            'purchase_id' => $originalPurchase->id,
            'from_status' => $originalPurchase->status,
            'to_status' => 'reopened',
            'changed_by' => Auth::id(),
            'note' => 'تم إعادة فتح الطلب - إنشاء نسخة جديدة: ' . $newPurchase->serial_number,
        ]);

        return redirect('purchase/' . $newPurchase->id)->with('success', 'تم إعادة فتح الطلب وإنشاء نسخة جديدة بنجاح!');
    }
}
