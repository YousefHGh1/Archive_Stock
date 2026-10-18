<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>قائمة أوامر الشراء</title>
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
            border-bottom: 3px solid #0d4f4f;
            padding-bottom: 12px;
        }

        .header h1 {
            color: #0d4f4f;
            font-size: 20px;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #0d4f4f;
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
        <h1>قائمة أوامر الشراء</h1>
        <p>عدد الأوامر: {{ $purchaseOrders->count() }} | تاريخ الإنشاء: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>رقم المسلسل</th>
                <th>طلب الشراء الأصلي</th>
                <th>التاريخ</th>
                <th>الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchaseOrders as $index => $order)
                @php
                    $statusLabels = ['pending' => 'قيد الانتظار', 'approved' => 'موافق عليه', 'rejected' => 'مرفوض'];
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->serial_number }}</td>
                    <td>{{ optional($order->purchase)->serial_number ?? '-' }}</td>
                    <td>{{ $order->purchase_date ?? '-' }}</td>
                    <td>{{ $statusLabels[$order->status] ?? $order->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">تم إنشاء هذا التقرير بتاريخ {{ now()->format('Y-m-d H:i') }}</div>
</body>

</html>
