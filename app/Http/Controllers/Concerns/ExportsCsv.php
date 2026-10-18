<?php

namespace App\Http\Controllers\Concerns;

use Symfony\Component\HttpFoundation\StreamedResponse;

trait ExportsCsv
{
    /**
     * تصدير صفوف التقرير إلى ملف CSV يفتح مباشرة في Excel مع دعم اللغة العربية (UTF-8 BOM).
     *
     * @param  iterable<array>  $rows
     */
    protected function exportReportCsv(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, $headers);

            foreach ($rows as $row) {
                fputcsv($out, $row);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
