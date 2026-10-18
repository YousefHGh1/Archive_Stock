<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>أمر شراء: {{ $purchaseOrder->serial_number }}</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif; }
        body { direction: rtl; text-align: right; margin: 20px; color: #333; font-size: 12px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #0d4f4f; padding-bottom: 12px; }
        .header h1 { color: #0d4f4f; font-size: 20px; margin: 0; }
        .section-title { background: #0d4f4f; color: #fff; padding: 8px 15px; border-radius: 5px; font-size: 13px; font-weight: bold; margin: 15px 0 8px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #ddd; padding: 7px 10px; }
        th { background: #f5f5f5; font-weight: bold; }
        .sig-box { border: 1px dashed #999; text-align: center; padding: 10px; min-height: 70px; }
        .sig-box h4 { margin: 0 0 25px; color: #0d4f4f; font-size: 12px; }
        .done { color: #155724; font-weight: bold; }
        .not-done { color: #721c24; font-weight: bold; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 30px; border-top: 1px solid #eee; padding-top: 8px; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 10px; font-size: 11px; font-weight: bold; }
        .bg-pending { background: #fff3cd; color: #856404; }
        .bg-approved { background: #d4edda; color: #155724; }
        .bg-rejected { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="header">
        <h1>أمر شراء</h1>
        <p>رقم: {{ $purchaseOrder->serial_number }} | {{ $purchaseOrder->purchase_date ?? '-' }}</p>
    </div>

    @php
        $statusLabels = ['pending' => 'قيد الانتظار', 'approved' => 'موافق عليه', 'rejected' => 'مرفوض'];
        $badgeClass = 'bg-' . ($purchaseOrder->status ?? 'pending');
    @endphp
    <div class="section-title">بيانات الأمر</div>
    <table>
        <tr>
            <th>طلب الشراء الأصلي</th>
            <td>{{ optional($purchaseOrder->purchase)->serial_number ?? '-' }}</td>
            <th>الحالة</th>
            <td><span class="badge {{ $badgeClass }}">{{ $statusLabels[$purchaseOrder->status] ?? $purchaseOrder->status }}</span></td>
        </tr>
        @if ($purchaseOrder->rejection_reason)
        <tr>
            <th>سبب الرفض</th>
            <td colspan="3">{{ $purchaseOrder->rejection_reason }}</td>
        </tr>
        @endif
        @if ($purchaseOrder->description)
        <tr>
            <th>الوصف</th>
            <td colspan="3">{{ $purchaseOrder->description }}</td>
        </tr>
        @endif
        @if ($purchaseOrder->notes)
        <tr>
            <th>ملاحظات</th>
            <td colspan="3">{{ $purchaseOrder->notes }}</td>
        </tr>
        @endif
    </table>

    <div class="section-title">التوقيعات</div>
    <table>
        <tr>
            <td style="width:33%"><div class="sig-box"><h4>قسم المشتريات</h4><span class="{{ $purchaseOrder->purchases_signature ? 'done' : 'not-done' }}">{{ $purchaseOrder->purchases_signature ? 'تم التوقيع' : 'لم يتم' }}</span></div></td>
            <td style="width:33%"><div class="sig-box"><h4>قسم المالية</h4><span class="{{ $purchaseOrder->finance_signature ? 'done' : 'not-done' }}">{{ $purchaseOrder->finance_signature ? 'تم التوقيع' : 'لم يتم' }}</span></div></td>
            <td style="width:33%"><div class="sig-box"><h4>مقدم الطلب</h4><span class="{{ $purchaseOrder->requester_signature ? 'done' : 'not-done' }}">{{ $purchaseOrder->requester_signature ? 'تم التوقيع' : 'لم يتم' }}</span></div></td>
        </tr>
    </table>

    <div class="section-title">الأصناف المطلوبة</div>
    <table>
        <thead>
            <tr>
                <th style="width:5%">#</th>
                <th>الصنف</th>
                <th>الوحدة</th>
                <th>الفئة</th>
                <th>الكمية</th>
                <th>ملاحظات</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchaseOrder->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->item_name }}</td>
                <td>{{ $item->unit_name ?? '-' }}</td>
                <td>{{ $item->category_name ?? '-' }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">تم إنشاء هذا التقرير بتاريخ {{ now()->format('Y-m-d H:i') }}</div>
</body>
</html>