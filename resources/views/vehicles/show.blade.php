@extends('layout.master')
@section('title') تفاصيل مركبة @endsection
@section('content')
<div class="container"><div class="card card-custom"><div class="card-header"><h3 class="card-label">تفاصيل المركبة {{ $vehicle->plate_number }}</h3></div>
<div class="card-body">
@if($vehicle->image_path)<img src="{{ asset('storage/'.$vehicle->image_path) }}" style="max-width:220px" class="mb-3 img-thumbnail">@endif
<p>النوع: {{ $vehicle->vehicle_type }}</p>
<p>رقم المركبة: {{ $vehicle->plate_number }}</p>
<p>الشاصي: {{ $vehicle->vin }}</p>
<p>السنة: {{ $vehicle->manufacture_year ?? '—' }} | اللون: {{ $vehicle->color ?? '—' }} | الحالة: {{ $vehicle->is_active ? 'نشطة':'غير نشطة' }}</p>
<p>ملاحظات: {{ $vehicle->notes ?? '—' }}</p>
<h5 class="mt-4">سجل العمليات</h5>
<div class="table-responsive"><table class="table"><thead><tr><th>النوع</th><th>السائق</th><th>اللترات</th><th>التكلفة</th><th>التاريخ</th></tr></thead>
<tbody>@forelse($vehicle->operations as $o)<tr><td>{{ $o->type_label }}</td><td>{{ $o->driver->name ?? '—' }}</td><td>{{ $o->liters ?? '—' }}</td><td>{{ $o->maintenance_cost ?? '—' }}</td><td>{{ $o->operation_date->format('Y-m-d') }}</td></tr>@empty<tr><td colspan="5" class="text-center">لا توجد عمليات</td></tr>@endforelse</tbody></table></div>
</div>
<div class="card-footer"><a href="{{ route('vehicles.index') }}" class="btn btn-secondary">رجوع</a></div>
</div></div>
@endsection
