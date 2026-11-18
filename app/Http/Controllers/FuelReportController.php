<?php

namespace App\Http\Controllers;

use App\Models\Diesel;
use App\Models\DieselExport;
use App\Models\TypesFuel;
use Illuminate\Http\Request;

class FuelReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:diesel-read', ['only' => ['comprehensiveReport']]);
        $this->middleware('permission:dieselexport-read', ['only' => ['comprehensiveReport']]);
    }

    public function comprehensiveReport(Request $request)
    {
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;

        // جلب جميع أنواع المحروقات
        $typesFuel = TypesFuel::all();

        // تطبيق الفلاتر
        $applyFilters = function ($query) use ($request, $currentYear) {
            // إذا تم تحديد تاريخ، نستخدمه بدلاً من السنة الحالية
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('date', [$request->start_date, $request->end_date]);
            } else {
                $query->whereYear('date', $currentYear);
            }

            if ($request->filled('typesfuel_id')) {
                $query->where('typesfuel_id', $request->typesfuel_id);
            }

            return $query;
        };

        // بيانات الوارد لكل نوع
        $importData = [];
        foreach ($typesFuel as $type) {
            $importQuery = $applyFilters(Diesel::where('typesfuel_id', $type->id));
            $importData[$type->id] = [
                'name' => $type->name,
                'total_imported' => $importQuery->sum('quantity'),
                'entries_count' => $importQuery->count(),
            ];
        }

        // بيانات الصادر لكل نوع
        $exportData = [];
        foreach ($typesFuel as $type) {
            $exportQuery = $applyFilters(DieselExport::where('typesfuel_id', $type->id));
            $exportData[$type->id] = [
                'name' => $type->name,
                'total_exported' => $exportQuery->sum('quantity'),
                'entries_count' => $exportQuery->count(),
            ];
        }

        // حساب الرصيد السابق (قبل التاريخ المحدد أو قبل السنة الحالية)
        $previousBalanceByType = [];
        foreach ($typesFuel as $type) {
            $previousQuery = Diesel::where('typesfuel_id', $type->id);
            $previousExportQuery = DieselExport::where('typesfuel_id', $type->id);

            if ($request->filled('start_date')) {
                $previousQuery->where('date', '<', $request->start_date);
                $previousExportQuery->where('date', '<', $request->start_date);
            } else {
                $previousQuery->whereYear('date', '<', $currentYear);
                $previousExportQuery->whereYear('date', '<', $currentYear);
            }

            $previousImport = $previousQuery->sum('quantity');
            $previousExport = $previousExportQuery->sum('quantity');
            $previousBalanceByType[$type->id] = $previousImport - $previousExport;
        }

        // حساب الرصيد الحالي (الإجمالي الكلي وليس فقط المفلتر)
        $currentBalanceByType = [];
        foreach ($typesFuel as $type) {
            $totalImport = Diesel::where('typesfuel_id', $type->id)->sum('quantity');
            $totalExport = DieselExport::where('typesfuel_id', $type->id)->sum('quantity');
            $currentBalanceByType[$type->id] = $totalImport - $totalExport;
        }

        // حساب الإجماليات
        $totalImported = array_sum(array_column($importData, 'total_imported'));
        $totalExported = array_sum(array_column($exportData, 'total_exported'));
        $totalPreviousBalance = array_sum($previousBalanceByType);
        $totalCurrentBalance = array_sum($currentBalanceByType);

        return view('reports.comprehensive_fuel', compact(
            'typesFuel',
            'importData',
            'exportData',
            'previousBalanceByType',
            'currentBalanceByType',
            'totalImported',
            'totalExported',
            'totalPreviousBalance',
            'totalCurrentBalance',
            'currentYear'
        ));
    }
}
