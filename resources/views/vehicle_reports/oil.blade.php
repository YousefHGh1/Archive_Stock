@extends('layout.master')
@section('title') استهلاك الزيوت @endsection
@section('content')
<div class="container"><div class="card card-custom"><div class="card-header"><h3 class="card-label">استهلاك الزيوت حسب المركبة</h3></div>
<div class="card-body">
<form method="GET" class="row mb-3">
<div class="col-md-3"><select name="vehicle_id" class="form-control"><option value="">كل المركبات</option>@foreach($vehicles as $v)<option value="{{ $v->id }}" @selected(request('vehicle_id')==$v->id)>{{ $v->plate_number }}</option>@endforeach</select></div>
<div class="col-md-3"><input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control"></div>
<div class="col-md-3"><input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control"></div>
<div class="col-md-3"><button class="btn btn-light-primary">تصفية</button> <a href="{{ request()->fullUrlWithQuery(['export'=>'excel']) }}" class="btn btn-success">Excel</a> <a href="{{ request()->fullUrlWithQuery(['export'=>'pdf']) }}" class="btn btn-danger">PDF</a></div>
</form>
<div class="table-responsive"><table class="table"><thead><tr><th>المركبة</th><th>الرقم</th><th>العمليات</th><th>اللترات</th></tr></thead>
<tbody>@forelse($rows as $r)<tr><td>{{ $r->vehicle->vehicle_type ?? '—' }}</td><td>{{ $r->vehicle->plate_number ?? '—' }}</td><td>{{ $r->c }}</td><td>{{ (float)$r->t }}</td></tr>@empty<tr><td colspan="4" class="text-center">لا توجد بيانات</td></tr>@endforelse</tbody></table></div>
</div></div></div>
@endsection
