@extends('layout.master')
@section('title') تعديل عملية @endsection
@section('content')
<div class="container"><div class="card card-custom"><div class="card-header"><h3 class="card-label">تعديل عملية</h3></div>
<form method="POST" action="{{ route('vehicle-operations.update',$operation->id) }}"><div class="card-body">@csrf @method('PUT')
<div class="row">
<div class="form-group col-md-4"><label>نوع الحدث *</label><select id="event_type" name="event_type" class="form-control" required><option value="diesel" @selected($operation->event_type=='diesel')>سولار</option><option value="oil" @selected($operation->event_type=='oil')>زيت</option><option value="maintenance" @selected($operation->event_type=='maintenance')>صيانة</option></select></div>
<div class="form-group col-md-4"><label>المركبة *</label><select name="vehicle_id" class="form-control" required>@foreach($vehicles as $v)<option value="{{ $v->id }}" @selected($operation->vehicle_id==$v->id)>{{ $v->plate_number }}</option>@endforeach</select></div>
<div class="form-group col-md-4"><label>السائق *</label><select name="driver_id" class="form-control" required>@foreach($drivers as $d)<option value="{{ $d->id }}" @selected($operation->driver_id==$d->id)>{{ $d->name }}</option>@endforeach</select></div>
<div class="form-group col-md-6" id="liters_wrap"><label>كمية اللترات</label><input type="number" step="0.01" min="0.01" name="liters" value="{{ old('liters',$operation->liters) }}" class="form-control"></div>
<div class="form-group col-md-6" id="cost_wrap" style="display:none"><label>تكلفة الصيانة بالشيكل</label><input type="number" step="0.01" min="0" name="maintenance_cost" value="{{ old('maintenance_cost',$operation->maintenance_cost) }}" class="form-control"></div>
<div class="form-group col-md-6"><label>تاريخ العملية *</label><input type="date" name="operation_date" value="{{ old('operation_date',$operation->operation_date->format('Y-m-d')) }}" class="form-control" required></div>
<div class="form-group col-12"><label>ملاحظات</label><textarea name="notes" class="form-control">{{ old('notes',$operation->notes) }}</textarea></div>
</div></div>
<div class="card-footer"><button class="btn btn-primary">حفظ</button> <a href="{{ route('vehicle-operations.index') }}" class="btn btn-secondary">رجوع</a></div>
</form></div></div>
@endsection
@section('scripts')
<script>
(function(){var t=document.getElementById('event_type'),l=document.getElementById('liters_wrap'),c=document.getElementById('cost_wrap');function g(){var m=t.value==='maintenance';l.style.display=m?'none':'';c.style.display=m?'':'none';}t.addEventListener('change',g);g();})();
</script>
@endsection
