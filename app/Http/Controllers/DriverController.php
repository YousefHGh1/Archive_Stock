<?php

namespace App\Http\Controllers;

use App\Http\Requests\DriverRequest;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:drivers-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:drivers-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:drivers-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:drivers-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Driver::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('is_active') && in_array($request->input('is_active'), ['0', '1'], true)) {
            $query->where('is_active', $request->input('is_active'));
        }

        $drivers = $query->orderByDesc('id')->paginate(10);

        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function store(DriverRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        Driver::create($data);

        return redirect()->route('drivers.index')->with('success', 'تمت إضافة السائق بنجاح.');
    }

    public function show($id)
    {
        $driver = Driver::with(['operations.vehicle'])->findOrFail($id);

        return view('drivers.show', compact('driver'));
    }

    public function edit($id)
    {
        $driver = Driver::findOrFail($id);

        return view('drivers.edit', compact('driver'));
    }

    public function update(DriverRequest $request, $id)
    {
        $driver = Driver::findOrFail($id);
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $driver->update($data);

        return redirect()->route('drivers.index')->with('success', 'تم تعديل السائق بنجاح.');
    }

    public function destroy($id)
    {
        $driver = Driver::findOrFail($id);

        if ($driver->operations()->exists()) {
            return redirect()->route('drivers.index')->with('warning', 'لا يمكن حذف السائق لوجود عمليات مسجلة له. يمكنك إيقافه بدلاً من حذفه.');
        }

        $driver->delete();

        return redirect()->route('drivers.index')->with('success', 'تم حذف السائق بنجاح.');
    }
}
