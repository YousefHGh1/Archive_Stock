<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Unit;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:items-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:items-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:items-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:items-delete', ['only' => ['destroy']]);
    }
public function index(Request $request)
{
    $query = Item::with('category', 'unit');

    // البحث باسم التصنيف
    if ($request->filled('category_name')) {
        $query->whereHas('category', function ($q) use ($request) {
            $q->where('category_name', 'LIKE', '%' . $request->category_name . '%');
        });
    }

    // البحث برقم التصنيف
    if ($request->filled('category_num')) {
        $query->whereHas('category', function ($q) use ($request) {
            $q->where('category_num', 'LIKE', '%' . $request->category_num . '%');
        });
    }

    // البحث باسم الصنف
    if ($request->filled('item_name')) {
        $query->where('item_name', 'LIKE', '%' . $request->item_name . '%');
    }

    // البحث برقم الصنف
    if ($request->filled('item_num')) {
        $query->where('item_num', 'LIKE', '%' . $request->item_num . '%');
    }

    // 🔽 الترتيب حسب رقم الصنف تصاعدياً (ترتيب رقمي صحيح)
    $query->orderByRaw('CAST(item_num AS UNSIGNED) ASC');

    // Pagination
    $item = $query->paginate(10);

    // جلب التصنيفات والوحدات
    $category = Category::get(['id', 'category_name', 'category_num']);
    $units = Unit::get(['id', 'unit_name', 'unit_num']);

    return view('inventory.item.index', compact('item', 'category', 'units'));
}

    // public function index(Request $request)
    // {
    //     $query = Item::with('category', 'unit');

    //     // Check if a category search query exists
    //     if ($request->has('category_name') && $request->input('category_name') != '') {
    //         $category = $request->input('category_name');
    //         $query->whereHas('category', function ($q) use ($category) {
    //             $q->where('category_name', 'LIKE', "%{$category}%");
    //         });
    //     }

    //     // Check if a category search query exists
    //     if ($request->has('category_num') && $request->input('category_num') != '') {
    //         $category = $request->input('category_num');
    //         $query->whereHas('category', function ($q) use ($category) {
    //             $q->where('category_num', 'LIKE', "%{$category}%");
    //         });
    //     }

    //     // Check if an item name search query exists
    //     if ($request->has('item_name') && $request->input('item_name') != '') {
    //         $itemName = $request->input('item_name');
    //         $query->where('item_name', 'LIKE', "%{$itemName}%");
    //     }

    //     // Check if an item number search query exists
    //     if ($request->has('item_num') && $request->input('item_num') != '') {
    //         $itemNum = $request->input('item_num');
    //         $query->where('item_num', 'LIKE', "%{$itemNum}%");
    //     }

    //     // Paginate the results
    //     $item = $query->paginate(10);
    //     $category = Category::get(['id', 'category_name', 'category_num']);

    //     return view('inventory.item.index', compact('item', 'category'));
    // }

    public function create()
    {
        $items = Item::all();
        $units = Unit::all();
        $categories = Category::all();

        return view('inventory.item.create', compact('items', 'units', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'item_num' => 'required|integer|unique:items,item_num',
            'item_name' => 'required|string|max:255|unique:items,item_name',
            'open_balance' => 'required|numeric|min:0',
            'low_limit' => 'nullable|numeric|min:0',
        ]);

        $item = new Item;
        $item->fill($validated);
        $item->balance = $validated['open_balance'];

        if ($item->save()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'id' => $item->id,
                    'item_num' => $item->item_num,
                    'item_name' => $item->item_name,
                    'balance' => $item->balance,
                    'category_id' => $item->category_id,
                    'unit_id' => $item->unit_id,
                    'open_balance' => $item->open_balance,
                    'low_limit' => $item->low_limit,
                    'category_name' => $item->category->category_name ?? '',
                    'unit_name' => $item->unit->unit_name ?? '',
                ], 201);
            }

            return redirect()->route('item.index')->with('success', 'تمت الإضافة بنجاح!');
        }

        return redirect()->back()->with('warning', 'تحقق من صحة البيانات!');
    }

    public function show($id)
    {
        $item = Item::with(['category', 'unit'])->find($id);

        if (!$item) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'الصنف غير موجود'
                ], 404);
            }
            $unit = Unit::all();
            $category = Category::all();

            return view('inventory.item.show', compact('item', 'unit', 'category'));
        }

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'item' => [
                    'id' => $item->id,
                    'item_num' => $item->item_num,
                    'item_name' => $item->item_name,
                    'balance' => $item->balance,
                    'open_balance' => $item->open_balance,
                    'low_limit' => $item->low_limit,
                    'category_id' => $item->category_id,
                    'unit_id' => $item->unit_id,
                    'category_name' => $item->category->category_name ?? '',
                    'unit_name' => $item->unit->unit_name ?? '',
                ]
            ]);
        }

        $unit = Unit::all();
        $category = Category::all();

        return view('inventory.item.show', compact('item', 'unit', 'category'));
    }

    public function edit($id)
    {
        //
        $item = item::find($id);
        $unit = Unit::all();
        $category = Category::all();

        return view('inventory.item.edit', compact('item', 'unit', 'category'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'item_num' => 'required|integer|unique:items,item_num,'.$id,
            'item_name' => 'required|string|max:255|unique:items,item_name,'.$id,
            'open_balance' => 'required|numeric|min:0',
            'low_limit' => 'nullable|numeric|min:0',
        ]);

        $item = Item::find($id);
        $old_open_balance = $item->open_balance;
        $item->fill($validated);
        $item->balance += ($validated['open_balance'] - $old_open_balance);

        if ($item->save()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تمت التعديل بنجاح!',
                    'item' => [
                        'id' => $item->id,
                        'item_num' => $item->item_num,
                        'item_name' => $item->item_name,
                        'balance' => $item->balance,
                        'category_id' => $item->category_id,
                        'unit_id' => $item->unit_id,
                        'open_balance' => $item->open_balance,
                        'low_limit' => $item->low_limit,
                        'category_name' => $item->category->category_name ?? '',
                        'unit_name' => $item->unit->unit_name ?? '',
                    ]
                ]);
            }

            return redirect()->route('item.index')->with('info', 'تمت التعديل بنجاح!');
        } else {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'تحقق من صحة البيانات!'
                ], 400);
            }
            return redirect()->back()->with('warning', 'تحقق من صحة البيانات!');
        }
    }

    public function destroy($id)
    {
        $item = Item::find($id);

        if (! $item) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'الصنف غير موجود'
                ], 404);
            }
            session()->flash('error', 'الصنف غير موجود');

            return redirect()->route('item.index');
        }

        if ($item->InvoiceProduct()->count() > 0) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف الصنف لأنه مرتبط بوارد.'
                ], 400);
            }
            session()->flash('warning', 'لا يمكن حذف الصنف لأنه مرتبط بوارد.');

            return redirect()->route('item.index');
        }

        if ($item->InvoiceExport_product()->count() > 0) {
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف الصنف لأنه مرتبط بصادر.'
                ], 400);
            }
            session()->flash('warning', 'لا يمكن حذف الصنف لأنه مرتبط  بصادر.');

            return redirect()->route('item.index');
        }

        $item->delete();

        if (request()->expectsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم الحذف بنجاح.'
            ]);
        }

        session()->flash('success', 'تم الحذف بنجاح.');

        return redirect()->route('item.index');
    }

    public function getLastItem($id)
    {
        // البحث عن آخر صنف مرتبط بالعائلة المحددة
        $lastItem = Item::where('category_id', $id)
            ->orderByRaw('CAST(item_num AS UNSIGNED) DESC') // ترتيب رقمي صحيح تنازليًا
            ->first();

        // إذا كان هناك صنف سابق
        if ($lastItem) {
            // إرجاع الرقم الأخير
            return response()->json(['last_item_num' => $lastItem->item_num]);
        }

        // إذا لم يكن هناك صنف سابق، نعيد الرقم الأول بناءً على رقم العائلة
        $category = Category::find($id);
        if ($category) {
            // استخدام رقم العائلة + 01 كأول صنف
            return response()->json(['last_item_num' => $category->category_num . '01']);
        }

        // في حالة عدم وجود العائلة
        return response()->json(['last_item_num' => $id . '01']);
    }
}