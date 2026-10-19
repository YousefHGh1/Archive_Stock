@extends('layout.master')
@section('title') الاستهلاك الشهري @endsection
@section('content')
<div class="container"><div class="card card-custom"><div class="card-header"><h3 class="card-label">إجمالي الاستهلاك الشهري</h3></div>
<div class="card-body">
<form method="GET" class="row mb-3"><div class="col-md-4"><input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control"></div>
<div class="col-md-4"><input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control"></div>
<div class="col-md-4"><button class="btn btn-light-primary">تصفية</button> <a href="{{ request()->fullUrlWithQuery(['export'=>'excel']) }}" class="btn btn-success">Excel</a> <a href="{{ request()->fullUrlWithQuery(['export'=>'pdf']) }}" class="btn btn-danger">PDF</a></div></form>
<div class="table-responsive"><table class="table"><thead><tr><th>الشهر</th><th>العمليات</th><th>اللترات</th></tr></thead>
<tbody>@forelse($rows as $r)<tr><td>{{ $r->m }}</td><td>{{ $r->c }}</td><td>{{ (float)$r->t }}</td></tr>@empty<tr><td colspan="3" class="text-center">لا توجد بيانات</td></tr>@endforelse</tbody></table></div>
</div></div></div>
@endsection
