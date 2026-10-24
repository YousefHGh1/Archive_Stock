@extends('layout.master')
@section('title') تسجيل عملية @endsection
@section('content')
<div class="container"><div class="card card-custom"><div class="card-header"><h3 class="card-label">تسجيل عملية مركبة</h3></div>
<form method="POST" action="{{ route('vehicle-operations.store') }}"><div class="card-body">@csrf
<div class="row">
<div class="form-group col-md-4"><label>نوع الحدث *</label><select id="event_type" name="event_type" class="form-control" required><option value="diesel" @selected(old('event_type')=='diesel')>سولار</option><option value="oil" @selected(old('event_type')=='oil')>زيت</option><option value="recycled_oil" @selected(old('event_type')=='recycled_oil')>أضف زيت مكرر</option><option value="maintenance" @selected(old('event_type')=='maintenance')>صيانة</option></select>@error('event_type')<span class="text-danger">{{ $message }}</span>@enderror</div>
<div class="form-group col-md-4"><label>المركبة *</label><select name="vehicle_id" class="form-control" required><option value="">اختر</option>@foreach($vehicles as $v)<option value="{{ $v->id }}" @selected(old('vehicle_id')==$v->id)>{{ $v->plate_number }} - {{ $v->vehicle_type }}</option>@endforeach</select>@error('vehicle_id')<span class="text-danger">{{ $message }}</span>@enderror</div>
<div class="form-group col-md-4"><label>السائق *</label><select name="driver_id" class="form-control" required><option value="">اختر</option>@foreach($drivers as $d)<option value="{{ $d->id }}" @selected(old('driver_id')==$d->id)>{{ $d->name }}</option>@endforeach</select>@error('driver_id')<span class="text-danger">{{ $message }}</span>@enderror</div>
<div class="form-group col-md-4" id="export_wrap"><label>سند الصادر (اختياري)</label><select name="diesel_export_id" class="form-control"><option value="">اختر سند صادر</option>@foreach($dieselExports as $de)<option value="{{ $de->id }}" @selected(old('diesel_export_id')==$de->id)>{{ $de->date }} - {{ $de->voucher }} - {{ $de->quantity }} لتر - {{ $de->typesfuel->name ?? '' }}</option>@endforeach</select>@error('diesel_export_id')<span class="text-danger">{{ $message }}</span>@enderror</div>
<div class="form-group col-md-6" id="liters_wrap"><label>كمية اللترات *</label><input type="number" step="0.01" min="0.01" name="liters" value="{{ old('liters') }}" class="form-control">@error('liters')<span class="text-danger">{{ $message }}</span>@enderror</div>
<div class="form-group col-md-6" id="cost_wrap" style="display:none"><label>تكلفة الصيانة بالشيكل *</label><input type="number" step="0.01" min="0" name="maintenance_cost" value="{{ old('maintenance_cost') }}" class="form-control">@error('maintenance_cost')<span class="text-danger">{{ $message }}</span>@enderror</div>
<div class="form-group col-md-6"><label>تاريخ العملية *</label><input type="date" name="operation_date" value="{{ old('operation_date', date('Y-m-d')) }}" class="form-control" required></div>
<div class="form-group col-12"><label>ملاحظات</label><textarea name="notes" class="form-control">{{ old('notes') }}</textarea></div>
</div></div>
<div class="card-footer"><button id="op_submit" class="btn btn-primary">حفظ</button> <a href="{{ route('vehicle-operations.index') }}" class="btn btn-secondary">رجوع</a></div>
</form></div></div>
@endsection
@section('scripts')
<script>
(function(){
var type=document.getElementById('event_type'),lw=document.getElementById('liters_wrap'),cw=document.getElementById('cost_wrap'),btn=document.getElementById('op_submit');
function toggle(){var m=type.value==='maintenance';lw.style.display=m?'none':'';cw.style.display=m?'':'none';if(m){var l=lw.querySelector('input');if(l)l.value='';}else{var c=cw.querySelector('input');if(c)c.value='';}}
type.addEventListener('change',toggle);toggle();
btn.closest('form').addEventListener('submit',function(){btn.disabled=true;btn.textContent='جارٍ الحفظ...';});
})();
</script>
@endsection
