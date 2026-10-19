@extends('layout.master')
@section('title') عمليات المركبات @endsection
@section('content')
<div class="container"><div class="card card-custom">
<div class="card-header flex-wrap pt-6 pb-0 border-0"><div class="card-title"><h3 class="card-label">عمليات المركبات</h3></div>
<div class="card-toolbar">@can('vehicle-operations-create')<a href="{{ route('vehicle-operations.create') }}" class="btn btn-primary">تسجيل عملية</a>@endcan</div></div>
<div class="card-body">
<form method="GET" class="row mb-4">
<div class="col-md-3"><select name="vehicle_id" class="form-control"><option value="">كل المركبات</option>@foreach($vehicles as $v)<option value="{{ $v->id }}" @selected(request('vehicle_id')==$v->id)>{{ $v->plate_number }}</option>@endforeach</select></div>
<div class="col-md-3"><select name="driver_id" class="form-control"><option value="">كل السائقين</option>@foreach($drivers as $d)<option value="{{ $d->id }}" @selected(request('driver_id')==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
<div class="col-md-2"><select name="event_type" class="form-control"><option value="">كل الأنواع</option><option value="diesel" @selected(request('event_type')=='diesel')>سولار</option><option value="oil" @selected(request('event_type')=='oil')>زيت</option><option value="maintenance" @selected(request('event_type')=='maintenance')>صيانة</option></select></div>
<div class="col-md-2"><input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control"></div>
<div class="col-md-2"><input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control"></div>
<div class="col-md-12 mt-2"><button class="btn btn-light-primary">بحث</button></div>
</form>
<div class="table-responsive"><table class="table"><thead><tr><th>النوع</th><th>المركبة</th><th>السائق</th><th>اللترات</th><th>التكلفة</th><th>التاريخ</th><th>إجراءات</th></tr></thead>
<tbody>@forelse($operations as $o)<tr><td>{{ $o->type_label }}</td><td>{{ $o->vehicle->plate_number ?? '—' }}</td><td>{{ $o->driver->name ?? '—' }}</td><td>{{ $o->liters ?? '—' }}</td><td>{{ $o->maintenance_cost ?? '—' }}</td><td>{{ $o->operation_date->format('Y-m-d') }}</td>
<td>@can('vehicle-operations-read')<a href="{{ route('vehicle-operations.show',$o->id) }}" class="btn btn-sm btn-clean btn-icon"><i class="la la-eye"></i></a>@endcan
@can('vehicle-operations-update')<a href="{{ route('vehicle-operations.edit',$o->id) }}" class="btn btn-sm btn-clean btn-icon"><i class="la la-edit"></i></a>@endcan
@can('vehicle-operations-delete')<form action="{{ route('vehicle-operations.destroy',$o->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل تريد الحذف؟')">@csrf @method('DELETE')<button class="btn btn-sm btn-clean btn-icon"><i class="la la-trash"></i></button></form>@endcan</td></tr>
@empty<tr><td colspan="7" class="text-center">لا توجد بيانات</td></tr>@endforelse</tbody></table></div>
{!! $operations->withQueryString()->links('pagination::bootstrap-5') !!}
</div></div></div>
@endsection
