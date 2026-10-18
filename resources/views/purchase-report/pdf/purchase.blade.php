<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>طلب شراء: {{ $purchase->serial_number }}</title>
    <style>
        * { font-family: 'DejaVu Sans', sans-serif; }
        body { direction: rtl; text-align: right; margin: 20px; color: #333; font-size: 12px; }
        .header { text-align: center; margin-bottom: 25px; border-bottom: 3px solid #1e3a5f; padding-bottom: 12px; }
        .header h1 { color: #1e3a5f; font-size: 20px; margin: 0; }
        .section-title { background: #1e3a5f; color: #fff; padding: 8px 15px; border-radius: 5px; font-size: 13px; font-weight: bold; margin: 15px 0 8px; }
        table { width: 100%; border-collapse: collapse; }
        td, th { border: 1px solid #ddd; padding: 7px 10px; }
        th { background: #f5f5f5; font-weight: bold; width: 20%; }
        .sig-box { border: 1px dashed #999; text-align: center; padding: 10px; min-height: 70px; }
        .sig-box h4 { margin: 0 0 25px; color: #1e3a5f; font-size: 12px; }
        .done { color: #155724; font-weight: bold; }
        .not-done { color: #721c24; font-weight: bold; }
        .footer { text-align: center; font-size: 10px; color: #999; margin-top: 30px; border-top: 1px solid #eee; padding-top: 8px; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 10px; font-size: 11px; font-weight: bold; }
        .bg-pending { background: #fff3cd; color: #856404; }
        .bg-pur { background: #d4edda; color: #155724; }
        .bg-tender { background: #cce5ff; color: #004085; }
        .bg-rent { background: #e2d9f3; color: #4b2e83; }
        .bg-rejected { background: #f8d7da; color: #721c24; }
        .bg-stock { background: #d4edda; color: #155724; }
    </style>
</head>
<body>
    <div class="header">
        <h1>طلب شراء</h1>
        <p>رقم: {{ $purchase->serial_number }} | {{ $purchase->created_at ? $purchase->created_at->format('Y-m-d') : '-' }}</p>
    </div>

    @php
        $statusLabels = config('purchase.status_labels', []);
        $badgeClass = 'bg-' . ($purchase->status ?? 'pending');
        $badgeClass = in_array($purchase->status, ['purchase_order', 'approved_stock']) ? 'bg-pur bg-stock' : ($purchase->status == 'tender_committee' ? 'bg-tender' : ($purchase->status == 'rent_repair' ? 'bg-rent' : ($purchase->status == 'rejected' ? 'bg-rejected' : 'bg-pending')));
    @endphp
    <div class="section-title">بيانات الطلب</div>
    <table>
        <tr>
            <th>القسم</th>
            <td>{{ optional($purchase->section)->name_section ?? '-' }}</td>
            <th>مقدم الطلب</th>
            <td>{{ $purchase->requester_name ?? '-' }}</td>
        </tr>
        <tr>
            <th>الموضوع</th>
            <td colspan="3">{{ $purchase->subject }}</td>
        </tr>
        @if ($purchase->description)
        <tr>
            <th>الوصف</th>
            <td colspan="3">{{ $purchase->description }}</td>
        </tr>
        @endif
        @if ($purchase->notes)
        <tr>
            <th>ملاحظات</th>
            <td colspan="3">{{ $purchase->notes }}</td>
        </tr>
        @endif
        <tr>
            <th>الحالة</th>
            <td><span class="badge {{ $badgeClass }}">{{ $statusLabels[$purchase->status] ?? $purchase->status }}</span></td>
            <th>النظام</th>
            <td>{{ $purchase->revision_number ? 'مراجعة ' . $purchase->revision_number : 'أصلي' }}</td>
        </tr>
    </table>

    <div class="section-title">التوقيعات</div>
    <table>
        <tr>
            <td style="width:33%"><div class="sig-box"><h4>توقيع الرئيس</h4><span class="{{ $purchase->president_signature ? 'done' : 'not-done' }}">{{ $purchase->president_signature ? 'تم التوقيع' : 'لم يتم' }}</span></div></td>
            <td style="width:33%"><div class="sig-box"><h4>مقدم الطلب</h4><span class="{{ $purchase->requester_signature ? 'done' : 'not-done' }}">{{ $purchase->requester_signature ? 'تم التوقيع' : 'لم يتم' }}</span></div></td>
            <td style="width:33%"><div class="sig-box"><h4>المالية</h4><span class="{{ $purchase->finance_signature ? 'done' : 'not-done' }}">{{ $purchase->finance_signature ? 'تم التوقيع' : 'لم يتم' }}</span></div></td>
        </tr>
    </table>

    @if ($purchase->council_decision_status)
    <div class="section-title">قرار المجلس البلدي</div>
    <table>
        <tr>
            <th>القرار</th>
            <td>{{ $purchase->council_decision_status == 'approved_stock' ? 'موافق عليه - إدخال للمخازن' : 'مرفوض' }}</td>
        </tr>
    </table>
    @endif

    @if ($purchase->stock_entry_status)
    <div class="section-title">إدخال المخازن</div>
    <table>
        <tr>
            <th>الحالة</th>
            <td>{{ $purchase->stock_entry_status == 'completed' ? 'تم الإدخال للمخازن' : 'مرفوض' }}</td>
        </tr>
    </table>
    @endif

    <div class="footer">تم إنشاء هذا التقرير بتاريخ {{ now()->format('Y-m-d H:i') }}</div>
</body>
</html>
