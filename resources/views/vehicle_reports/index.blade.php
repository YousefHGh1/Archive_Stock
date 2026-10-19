@extends('layout.master')
@section('title') تقارير المركبات @endsection
@section('content')
<div class="container"><div class="card card-custom"><div class="card-header"><h3 class="card-label">تقارير المركبات والمحروقات والصيانة</h3></div>
<div class="card-body"><div class="row">
<div class="col-md-4 mb-2"><a class="btn btn-light-primary btn-block" href="{{ route('vehicle-reports.diesel') }}">استهلاك السولار حسب المركبة</a></div>
<div class="col-md-4 mb-2"><a class="btn btn-light-primary btn-block" href="{{ route('vehicle-reports.oil') }}">استهلاك الزيوت حسب المركبة</a></div>
<div class="col-md-4 mb-2"><a class="btn btn-light-primary btn-block" href="{{ route('vehicle-reports.monthly') }}">إجمالي الاستهلاك الشهري</a></div>
<div class="col-md-4 mb-2"><a class="btn btn-light-primary btn-block" href="{{ route('vehicle-reports.maintenance') }}">تكاليف الصيانة حسب المركبة</a></div>
<div class="col-md-4 mb-2"><a class="btn btn-light-primary btn-block" href="{{ route('vehicle-reports.driver') }}">سجل عمليات السائق</a></div>
<div class="col-md-4 mb-2"><a class="btn btn-light-primary btn-block" href="{{ route('vehicle-reports.summary') }}">تقرير إجمالي</a></div>
</div></div></div></div>
@endsection
