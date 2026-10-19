@extends('layout.master')
@section('title') سجل السائق @endsection
@section('content')
<div class="container"><div class="card card-custom"><div class="card-header"><h3 class="card-label">سجل عمليات السائق</h3></div>
<div class="card-body">
<form method="GET" class="row mb-3">
<div class="col-md-3"><select name="driver_id" class="form-control"><option value="">كل السائقين</option>@foreach($drivers as $d)<option value="{{ $d->id }}" @selected(request('driver_id')==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
<div class="col-md-3"><input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control"></div>
<div class="col-md-3"><input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control"></div>
<div class="col-md-3"><button class="btn btn-light-primary">تصفية</button> <a href="{{ request()->fullUrlWithQuery(['export'=>'excel']) }}" class="btn btn-success">Excel</a> <a href="{{ request()->fullUrlWithQuery(['export'=>'pdf']) }}" class="btn btn-danger">PDF</a></div>
</form>
<div class="table-responsive"><table class="table"><thead><tr><th>النوع</th><th>المركبة</th><th>السائق</th><th>اللترات</th><th>التكلفة</th><th>التاريخ</th></tr></thead>
<tbody>@forelse($operations as $o)<tr><td>{{ $o->type_label }}</td><td>{{ $o->vehicle->plate_number ?? '—' }}</td><td>{{ $o->driver->name ?? '—' }}</td><td>{{ $o->liters ?? '—' }}</td><td>{{ $o->maintenance_cost ?? '—' }}</td><td>{{ $o->operation_date->format('Y-m-d') }}</td></tr>@empty<tr><td colspan="6" class="text-center">لا توجد بيانات</td></tr>@endforelse</tbody></table></div>
{!! $operations->withQueryString()->links('pagination::bootstrap-5') !!}
</div></div></div>
@endsection
