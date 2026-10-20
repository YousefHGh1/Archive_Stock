<?php

namespace App\Http\Controllers;

use App\Http\Requests\VehicleOperationRequest;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\VehicleOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VehicleOperationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:vehicle-operations-read', ['only' => ['index', 'show']]);
        $this->middleware('permission:vehicle-operations-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:vehicle-operations-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:vehicle-operations-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = VehicleOperation::with(['vehicle', 'driver', 'dieselExport']);

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->input('vehicle_id'));
        }

        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->input('driver_id'));
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->input('event_type'));
        }

        if ($request->filled('start_date')) {
            $query->where('operation_date', '>=', $request->input('start_date'));
        }

        if ($request->filled('end_date')) {
            $query->where('operation_date', '<=', $request->input('end_date'));
        }

        $operations = $query->orderByDesc('operation_date')->orderByDesc('id')->paginate(15);
        $vehicles = Vehicle::orderBy('plate_number')->get(['id', 'plate_number', 'vehicle_type']);
        $drivers = Driver::orderBy('name')->get(['id', 'name']);

        return view('vehicle_operations.index', compact('operations', 'vehicles', 'drivers'));
    }

    public function create()
    {
        $vehicles = Vehicle::where('is_active', true)->orderBy('plate_number')->get();
        $drivers = Driver::where('is_active', true)->orderBy('name')->get();
        $dieselExports = \App\Models\DieselExport::with(['section', 'subSection', 'typesfuel'])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        return view('vehicle_operations.create', compact('vehicles', 'drivers', 'dieselExports'));
    }

    public function store(VehicleOperationRequest $request)
    {
        $data = $this->normalize($request->validated());

        $vehicle = Vehicle::findOrFail($data['vehicle_id']);
        $driver = Driver::findOrFail($data['driver_id']);

        if (! $vehicle->is_active || ! $driver->is_active) {
            return back()->withInput()->with('warning', 'المركبة والسائق يجب أن يكونا نشطين.');
        }

        $data['created_by'] = $request->user()->id ?? null;
        $data['idempotency_key'] = $data['idempotency_key'] ?? Str::uuid()->toString();

        // منع تكرار الخصم عند إعادة إرسال الطلب
        $existing = VehicleOperation::where('idempotency_key', $data['idempotency_key'])->first();
        if ($existing) {
            return redirect()->route('vehicle-operations.index')->with('info', 'العملية مسجلة مسبقاً، تم تجاهل التكرار.');
        }

        DB::transaction(function () use ($data) {
            VehicleOperation::create($data);
        });

        return redirect()->route('vehicle-operations.index')->with('success', 'تم تسجيل العملية بنجاح.');
    }

    public function show($id)
    {
        $operation = VehicleOperation::with(['vehicle', 'driver', 'creator'])->findOrFail($id);

        return view('vehicle_operations.show', compact('operation'));
    }

    public function edit($id)
    {
        $operation = VehicleOperation::with('dieselExport')->findOrFail($id);
        $vehicles = Vehicle::where('is_active', true)->orWhere('id', $operation->vehicle_id)->orderBy('plate_number')->get();
        $drivers = Driver::where('is_active', true)->orWhere('id', $operation->driver_id)->orderBy('name')->get();
        $dieselExports = \App\Models\DieselExport::with(['section', 'subSection', 'typesfuel'])
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->get();

        return view('vehicle_operations.edit', compact('operation', 'vehicles', 'drivers', 'dieselExports'));
    }

    public function update(VehicleOperationRequest $request, $id)
    {
        $operation = VehicleOperation::findOrFail($id);
        $data = $this->normalize($request->validated());

        $vehicle = Vehicle::findOrFail($data['vehicle_id']);
        $driver = Driver::findOrFail($data['driver_id']);

        if (! $vehicle->is_active || ! $driver->is_active) {
            return back()->withInput()->with('warning', 'المركبة والسائق يجب أن يكونا نشطين.');
        }

        unset($data['idempotency_key']);

        DB::transaction(function () use ($operation, $data) {
            $operation->update($data);
        });

        return redirect()->route('vehicle-operations.index')->with('success', 'تم تعديل العملية بنجاح.');
    }

    public function destroy($id)
    {
        $operation = VehicleOperation::findOrFail($id);

        DB::transaction(function () use ($operation) {
            $operation->delete();
        });

        return redirect()->route('vehicle-operations.index')->with('success', 'تم حذف العملية بنجاح.');
    }

    /**
     * عند تعديل نوع الحدث امسح القيم غير الملائمة ولا تعتمد على إخفاء الحقول وحده.
     */
    protected function normalize(array $data): array
    {
        if ($data['event_type'] === VehicleOperation::TYPE_MAINTENANCE) {
            $data['liters'] = null;
        } else {
            $data['maintenance_cost'] = null;
        }

        return $data;
    }
}
