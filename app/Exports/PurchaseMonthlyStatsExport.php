<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PurchaseMonthlyStatsExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $monthlyStats;

    public function __construct($monthlyStats)
    {
        $this->monthlyStats = $monthlyStats;
    }

    public function array(): array
    {
        $rows = [];
        foreach ($this->monthlyStats as $stat) {
            $monthName = \Carbon\Carbon::create($stat->year, $stat->month, 1)->locale('ar')->monthName;
            $rows[] = [
                $monthName . ' ' . $stat->year,
                $stat->total,
                $stat->approved,
                $stat->rejected,
            ];
        }
        return $rows;
    }

    public function headings(): array
    {
        return ['الشهر', 'إجمالي الطلبات', 'تمت الموافقة', 'مرفوض'];
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }
}