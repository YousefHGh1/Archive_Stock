<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VehicleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:vehicles-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:vehicles-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:vehicles-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:vehicles-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Vehicle::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('plate_number', 'like', "%{$search}%")
                    ->orWhere('vin', 'like', "%{$search}%")
                    ->orWhere('vehicle_type', 'like', "%{$search}%");
            });
        }

        if ($request->filled('is_active') && in_array($request->input('is_active'), ['0', '1'], true)) {
            $query->where('is_active', $request->input('is_active'));
        }

        $vehicles = $query->orderByDesc('id')->paginate(10);

        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(VehicleRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('vehicles', 'public');
        }

        unset($data['image']);

        Vehicle::create($data);

        return redirect()->route('vehicles.index')->with('success', 'تمت إضافة المركبة بنجاح.');
    }

    public function show($id)
    {
        $vehicle = Vehicle::with(['operations.driver'])->findOrFail($id);

        return view('vehicles.show', compact('vehicle'));
    }

    public function edit($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(VehicleRequest $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($vehicle->image_path) {
                Storage::disk('public')->delete($vehicle->image_path);
            }
            $data['image_path'] = $request->file('image')->store('vehicles', 'public');
        }

        unset($data['image']);

        $vehicle->update($data);

        return redirect()->route('vehicles.index')->with('success', 'تم تعديل المركبة بنجاح.');
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);

        if ($vehicle->operations()->exists()) {
            return redirect()->route('vehicles.index')->with('warning', 'لا يمكن حذف المركبة لوجود عمليات مرتبطة بها. يمكنك إيقافها بدلاً من حذفها.');
        }

        if ($vehicle->image_path) {
            Storage::disk('public')->delete($vehicle->image_path);
        }

        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success', 'تم حذف المركبة بنجاح.');
    }
}
