<?php
namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:units-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:units-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:units-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:units-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        // $unit= Unit::simplepaginate(5);
        $unit = Unit::all();
        return view('inventory.unit.index', compact('unit'));
    }

    public function create()
    {

        $next_num = Unit::max('unit_num') + 1;
        if (! $next_num) {
            $next_num = 1;
        }
        return view('inventory.unit.create', compact('next_num'));
    }

    public function store(Request $request)
    {
        // الحصول على أعلى قيمة مخزنة في عمود unit_num
        $next_num = Unit::max('unit_num') + 1;

        // إذا لم يتم العثور على أي سجلات، يتم تعيين القيمة 1 إلى $next_num
        if (! $next_num) {
            $next_num = 1;
        }

        // التحقق من صحة البيانات المدخلة
        $request->validate([
            'unit_name' => 'required|string|min:2',
        ]);

        // حفظ الوحدة في قاعدة البيانات
        $unit            = new Unit();
        $unit->unit_num  = $next_num;
        $unit->unit_name = $request->input('unit_name');

        if ($unit->save()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'id' => $unit->id,
                    'unit_num' => $unit->unit_num,
                    'unit_name' => $unit->unit_name,
                ], 201);
            }

            return redirect('inventory/unit/create')->with('success', '!تم حفظ الوحدة بنجاح');
        } else {
            return redirect('inventory/unit/create')->with('warning', '  تحقق من صحة البيانات!');
        }

    }

    public function edit($id)
    {
        $unit = Unit::find($id);
        return view('inventory.unit.edit', compact('unit'));
    }

    public function show($id)
    {
        $unit = Unit::find($id);
        // $unit = Unit::all();
        return view('inventory.unit.show', compact('unit'))->with('unit', $unit);
    }

    public function update(Request $request, $id)
    {
        $unit            = Unit::find($id);
        $unit->unit_num  = $request->unit_num;
        $unit->unit_name = $request->unit_name;
        $unit->save();
        return redirect('inventory/unit')->with('success', 'تمت التعديل بنجاح!');
    }

    public function destroy($id)
    {
        $unit = Unit::find($id);

        if (! $unit) {
            session()->flash('error', 'الوحدة غير موجود');
            return redirect()->route('unit.index');
        }

        if ($unit->Items()->count() > 0) {
            session()->flash('warning', 'لا يمكن حذف الوحدة لأنه مرتبط بصنف.');
            return redirect()->route('unit.index');
        }

        $unit->delete();
        session()->flash('success', 'تم الحذف بنجاح.');
        return redirect()->route('unit.index');
    }

}
