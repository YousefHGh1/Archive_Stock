<?php

namespace App\Http\Controllers;

use App\Exports\ReportTableExport;
use App\Models\Item;
use App\Models\Stocktake;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StocktakeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:stocktakes-read', ['only' => ['index', 'show', 'report']]);
        $this->middleware('permission:stocktakes-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:stocktakes-update', ['only' => ['edit', 'update', 'approve']]);
        $this->middleware('permission:stocktakes-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $stocktakes = Stocktake::with('user:id,employee_name')->latest('id')->paginate(10);

        return view('stocktake.index', compact('stocktakes'));
    }

    public function create()
    {
        return view('stocktake.create', [
            'items' => $this->itemsWithBookQuantity(today()->toDateString()),
        ]);
    }

    /**
     * إنشاء جلسة جرد جديدة: لقطة للكميات الدفترية وقت الجرد + الكميات الفعلية المُدخلة.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'voucher_no' => ['required', 'string', 'max:50', 'unique:stocktakes,voucher_no'],
            'stocktake_date' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
            'counted' => ['required', 'array', 'min:1'],
            'counted.*' => ['nullable', 'numeric', 'min:0'],
        ]);

        $stocktake = DB::transaction(function () use ($validated, $request) {
            $bookQuantities = $this->bookQuantities($validated['stocktake_date']);
            $validItemIds = array_flip(Item::pluck('id')->all());

            $stocktake = Stocktake::create([
                'voucher_no' => $validated['voucher_no'],
                'stocktake_date' => $validated['stocktake_date'],
                'status' => Stocktake::STATUS_DRAFT,
                'notes' => $validated['notes'] ?? null,
                'user_id' => $request->user()?->id,
            ]);

            $rows = [];
            foreach (array_intersect_key($validated['counted'], $validItemIds) as $itemId => $counted) {
                $rows[] = [
                    'stocktake_id' => $stocktake->id,
                    'item_id' => (int) $itemId,
                    'book_quantity' => round($bookQuantities[$itemId] ?? 0, 2),
                    'counted_quantity' => $counted === null ? null : (float) $counted,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('stocktake_items')->insert($rows);

            return $stocktake;
        });

        return redirect()->route('stocktake.show', $stocktake->id)
            ->with('success', 'تم إنشاء جلسة الجرد بنجاح.');
    }

    public function show($id)
    {
        $stocktake = Stocktake::with([
            'items.item.category:id,category_name',
            'items.item.unit:id,unit_name',
            'user:id,employee_name',
        ])->findOrFail($id);

        return view('stocktake.show', compact('stocktake'));
    }

    public function edit($id)
    {
        $stocktake = Stocktake::with(['items.item.unit:id,unit_name'])->findOrFail($id);

        if ($stocktake->isApproved()) {
            return redirect()->route('stocktake.show', $stocktake->id)
                ->with('warning', 'لا يمكن تعديل جرد مُعتمد.');
        }

        return view('stocktake.edit', compact('stocktake'));
    }

    /**
     * تعديل الكميات الفعلية وأسباب الاختلاف في جرد مسودة (الكمية الدفترية ثابتة).
     */
    public function update(Request $request, $id)
    {
        $stocktake = Stocktake::findOrFail($id);

        if ($stocktake->isApproved()) {
            return redirect()->route('stocktake.show', $stocktake->id)
                ->with('warning', 'لا يمكن تعديل جرد مُعتمد.');
        }

        $validated = $request->validate([
            'counted' => ['required', 'array', 'min:1'],
            'counted.*' => ['nullable', 'numeric', 'min:0'],
            'reason' => ['nullable', 'array'],
            'reason.*' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($stocktake, $validated) {
            foreach ($stocktake->items as $stocktakeItem) {
                if (! array_key_exists($stocktakeItem->item_id, $validated['counted'])) {
                    continue;
                }

                $counted = $validated['counted'][$stocktakeItem->item_id];

                $stocktakeItem->update([
                    'counted_quantity' => $counted === null ? null : (float) $counted,
                    'reason' => $validated['reason'][$stocktakeItem->item_id] ?? $stocktakeItem->reason,
                ]);
            }
        });

        return redirect()->route('stocktake.show', $stocktake->id)
            ->with('success', 'تم تحديث كميات الجرد.');
    }

    /**
     * اعتماد الجرد وتسوية أرصدة الأصناف مرة واحدة فقط
     * (فرق = فعلية − دفترية يُضاف إلى الرصيد الحالي).
     */
    public function approve(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $stocktake = Stocktake::whereKey($id)->lockForUpdate()->firstOrFail();

            if ($stocktake->isApproved()) {
                return redirect()->route('stocktake.show', $id)
                    ->with('warning', 'هذه الجلسة مُعتمدة مسبقًا؛ لن يتكرر أثر التسوية.');
            }

            $countedItems = $stocktake->items()->whereNotNull('counted_quantity')->get();

            foreach ($countedItems as $stocktakeItem) {
                $difference = round((float) $stocktakeItem->counted_quantity - (float) $stocktakeItem->book_quantity, 2);

                if ($difference == 0.0) {
                    continue;
                }

                $item = Item::findOrFail($stocktakeItem->item_id);
                $item->balance = (float) $item->balance + $difference;
                $item->update();
            }

            $stocktake->update([
                'status' => Stocktake::STATUS_APPROVED,
                'approved_at' => now(),
                'user_id' => $request->user()?->id ?? $stocktake->user_id,
            ]);

            return redirect()->route('stocktake.show', $id)
                ->with('success', 'تم اعتماد الجرد وتسوية أرصدة الأصناف بنجاح.');
        });
    }

    public function destroy($id)
    {
        $stocktake = Stocktake::findOrFail($id);

        if ($stocktake->isApproved()) {
            return redirect()->route('stocktake.index')
                ->with('warning', 'لا يمكن حذف جرد مُعتمد.');
        }

        $stocktake->delete();

        return redirect()->route('stocktake.index')
            ->with('success', 'تم حذف جلسة الجرد.');
    }

    // __APPEND__
}