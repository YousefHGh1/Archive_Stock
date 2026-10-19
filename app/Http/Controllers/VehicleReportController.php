<?php

namespace App\Http\Controllers;

use App\Exports\ReportTableExport;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\VehicleOperation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class VehicleReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:vehicle-reports-read');
    }

    public function index()
    {
        $vehicles = Vehicle::orderBy('plate_number')->get(['id', 'plate_number']);
        $drivers = Driver::orderBy('name')->get(['id', 'name']);
        return view('vehicle_reports.index', compact('vehicles', 'drivers'));
    }

    protected function base(Request $r)
    {
        $q = VehicleOperation::with(['vehicle', 'driver']);
        if ($r->filled('vehicle_id')) $q->where('vehicle_id', $r->vehicle_id);
        if ($r->filled('driver_id')) $q->where('driver_id', $r->driver_id);
        if ($r->filled('event_type')) $q->where('event_type', $r->event_type);
        if ($r->filled('start_date')) $q->where('operation_date', '>=', $r->start_date);
        if ($r->filled('end_date')) $q->where('operation_date', '<=', $r->end_date);
        return $q;
    }

    protected function lists(): array
    {
        return [
            'vehicles' => Vehicle::orderBy('plate_number')->get(['id', 'plate_number']),
            'drivers' => Driver::orderBy('name')->get(['id', 'name']),
        ];
    }

    protected function out(Request $r, $title, $headings, $rows, $file, $view, $data)
    {
        if ($r->export === 'excel') return Excel::download(new ReportTableExport($title, null, $headings, $rows), $file . '.xlsx');
        if ($r->export === 'pdf') return Pdf::loadView('reports.pdf.table', compact('title', 'headings', 'rows'))->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'DejaVu Sans'])->download($file . '.pdf');
        return view($view, array_merge($data, $this->lists()));
    }
    public function diesel(Request $r)
    {
        $rows = $this->base($r)->where('event_type', 'diesel')->select('vehicle_id', DB::raw('SUM(liters) t'), DB::raw('COUNT(*) c'))->groupBy('vehicle_id')->with('vehicle')->get();
        $ex = $rows->map(fn ($x) => [$x->vehicle->vehicle_type ?? '—', $x->vehicle->plate_number ?? '—', $x->c, (float) $x->t])->all();
        return $this->out($r, 'استهلاك السولار', ['المركبة', 'الرقم', 'العمليات', 'اللترات'], $ex, 'v-diesel', 'vehicle_reports.diesel', compact('rows'));
    }

    public function oil(Request $r)
    {
        $rows = $this->base($r)->where('event_type', 'oil')->select('vehicle_id', DB::raw('SUM(liters) t'), DB::raw('COUNT(*) c'))->groupBy('vehicle_id')->with('vehicle')->get();
        $ex = $rows->map(fn ($x) => [$x->vehicle->vehicle_type ?? '—', $x->vehicle->plate_number ?? '—', $x->c, (float) $x->t])->all();
        return $this->out($r, 'استهلاك الزيوت', ['المركبة', 'الرقم', 'العمليات', 'اللترات'], $ex, 'v-oil', 'vehicle_reports.oil', compact('rows'));
    }

    public function monthly(Request $r)
    {
        $rows = $this->base($r)->whereIn('event_type', ['diesel', 'oil'])->select(DB::raw("DATE_FORMAT(operation_date,'%Y-%m') m"), DB::raw('SUM(liters) t'), DB::raw('COUNT(*) c'))->groupBy('m')->orderBy('m')->get();
        $ex = $rows->map(fn ($x) => [$x->m, $x->c, (float) $x->t])->all();
        return $this->out($r, 'الاستهلاك الشهري', ['الشهر', 'العمليات', 'اللترات'], $ex, 'v-monthly', 'vehicle_reports.monthly', compact('rows'));
    }
    public function maintenance(Request $r)
    {
        $rows = $this->base($r)->where('event_type', 'maintenance')->select('vehicle_id', DB::raw('SUM(maintenance_cost) t'), DB::raw('COUNT(*) c'))->groupBy('vehicle_id')->with('vehicle')->get();
        $ex = $rows->map(fn ($x) => [$x->vehicle->vehicle_type ?? '—', $x->vehicle->plate_number ?? '—', $x->c, (float) $x->t])->all();
        return $this->out($r, 'تكاليف الصيانة', ['المركبة', 'الرقم', 'العمليات', 'التكلفة (شيكل)'], $ex, 'v-maint', 'vehicle_reports.maintenance', compact('rows'));
    }

    public function driver(Request $r)
    {
        $ops = $this->base($r)->orderByDesc('operation_date')->paginate(20);
        $ex = array_map(fn ($o) => [$o->type_label, $o->vehicle->plate_number ?? '—', $o->driver->name ?? '—', $o->liters !== null ? (float) $o->liters : '—', $o->maintenance_cost !== null ? (float) $o->maintenance_cost : '—', $o->operation_date?->format('Y-m-d')], $ops->items());
        if ($r->export === 'excel') return Excel::download(new ReportTableExport('سجل السائق', null, ['النوع', 'المركبة', 'السائق', 'اللترات', 'التكلفة', 'التاريخ'], $ex), 'v-driver.xlsx');
        if ($r->export === 'pdf') return Pdf::loadView('reports.pdf.table', ['title' => 'سجل السائق', 'headings' => ['النوع', 'المركبة', 'السائق', 'اللترات', 'التكلفة', 'التاريخ'], 'rows' => $ex])->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'DejaVu Sans'])->download('v-driver.pdf');
        return view('vehicle_reports.driver', array_merge(['operations' => $ops], $this->lists()));
    }

    public function summary(Request $r)
    {
        $b = $this->base($r);
        $totals = ['diesel' => (clone $b)->where('event_type', 'diesel')->sum('liters'), 'oil' => (clone $b)->where('event_type', 'oil')->sum('liters'), 'maintenance' => (clone $b)->where('event_type', 'maintenance')->sum('maintenance_cost'), 'count' => (clone $b)->count()];
        $ex = [['سولار (لتر)', (float) $totals['diesel']], ['زيت (لتر)', (float) $totals['oil']], ['صيانة (شيكل)', (float) $totals['maintenance']], ['عدد العمليات', (int) $totals['count']]];
        if ($r->export === 'excel') return Excel::download(new ReportTableExport('تقرير إجمالي', null, ['البند', 'القيمة'], $ex), 'v-summary.xlsx');
        if ($r->export === 'pdf') return Pdf::loadView('reports.pdf.table', ['title' => 'تقرير إجمالي', 'headings' => ['البند', 'القيمة'], 'rows' => $ex])->setPaper('a4', 'landscape')->setOptions(['defaultFont' => 'DejaVu Sans'])->download('v-summary.pdf');
        return view('vehicle_reports.summary', array_merge(compact('totals'), $this->lists()));
    }
}
