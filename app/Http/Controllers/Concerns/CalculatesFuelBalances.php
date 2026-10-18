<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Diesel;
use App\Models\DieselExport;
use App\Models\TypesFuel;
use Illuminate\Http\Request;

trait CalculatesFuelBalances
{
    /**
     * حساب المتبقي لكل نوع من أنواع المحروقات بشكل ديناميكي
     * بدلاً من تثبيت الأنواع (سولار / بنزين) بمعرفات ثابتة.
     *
     * @return array{typesfuel: \Illuminate\Support\Collection, remainingByType: array, totalRemaining: float|int}
     */
    protected function fuelRemainingByType(Request $request): array
    {
        $typesfuel = TypesFuel::orderBy('id')->get();

        $remainingByType = [];

        foreach ($typesfuel as $type) {
            $dieselQuery = Diesel::where('typesfuel_id', $type->id);
            $dieselExportQuery = DieselExport::where('typesfuel_id', $type->id);

            // تطبيق مدى التاريخ في حال إرساله (على الوارد والصادر معاً)
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $dieselQuery->whereBetween('date', [$request->start_date, $request->end_date]);
                $dieselExportQuery->whereBetween('date', [$request->start_date, $request->end_date]);
            }

            // تطبيق فلتر الدائرة والقسم الفرعي على الصادر فقط
            // لأن جدول الوارد (diesels) لا يحتوي على الأعمدة section_id / sub_section_id
            if ($request->filled('section_id')) {
                $dieselExportQuery->where('section_id', $request->section_id);
            }

            if ($request->filled('sub_section_id')) {
                $dieselExportQuery->where('sub_section_id', $request->sub_section_id);
            }

            $remainingByType[$type->id] = $dieselQuery->sum('quantity') - $dieselExportQuery->sum('quantity');
        }

        return [
            'typesfuel' => $typesfuel,
            'remainingByType' => $remainingByType,
            'totalRemaining' => array_sum($remainingByType),
        ];
    }
}
