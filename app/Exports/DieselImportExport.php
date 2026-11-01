<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DieselImportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $dieselEntries;

    public function __construct($dieselEntries)
    {
        $this->dieselEntries = $dieselEntries;
    }

    public function collection()
    {
        return $this->dieselEntries;
    }

    public function headings(): array
    {
        return [
            'نوع المحروقات',
            'اسم المورد',
            'كمية التوريد',
            'جهة التوريد',
            'سند التوريد',
            'رقم الفاتورة',
            'تاريخ التوريد',
        ];
    }

    public function map($entry): array
    {
        return [
            $entry->typesfuel->name ?? '-',
            $entry->supplier->name_supplier ?? '-',
            $entry->quantity,
            $entry->type ?: '-',
            $entry->voucher ?: '-',
            $entry->invoice_num ?: '-',
            $entry->date,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
