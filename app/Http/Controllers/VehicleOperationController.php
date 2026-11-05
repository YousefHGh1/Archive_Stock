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
        $query = VehicleOperation::with(['vehicle', 'driver', 'dieselExport', 'invoiceExport']);

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
        $driver = ! empty($data['driver_id'])
            ? Driver::findOrFail($data['driver_id'])
            : null;

        if (! $vehicle->is_active || ($driver && ! $driver->is_active)) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'المركبة، والسائق عند اختياره، يجب أن يكونا نشطين.'
                ], 400);
            }
            return back()->withInput()->with('warning', 'المركبة، والسائق عند اختياره، يجب أن يكونا نشطين.');
        }

        $data['created_by'] = $request->user()->id ?? null;
        $data['idempotency_key'] = $data['idempotency_key'] ?? Str::uuid()->toString();

        // منع تكرار الخصم عند إعادة إرسال الطلب
        $existing = VehicleOperation::where('idempotency_key', $data['idempotency_key'])->first();
        if ($existing) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'العملية مسجلة مسبقاً، تم تجاهل التكرار.'
                ], 400);
            }
            return redirect()->route('vehicle-operations.index')->with('info', 'العملية مسجلة مسبقاً، تم تجاهل التكرار.');
        }

        DB::transaction(function () use ($data) {
            VehicleOperation::create($data);
        });

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل العملية بنجاح.'
            ], 201);
        }

        return redirect()->route('vehicle-operations.index')->with('success', 'تم تسجيل العملية بنجاح.');
    }

    public function show($id)
    {
        $operation = VehicleOperation::with(['vehicle', 'driver', 'creator'])->findOrFail($id);

        return view('vehicle_operations.show', compact('operation'));
    }

    public function importFromInventory(Request $request)
    {
        // جلب جميع سندات صرف المخازن التي ليس لها عملية مرتبطة
        $invoiceExports = \App\Models\InvoiceExport::with('InvoiceExport_product.item')
            ->whereDoesntHave('vehicleOperation')
            ->get();

        $importedCount = 0;
        $skippedCount = 0;
        $noVehicleCount = 0;

        foreach ($invoiceExports as $invoiceExport) {
            // تحليل الأصناف في السند
            $hasOil = false;
            $hasRecycledOil = false;
            $hasMaintenance = false;
            $oilLiters = 0;
            $recycledOilLiters = 0;
            $maintenanceCost = 0;

            foreach ($invoiceExport->InvoiceExport_product as $product) {
                if ($product->item) {
                    $itemName = trim($product->item->item_name);
                    $itemNum = $product->item->item_num;

                    // تحقق من زيت - مطابقة دقيقة فقط "زيت"
                    if ($itemName === 'زيت') {
                        $hasOil = true;
                        $oilLiters += $product->quantity;
                    }
                    // تحقق من زيت مكرر - مطابقة دقيقة فقط "زيت مكرر"
                    elseif ($itemName === 'زيت مكرر') {
                        $hasRecycledOil = true;
                        $recycledOilLiters += $product->quantity;
                    }
                    // تحقق من أصناف الصيانة (تبدأ بـ 4)
                    elseif (strpos($itemNum, '4') === 0) {
                        $hasMaintenance = true;
                        $maintenanceCost += $product->quantity;
                    }
                }
            }

            // إذا كان هناك أكثر من نوع واحد، تخطي السند
            $typeCount = ($hasOil ? 1 : 0) + ($hasRecycledOil ? 1 : 0) + ($hasMaintenance ? 1 : 0);
            if ($typeCount > 1) {
                $skippedCount++;
                continue;
            }

            // إذا لم يكن هناك أي صنف معروف، تخطي السند
            if (!$hasOil && !$hasRecycledOil && !$hasMaintenance) {
                $skippedCount++;
                continue;
            }

            // الحصول على المركبة المرتبطة أو البحث عنها
            $vehicle = null;
            if ($invoiceExport->vehicle_id) {
                $vehicle = \App\Models\Vehicle::find($invoiceExport->vehicle_id);
            } else {
                // البحث عن مركبة تطابق اسم الجهة المستلمة
                $vehicle = \App\Models\Vehicle::where('vehicle_type', 'like', '%' . $invoiceExport->beneficiary . '%')
                    ->orWhere('plate_number', 'like', '%' . $invoiceExport->beneficiary . '%')
                    ->first();
            }

            // إذا لم يوجد مركبة، تخطي السند
            if (!$vehicle) {
                $noVehicleCount++;
                continue;
            }

            // إنشاء العملية بناءً على نوع الأصناف
            if ($hasOil) {
                $vehicleOperation = new VehicleOperation();
                $vehicleOperation->event_type = 'oil';
                $vehicleOperation->vehicle_id = $vehicle->id;
                $vehicleOperation->driver_id = $vehicle->driver_id;
                $vehicleOperation->invoice_export_id = $invoiceExport->id;
                $vehicleOperation->liters = $oilLiters;
                $vehicleOperation->operation_date = $invoiceExport->voucher_date;
                $vehicleOperation->notes = $invoiceExport->notes;
                $vehicleOperation->created_by = $request->user()->id ?? null;
                $vehicleOperation->idempotency_key = \Illuminate\Support\Str::uuid()->toString();
                $vehicleOperation->save();
                $importedCount++;
            } elseif ($hasRecycledOil) {
                $vehicleOperation = new VehicleOperation();
                $vehicleOperation->event_type = 'recycled_oil';
                $vehicleOperation->vehicle_id = $vehicle->id;
                $vehicleOperation->driver_id = $vehicle->driver_id;
                $vehicleOperation->invoice_export_id = $invoiceExport->id;
                $vehicleOperation->liters = $recycledOilLiters;
                $vehicleOperation->operation_date = $invoiceExport->voucher_date;
                $vehicleOperation->notes = $invoiceExport->notes;
                $vehicleOperation->created_by = $request->user()->id ?? null;
                $vehicleOperation->idempotency_key = \Illuminate\Support\Str::uuid()->toString();
                $vehicleOperation->save();
                $importedCount++;
            } elseif ($hasMaintenance) {
                $vehicleOperation = new VehicleOperation();
                $vehicleOperation->event_type = 'maintenance';
                $vehicleOperation->vehicle_id = $vehicle->id;
                $vehicleOperation->driver_id = $vehicle->driver_id;
                $vehicleOperation->invoice_export_id = $invoiceExport->id;
                $vehicleOperation->maintenance_cost = $maintenanceCost;
                $vehicleOperation->operation_date = $invoiceExport->voucher_date;
                $vehicleOperation->notes = $invoiceExport->notes;
                $vehicleOperation->created_by = $request->user()->id ?? null;
                $vehicleOperation->idempotency_key = \Illuminate\Support\Str::uuid()->toString();
                $vehicleOperation->save();
                $importedCount++;
            }
        }

        return redirect()->route('vehicle-operations.index')
            ->with('success', "تم استيراد {$importedCount} عملية بنجاح. تم تخطي {$skippedCount} سند (أصناف مختلطة أو غير معروفة). تم تخطي {$noVehicleCount} سند (لا يوجد مركبة مطابقة).");
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
        $driver = ! empty($data['driver_id'])
            ? Driver::findOrFail($data['driver_id'])
            : null;

        if (! $vehicle->is_active || ($driver && ! $driver->is_active)) {
            return back()->withInput()->with('warning', 'المركبة، والسائق عند اختياره، يجب أن يكونا نشطين.');
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

        // التحقق من أن العملية ليست مرتبطة بسند صرف
        if ($operation->invoiceExport) {
            return redirect()->route('vehicle-operations.index')->with('error', 'لا يمكن حذف هذه العملية لأنها مرتبطة بسند صرف من المخازن. يجب حذف السند أولاً.');
        }

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
