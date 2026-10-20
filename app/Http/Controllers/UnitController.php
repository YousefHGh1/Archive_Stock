<?php
namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $next_num = Unit::max('unit_num') + 1;
        if (! $next_num) {
            $next_num = 1;
        }
        return view('inventory.unit.index', compact('unit', 'next_num'));
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
        $validator = Validator::make($request->all(), [
            'unit_name' => 'required|string|min:2',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }
            return redirect('inventory/unit/create')
                ->withErrors($validator)
                ->withInput();
        }

        // حفظ الوحدة في قاعدة البيانات
        $unit            = new Unit();
        $unit->unit_num  = $next_num;
        $unit->unit_name = $request->input('unit_name');

        if ($unit->save()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'id' => $unit->id,
                    'unit_num' => $unit->unit_num,
                    'unit_name' => $unit->unit_name,
                ], 201);
            }

            return redirect('inventory/unit/create')->with('success', '!تم حفظ الوحدة بنجاح');
        } else {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'تحقق من صحة البيانات!'
                ], 400);
            }
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
        $validator = Validator::make($request->all(), [
            'unit_name' => 'required|string|min:2',
            'unit_num' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $unit            = Unit::find($id);
        $unit->unit_num  = $request->unit_num;
        $unit->unit_name = $request->unit_name;
        $unit->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تمت التعديل بنجاح!'
            ], 200);
        }

        return redirect('inventory/unit')->with('success', 'تمت التعديل بنجاح!');
    }

    public function destroy(Request $request, $id)
    {
        $unit = Unit::find($id);

        if (! $unit) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'الوحدة غير موجود'
                ], 404);
            }
            session()->flash('error', 'الوحدة غير موجود');
            return redirect()->route('unit.index');
        }

        if ($unit->Items()->count() > 0) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن حذف الوحدة لأنه مرتبط بصنف.'
                ], 400);
            }
            session()->flash('warning', 'لا يمكن حذف الوحدة لأنه مرتبط بصنف.');
            return redirect()->route('unit.index');
        }

        $unit->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم الحذف بنجاح.'
            ], 200);
        }

        session()->flash('success', 'تم الحذف بنجاح.');
        return redirect()->route('unit.index');
    }

}
