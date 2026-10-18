<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>قائمة طلبات المشتريات</title>
    <style>
        * {
            font-family: 'DejaVu Sans', sans-serif;
        }

        body {
            direction: rtl;
            text-align: right;
            margin: 20px;
            color: #333;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #1e3a5f;
            padding-bottom: 12px;
        }

        .header h1 {
            color: #1e3a5f;
            font-size: 20px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #1e3a5f;
            color: #fff;
            padding: 8px 10px;
            font-size: 11px;
        }

        td,
        th {
            border: 1px solid #ddd;
            padding: 6px 8px;
        }

        tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            color: #999;
            margin-top: 25px;
            border-top: 1px solid #eee;
            padding-top: 8px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>قائمة طلبات المشتريات</h1>
        <p>عدد الطلبات: {{ $purchases->count() }} | تاريخ الإنشاء: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>رقم المسلسل</th>
                <th>الموضوع</th>
                <th>القسم</th>
                <th>مقدم الطلب</th>
                <th>التاريخ</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchases as $index => $purchase)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $purchase->serial_number }}</td>
                    <td>{{ $purchase->subject }}</td>
                    <td>{{ optional($purchase->section)->name_section ?? '-' }}</td>
                    <td>{{ $purchase->requester_name ?? '-' }}</td>
                    <td>{{ $purchase->created_at ? $purchase->created_at->format('Y-m-d') : '-' }}</td>
                    <td>{{ config('purchase.status_labels')[$purchase->status] ?? $purchase->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">تم إنشاء هذا التقرير بتاريخ {{ now()->format('Y-m-d H:i') }}</div>
</body>

</html>
