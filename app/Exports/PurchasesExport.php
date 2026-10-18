<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PurchasesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $purchases;

    public function __construct($purchases)
    {
        $this->purchases = $purchases;
    }

    public function collection()
    {
        return $this->purchases;
    }

    public function headings(): array
    {
        return [
            'رقم المسلسل',
            'الموضوع',
            'القسم',
            'مقدم الطلب',
            'التاريخ',
            'الحالة',
            'توقيع الرئيس',
        ];
    }

    public function map($purchase): array
    {
        $statusLabels = [
            'pending' => 'قيد الانتظار',
            'purchase_order' => 'أمر شراء',
            'tender_committee' => 'لجنة العطاءات',
            'rent_repair' => 'استئجار + إصلاح',
            'rejected' => 'مرفوض',
            'approved_stock' => 'موافق عليه - مخازن',
            'withdrawn' => 'مسحوب',
        ];

        return [
            $purchase->serial_number,
            $purchase->subject,
            optional($purchase->section)->name_section ?? '-',
            $purchase->requester_name ?? '-',
            $purchase->created_at ? $purchase->created_at->format('Y-m-d H:i') : '-',
            $statusLabels[$purchase->status] ?? $purchase->status,
            $purchase->president_signature ? 'تم التوقيع' : 'لم يتم',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}