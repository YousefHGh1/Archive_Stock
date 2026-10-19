@extends('layout.master')
@section('title') تفاصيل سائق @endsection
@section('content')
<div class="container"><div class="card card-custom"><div class="card-header"><h3 class="card-label">تفاصيل السائق {{ $driver->name }}</h3></div>
<div class="card-body">
<p>الاسم: {{ $driver->name }} | الحالة: {{ $driver->is_active?'نشط':'غير نشط' }}</p>
<p>ملاحظات: {{ $driver->notes ?? '—' }}</p>
<h5 class="mt-4">سجل العمليات</h5>
<div class="table-responsive"><table class="table"><thead><tr><th>النوع</th><th>المركبة</th><th>اللترات</th><th>التكلفة</th><th>التاريخ</th></tr></thead>
<tbody>@forelse($driver->operations as $o)<tr><td>{{ $o->type_label }}</td><td>{{ $o->vehicle->plate_number ?? '—' }}</td><td>{{ $o->liters ?? '—' }}</td><td>{{ $o->maintenance_cost ?? '—' }}</td><td>{{ $o->operation_date->format('Y-m-d') }}</td></tr>@empty<tr><td colspan="5" class="text-center">لا توجد عمليات</td></tr>@endforelse</tbody></table></div>
</div><div class="card-footer"><a href="{{ route('drivers.index') }}" class="btn btn-secondary">رجوع</a></div></div></div>
@endsection
