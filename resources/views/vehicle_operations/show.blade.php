@extends('layout.master')
@section('title') تفاصيل عملية @endsection
@section('content')
<div class="container">
    <div class="card card-custom">
        <div class="card-header">
            <h3 class="card-label">تفاصيل العملية</h3>
        </div>
        <div class="card-body">
            <p><strong>النوع:</strong> {{ $operation->type_label }}</p>
            <p><strong>المركبة:</strong> {{ $operation->vehicle->plate_number ?? '—' }} ({{ $operation->vehicle->vehicle_type ?? '—' }})</p>
            <p><strong>السائق:</strong> {{ $operation->driver->name ?? '—' }}</p>

            @if($operation->diesel_export_id)
                <p><strong>سند الصادر:</strong> {{ $operation->dieselExport->date ?? '—' }} - رقم الإيصال: {{ $operation->dieselExport->voucher ?? '—' }} - الكمية: {{ $operation->dieselExport->quantity ?? '—' }} لتر</p>
            @endif

            @if($operation->invoice_export_id && $operation->invoiceExport)
                <p><strong>سند الصرف من المخازن:</strong> رقم {{ $operation->invoiceExport->voucher_no ?? '—' }} - التاريخ: {{ $operation->invoiceExport->voucher_date ?? '—' }}</p>

                @if($operation->invoiceExport->InvoiceExport_product && $operation->invoiceExport->InvoiceExport_product->count() > 0)
                    <div class="mt-4">
                        <h5 class="mb-3">تفاصيل الأصناف:</h5>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>رقم الصنف</th>
                                    <th>اسم الصنف</th>
                                    <th>العدد/الكمية</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($operation->invoiceExport->InvoiceExport_product as $product)
                                    <tr>
                                        <td>{{ $product->item->item_num ?? '—' }}</td>
                                        <td>{{ $product->item->item_name ?? '—' }}</td>
                                        <td>{{ $product->quantity ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif

            <p><strong>اللترات:</strong> {{ $operation->liters ?? '—' }} | <strong>التكلفة:</strong> {{ $operation->maintenance_cost ?? '—' }}</p>
            <p><strong>التاريخ:</strong> {{ $operation->operation_date->format('Y-m-d') }} | <strong>أنشأها:</strong> {{ $operation->creator->name ?? '—' }}</p>
            <p><strong>ملاحظات:</strong> {{ $operation->notes ?? '—' }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('vehicle-operations.index') }}" class="btn btn-secondary">رجوع</a>
        </div>
    </div>
</div>
@endsection
