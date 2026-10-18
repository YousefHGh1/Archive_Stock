<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportTableExport implements FromArray, ShouldAutoSize, WithEvents, WithHeadings, WithStyles
{
    public function __construct(
        private string $title,
        private ?string $subtitle,
        private array $headings,
        private array $rows
    ) {}

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            4 => ['font' => ['bold' => true]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->insertNewRowBefore(1, 3);
                $sheet->setCellValue('A1', $this->title);
                $sheet->setCellValue('A2', $this->subtitle ?? '');
                $sheet->setCellValue('A3', 'تاريخ التصدير: '.now()->format('Y-m-d H:i'));
                $sheet->getStyle('A1:A3')->getFont()->setBold(true);
            },
        ];
    }
}
