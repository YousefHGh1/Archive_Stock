@extends('layout.master')
@section('title') تفاصيل عملية @endsection
@section('content')
<div class="container"><div class="card card-custom"><div class="card-header"><h3 class="card-label">تفاصيل العملية</h3></div>
<div class="card-body">
<p>النوع: {{ $operation->type_label }}</p>
<p>المركبة: {{ $operation->vehicle->plate_number ?? '—' }} | السائق: {{ $operation->driver->name ?? '—' }}</p>
@if($operation->diesel_export_id)
<p>سند الصادر: {{ $operation->dieselExport->date ?? '—' }} - {{ $operation->dieselExport->voucher ?? '—' }} - {{ $operation->dieselExport->quantity ?? '—' }} لتر</p>
@endif
<p>اللترات: {{ $operation->liters ?? '—' }} | التكلفة: {{ $operation->maintenance_cost ?? '—' }}</p>
<p>التاريخ: {{ $operation->operation_date->format('Y-m-d') }} | أنشأها: {{ $operation->creator->name ?? '—' }}</p>
<p>ملاحظات: {{ $operation->notes ?? '—' }}</p>
</div><div class="card-footer"><a href="{{ route('vehicle-operations.index') }}" class="btn btn-secondary">رجوع</a></div></div></div>
@endsection
