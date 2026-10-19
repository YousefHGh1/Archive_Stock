@extends('layout.master')
@section('title') تقرير إجمالي @endsection
@section('content')
<div class="container"><div class="card card-custom"><div class="card-header"><h3 class="card-label">تقرير إجمالي المحروقات والصيانة</h3></div>
<div class="card-body">
<form method="GET" class="row mb-3">
<div class="col-md-3"><select name="vehicle_id" class="form-control"><option value="">كل المركبات</option>@foreach($vehicles as $v)<option value="{{ $v->id }}" @selected(request('vehicle_id')==$v->id)>{{ $v->plate_number }}</option>@endforeach</select></div>
<div class="col-md-3"><input type="date" name="start_date" value="{{ request('start_date') }}" class="form-control"></div>
<div class="col-md-3"><input type="date" name="end_date" value="{{ request('end_date') }}" class="form-control"></div>
<div class="col-md-3"><button class="btn btn-light-primary">تصفية</button> <a href="{{ request()->fullUrlWithQuery(['export'=>'excel']) }}" class="btn btn-success">Excel</a> <a href="{{ request()->fullUrlWithQuery(['export'=>'pdf']) }}" class="btn btn-danger">PDF</a></div>
</form>
<div class="table-responsive"><table class="table"><thead><tr><th>البند</th><th>القيمة</th></tr></thead>
<tbody>
<tr><td>سولار (لتر)</td><td>{{ (float)$totals['diesel'] }}</td></tr>
<tr><td>زيت (لتر)</td><td>{{ (float)$totals['oil'] }}</td></tr>
<tr><td>صيانة (شيكل)</td><td>{{ (float)$totals['maintenance'] }}</td></tr>
<tr><td>عدد العمليات</td><td>{{ (int)$totals['count'] }}</td></tr>
</tbody></table></div>
</div></div></div>
@endsection
