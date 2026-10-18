<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: DejaVu Sans, sans-serif; direction: rtl; text-align: right; font-size: 10px; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        .subtitle { color: #666; margin: 0 0 6px; }
        .exported-at { color: #666; margin: 0 0 14px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 5px; }
        th { background: #e8edf2; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    @if ($subtitle)
        <p class="subtitle">{{ $subtitle }}</p>
    @endif
    <p class="exported-at">تاريخ التصدير: {{ now()->format('Y-m-d H:i') }}</p>
    <table>
        <thead><tr>@foreach ($headings as $heading)<th>{{ $heading }}</th>@endforeach</tr></thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>@foreach ($row as $value)<td>{{ $value }}</td>@endforeach</tr>
            @empty
                <tr><td colspan="{{ count($headings) }}">لا توجد بيانات مطابقة.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
