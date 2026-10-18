<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PurchaseOrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $purchaseOrders;

    public function __construct($purchaseOrders)
    {
        $this->purchaseOrders = $purchaseOrders;
    }

    public function collection()
    {
        return $this->purchaseOrders;
    }

    public function headings(): array
    {
        return [
            'رقم المسلسل',
            'طلب الشراء الأصلي',
            'التاريخ',
            'الحالة',
            'توقيع المشتريات',
            'توقيع المالية',
            'توقيع مقدم الطلب',
        ];
    }

    public function map($order): array
    {
        $statusLabels = [
            'pending' => 'قيد الانتظار',
            'approved' => 'موافق عليه',
            'rejected' => 'مرفوض',
        ];

        return [
            $order->serial_number,
            optional($order->purchase)->serial_number ?? '-',
            $order->purchase_date ?? '-',
            $statusLabels[$order->status] ?? $order->status,
            $order->purchases_signature ? 'تم التوقيع' : 'لم يتم',
            $order->finance_signature ? 'تم التوقيع' : 'لم يتم',
            $order->requester_signature ? 'تم التوقيع' : 'لم يتم',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}