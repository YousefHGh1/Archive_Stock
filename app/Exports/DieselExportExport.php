<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DieselExportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $dieselExports;

    public function __construct($dieselExports)
    {
        $this->dieselExports = $dieselExports;
    }

    public function collection()
    {
        return $this->dieselExports;
    }

    public function headings(): array
    {
        return [
            'نوع المحروقات',
            'الدائرة',
            'القسم الفرعي',
            'رقم الإيصال',
            'كمية الصادر',
            'الجهة المستلمة',
            'رقم الدفتر',
            'تاريخ الصادر',
        ];
    }

    public function map($row): array
    {
        return [
            $row->typesfuel->name ?? '-',
            $row->section->name_section ?? '-',
            $row->subSection->name ?? '-',
            $row->voucher ?: '-',
            $row->quantity,
            $row->num_section ?: '-',
            $row->num_note ?: '-',
            $row->date,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
